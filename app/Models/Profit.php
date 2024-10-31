<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profit extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'total',
    ];

 
    public function profits()
    {
        return $this->hasMany(Profit::class, 'transaction_id'); 
    }
}