<?php


namespace App\Repositories;

use App\Product;
use App\ShoppingCart;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;

class ShoppingCartRepository extends AbstractRepository
{
    /**
     * ShoppingCartRepository constructor.
     * @param ShoppingCart $model
     */
    function __construct(ShoppingCart $model)
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
            ->select('shopping_carts.*');

        $joins = collect();

        if(isset($filters['user_id']) && $filters['user_id'])
        {
            $query = $query->ofUserId($filters['user_id']);
        }

        if(isset($filters['without_pay']))
        {
            $query = $query->ofPay($filters['without_pay']);
        }

        $joins->each(function ($item, $key) use (&$query) {
            $item = json_decode($item);
            $query->join($key, $item->first, '=', $item->second, $item->join_type);
        });

        if ($count) {
            return $query->count('shopping_carts.id');
        }

        logger($query->toSql());

        return $query->orderBy('shopping_carts.created_at', 'desc');
    }

    public function getByUserId(int $user_id)
    {
        return $this->search(compact('user_id'))->first();
    }

    public function associateProduct(ShoppingCart $cart, Product $product, int $quantity)
    {
        $cart->products()->attach($product->id, ['quantity' => $quantity]);
    }

    public function detachProduct(ShoppingCart $cart, Product $product)
    {
        $cart->products()->detach($product->id);
    }

    public function updateProductQuantity(ShoppingCart $cart, Product $product, int $quantity)
    {
        $cart->products()->updateExistingPivot($product->id, ['quantity' => $quantity]);
    }
}
