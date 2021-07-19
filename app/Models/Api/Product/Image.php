<?php

namespace App\Models\Api\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [

         'path'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
