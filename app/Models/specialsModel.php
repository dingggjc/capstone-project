<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class specialsModel extends Model
{
    protected $table = 'specials';
    protected $primaryKey = 'specials_id';

    protected $fillable = [
        'name',
        'description',
        'price',
        'category_id'
    ];

    public function category()
    {
        return $this->belongsTo(CategoryModel::class, 'category_id', 'category_id');
    }

    public function products()
    {
        return $this->belongsToMany(Products::class, 'product_specials', 'specials_id', 'product_inventory_id')
            ->withPivot('quantity')
            ->withTimestamps();
    }
}
