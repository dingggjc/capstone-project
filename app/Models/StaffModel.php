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
        "status",
    ];
}
