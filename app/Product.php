<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /** @var string[]  */
    protected $fillable = [
        'image_url',
        'sku',
        'name',
        'description',
        'stock',
        'price',
        'category_id'
    ];


    public function scopeOfSku($query, $sku)
    {
        if (is_array($sku) && !empty($sku)){
            return $query->whereIn('products.sku', $sku);
        }else{
            return !$sku ? $query : $query->where('products.sku', $sku);
        }
    }
}
