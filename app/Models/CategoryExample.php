<?php

namespace App\Models;

use App\Models\CategoryModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryExample extends Model
{
    use HasFactory;

    protected $primaryKey = 'category_example_id';
    protected $fillable = ['category_id', 'example_name'];

    public function category()
    {
        return $this->belongsTo(CategoryModel::class);
    }
}