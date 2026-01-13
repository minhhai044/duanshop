<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;
    protected $fillable = [
        'coupon_code',
        'discount_type',
        'discount_value',
        'start_date',
        'end_date',
        'coupon_limit',
        'coupon_used',
        'coupon_status',
        'coupon_description'
    ];

    protected $casts = [
        'discount_type' => 'boolean',
        'discount_value' => 'float',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'coupon_limit' => 'integer',
        'coupon_used' => 'integer',
        'coupon_status' => 'boolean'
    ];
    public function products(){
        return $this->belongsToMany(Product::class,'product_coupon');
    }
}
