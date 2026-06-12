<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use SoftDeletes;

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function productsAvailable()
    {
      return self::products()->where('products.status', Product::STATUS_ID_AVAILABLE);
    }
    public function series()
    {
        return $this->hasMany(Series::class);
    }
}
