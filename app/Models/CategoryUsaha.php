<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryUsaha extends Model
{
    use HasFactory;

    protected $table = 'category_usaha';

    protected $fillable = [
        'category_name'
    ];
}
