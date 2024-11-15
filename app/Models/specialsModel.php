<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class specialsModel extends Model
{
    protected $table = 'specials';
    protected $primaryKey = "specials_id";

    protected $fillable = [
        'name',
        'description',
        'price',
        'category_id'
    ];

    public function category()
    {
        return $this->belongsTo(CategoryModel::class, 'category_id', 'category_id');
    }
}