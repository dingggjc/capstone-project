<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageModel extends Model
{
    use HasFactory;

    protected $table = 't_package';
    protected $primaryKey = 'package_id';



    protected $fillable = [
        'package_name',
        'package_description',
        'package_price',
        'category_id'
    ];

    public function products()
    {
        return $this->belongsToMany(Products::class, 'package_product', 'package_id', 'product_inventory_id')
            ->withPivot('quantity')
            ->withTimestamps();
    }
    public function category()
    {
        return $this->belongsTo(CategoryModel::class, 'category_id', 'category_id');
    }
}
