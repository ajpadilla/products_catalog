<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShoppingCart extends Model
{
    /** @var string[]  */
    protected $fillable = [
        'user_id',
        'pay',
        'total_pay',
        'total_items',
        'references'
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity');
    }


    public function scopeOfUserId($query, $user_id)
    {
        if (is_array($user_id) && !empty($user_id)){
            return $query->whereIn('shopping_carts.user_id', $user_id);
        }else{
            return !$user_id ? $query : $query->where('shopping_carts.user_id', $user_id);
        }
    }

    public function scopeOfPay($query, $pay)
    {
        return $query->where('shopping_carts.pay', $pay);
    }

    public function hasProduct(Product $product){
        return $this->products()->where('product_id', $product->id)->first();
    }


    public function getProductItemsDescription()
    {
        $description = '';
        foreach ($this->products as $product){
            $description .= "[{$product->pivot->quantity} X {$product->description}]";
        }
        return $description;
    }

    public function total()
    {
        $total = 0;
        foreach ($this->products as $product){
            $total += $product->price * $product->pivot->quantity;
        }
        return $total;
    }
}
