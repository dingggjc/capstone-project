<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerModel extends Model
{
    use HasFactory;

    protected $table = 'customers';  // Table name

    protected $primaryKey = 'customer_id';  // Primary key

    protected $fillable = [
        'cus_fname',
        'cus_lname',
        'vehicle_name',
        'vehicle_plate',
        'cus_phone',
        'cus_amount',
    ];

    protected $casts = [
        'cus_amount' => 'integer',
    ];

    // Define the many-to-many relationship with products
    public function products() {}
}