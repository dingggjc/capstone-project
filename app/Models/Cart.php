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
        // 'customer_name',
        // 'customer_phone',
        // 'vehicle_type',
        // 'vehicle_plate',
        'qty',
        'price',
    ];

    /**
     * Relationship with the User model (Cashier).
     * Each cart is associated with one cashier.
     */
    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    /**
     * Relationship with the ProductInventory model.
     * Each cart item may have a single product.
     */
    public function products()
    {
        return $this->belongsTo(Products::class, 'product_inventory_id');
    }

    /**
     * Relationship with the Package model.
     * Each cart item may have a single package.
     */
    public function package()
    {
        return $this->belongsTo(PackageModel::class, 'package_id');
    }
}