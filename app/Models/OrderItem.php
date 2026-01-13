<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id',
        'product_variant_id',
        'order_item_quantity',
        'product_name',
        'product_sku',
        'product_img_thumbnail',
        'pro_price_regular',
        'pro_price_sale',
        'variant_capacity_name',
        'variant_color_name'
    ];

    protected $casts = [
        'order_item_quantity' => 'integer',
        'pro_price_regular' => 'decimal:2',
        'pro_price_sale' => 'decimal:2'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    
    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }
}
