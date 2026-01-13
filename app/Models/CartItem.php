<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_variant_id',
        'cart_item_quantity'
    ];

    protected $casts = [
        'cart_item_quantity' => 'integer'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    
    public function productVariant(){
        return $this->belongsTo(ProductVariant::class);
    }

    /**
     * Get the product through product variant
     */
    public function product(){
        return $this->productVariant->product;
    }
}
