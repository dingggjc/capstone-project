<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;

    protected $table = 'product_inventory';
    protected $primaryKey = 'product_inventory_id';
    public $timestamps = true;

    protected $fillable = [
        'product_name',
        'product_description',
        'product_price',
        'product_quantity',
    ];
    public function specials()
    {
        return $this->belongsToMany(specialsModel::class, 'product_specials', 'product_id', 'specials_id')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    protected $casts = [
        'product_price' => 'float',
    ];
}