<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryModel extends Model
{
    protected $table = "categories";
    protected $primaryKey = "category_id";

    protected $fillable = [
        'category_name',
        'category_description',
        'category_example'
    ];

    public function packages()
    {
        return $this->hasMany(PackageModel::class, 'category_id', 'category_id');
    }

    public function specials()
    {
        return $this->hasMany(specialsModel::class, 'specials_id', 'specials');
    }
}