<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // ✅ Allow mass assignment for all table columns
    protected $fillable = [
        'name',
        'sku',
        'category',
        'quantity',
        'price',
        'description',
    ];
}
