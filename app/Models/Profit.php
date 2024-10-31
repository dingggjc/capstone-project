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

    /**
     * Define the relationship to the Transaction model.
     */
    public function profits()
    {
        return $this->hasMany(Profit::class, 'transaction_id'); // Ensure foreign key is specified here
    }
}
