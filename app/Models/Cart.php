<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'carts';

    protected $fillable = [
        'cashier_id',
        'product_inventory_id',
        'package_id',
        'qty',
        'price',
    ];


    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }


    public function products()
    {
        return $this->belongsTo(Products::class, 'product_inventory_id');
    }


    public function package()
    {
        return $this->belongsTo(PackageModel::class, 'package_id');
    }
}
