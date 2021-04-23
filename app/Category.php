<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /** @var string[]  */
    protected $fillable = [
        'name',
        'status',
        'parent_id'
    ];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function childs()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function hasParent()
    {
        return $this->parent ? true : false;
    }

    public function isActive()
    {
        return $this->status == 'active';
    }

    public function isParentActive()
    {
        if ($this->parent){
            return $this->parent->status == 'active';
        }
        return false;
    }
}
