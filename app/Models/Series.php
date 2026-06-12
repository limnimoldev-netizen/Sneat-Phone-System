<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Series extends Model
{
    use SoftDeletes;

    protected $table = 'series';

    protected $fillable = ['name'];

    public function brand()
    {
      return $this->belongsTo(Brand::class);
  }

    public function products()
    {
        return $this->hasMany(Product::class, 'series_id', 'id');
    }

    public function brands()
    {
        return $this->belongsTo(Brand::class);
    }
}
