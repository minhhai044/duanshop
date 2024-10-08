<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'color_id',
        'capacity_id',
        'quantity'
    ];
    public function capatities()
    {
        return $this->belongsTo(Capacity::class);
    }

    public function colors()
    {
        return $this->belongsTo(Color::class);
    }
    public function products(){
        return $this->belongsTo(ProductVariant::class);
    }
}
