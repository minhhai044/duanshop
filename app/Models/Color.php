<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    use HasFactory;
    protected $fillable = [
        'color_name',
        'slug',
        'is_active',
        'color_code',
        'color_text'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function productVariants()
    {
        return $this->hasMany(ProductVariant::class);
    }
}
