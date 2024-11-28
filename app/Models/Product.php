<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['product_number', 'name', 'price', 'sale_price', 'image', 'sizes'];
    protected $casts = [
        'sizes' => 'array', // Automatically casts the JSON column to an array
    ];

}