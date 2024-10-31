<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Transactions extends Model
{
    use HasFactory;

    protected $table = 'transactions';

    protected $fillable = [
        'cashier_id',
        'invoice',
        'customer_name',
        'customer_phone',
        'vehicle_type',
        'vehicle_plate',
        'cash',
        'change',
        'grand_total',
        'status',
    ];


    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }
    public function details()
    {
        return $this->hasMany(TransactionDetail::class, 'transaction_id'); // Specify the foreign key
    }

    public function profits()
    {
        return $this->hasMany(Profit::class, 'transaction_id'); // Explicitly set 'transaction_id'
    }

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn($value) => Carbon::parse($value)->format('d-M-Y H:i:s'),
        );
    }
}
