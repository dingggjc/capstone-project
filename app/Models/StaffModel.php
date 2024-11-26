<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffModel extends Model
{
    protected $table = "staff";
    protected $primaryKey = "staff_id";

    protected $fillable = [
        "staff_name",
        "staff_phone",
        "staff_status",
    ];

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class, 'staff_id', 'staff_id');
    }
}