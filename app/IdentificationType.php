<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IdentificationType extends Model
{
    protected $table = 'identification_types';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description'
    ];
}
