<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'category_id',
        'pro_name',
        'pro_sku',
        'pro_slug',
        'pro_description',
        'pro_img_thumbnail',
        'pro_price_regular',
        'pro_price_sale',
        'pro_views',
        'pro_featured',
        'pro_prating',
        'is_hot',
        'is_active'
    ];

    protected $casts = [
        'pro_price_regular' => 'decimal:0',
        'pro_price_sale' => 'decimal:0',
        'pro_views' => 'integer',
        'pro_featured' => 'boolean',
        'pro_prating' => 'decimal:1',
        'is_hot' => 'boolean',
        'is_active' => 'boolean'
    ];
    public function category(){
        return $this->belongsTo(Category::class);
    }
    public function galleries()
    {
        return $this->hasMany(Gallery::class);
    }
    
    public function productVariants(){
        return $this->hasMany(ProductVariant::class);
    }

    public function coupons(){
        return $this->belongsToMany(Coupon::class, 'product_coupon');
    }

}
