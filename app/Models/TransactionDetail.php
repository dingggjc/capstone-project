<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;

    protected $table = 'transaction_details';
    protected $primaryKey = 'transaction_detail_id';

    protected $fillable = [
        'transaction_id',
        'product_inventory_id',
        'package_id',
        'qty',
        'product_price',
        'package_price',
    ];


    public function transaction()
    {
        return $this->belongsTo(Transactions::class, 'transaction_id');
    }


    public function product()
    {
        return $this->belongsTo(Products::class, 'product_inventory_id');
    }


    public function package()
    {
        return $this->belongsTo(PackageModel::class, 'package_id');
    }
}
