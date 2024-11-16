<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class otherServicesModel extends Model
{
    protected $table = "others";
    protected $primaryKey = "others_id";

    protected $fillable = [
        'others_name',
        'others_description',
        'others_price',
        'category_id',

    ];

    public function category()
    {
        return $this->belongsTo(CategoryModel::class, 'category_id', 'category_id');
    }
}
