<?php


namespace App\Repositories;

use App\Product;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;

class ProductRepository extends AbstractRepository
{
    /**
     * ProductRepository constructor.
     * @param Product $model
     */
    function __construct(Product $model)
    {
        $this->model = $model;
    }

    /**
     * @param Collection $joins
     * @param $table
     * @param $first
     * @param $second
     * @param string $join_type
     */
    private function addJoin(Collection &$joins, $table, $first, $second, $join_type = 'inner')
    {
        if (!$joins->has($table)) {
            $joins->put($table, json_encode(compact('first', 'second', 'join_type')));
        }
    }

    /**
     * @param array $filters
     * @param false $count
     * @return mixed
     */
    public function search(array $filters = [], $count = false)
    {
        /** @var Builder $query */
        $query = $this->model
            ->distinct()
            ->select('products.*');

        $joins = collect();

        if(isset($filters['sku']) && $filters['sku'])
        {
            $query = $query->ofSku($filters['sku']);
        }

        if(isset($filters['active_categories']) && $filters['active_categories'])
        {
            $this->addJoin($joins, 'categories as child', 'products.category_id', 'child.id');
            $this->addJoin($joins, 'categories as parent', 'child.parent_id', 'parent.id');
            $query->where('child.status', 'active');
            $query->where('parent.status', 'active');
            $query->where('products.stock', '>', 0);
            $query->where('products.price', '>', 0);
        }


        $joins->each(function ($item, $key) use (&$query) {
            $item = json_decode($item);
            $query->join($key, $item->first, '=', $item->second, $item->join_type);
        });

        logger($query->toSql());

        if ($count) {
            return $query->count('products.id');
        }

        return $query->orderBy('products.id');
    }

    public function getBySku(string $sku)
    {
        return $this->search(compact('sku'))->first();
    }
}
