<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;

    protected $table = 'product_inventory';  // Table name
    protected $primaryKey = 'product_inventory_id';  // Primary key column
    public $timestamps = true;  // Assuming your table has timestamps

    protected $fillable = [
        'product_name',
        'product_description',
        'product_price',
        'product_quantity',
    ];

    // Cast product_price to float
    protected $casts = [
        'product_price' => 'float',
    ];
}