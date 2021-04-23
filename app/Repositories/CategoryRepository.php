<?php


namespace App\Repositories;


use App\Category;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;


class CategoryRepository extends AbstractRepository
{
    /**
     * CategoryRepository constructor.
     * @param Category $model
     */
    function __construct(Category $model)
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
            ->select('categories.*');

        $joins = collect();

        $joins->each(function ($item, $key) use (&$query) {
            $item = json_decode($item);
            $query->join($key, $item->first, '=', $item->second, $item->join_type);
        });



        if ($count) {
            return $query->count('categories.id');
        }

        return $query->orderBy('categories.id');
    }

}
