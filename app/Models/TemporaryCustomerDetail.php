<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemporaryCustomerDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'cashier_id',
        'name',
        'phone',
        'vehicle_plate',
        'vehicle_type'
    ];
}