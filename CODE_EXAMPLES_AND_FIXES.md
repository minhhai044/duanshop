# CODE EXAMPLES AND FIXES

## 🔴 FIX #1: Product::productVariant() - Change hasOne to hasMany

### BEFORE (WRONG)
```php
// app/Models/Product.php
public function productVariant(){
    return $this->hasOne(ProductVariant::class);
}
```

### AFTER (CORRECT)
```php
// app/Models/Product.php
public function productVariants(){
    return $this->hasMany(ProductVariant::class);
}
```

### IMPACT ON CONTROLLERS

**File:** `app/Http/Controllers/Admin/ProductController.php`

#### Change 1: Line 95 in store() method
```php
// BEFORE:
foreach ($dataProductVariants as $item) {
    $product->product_variant()->create($item);
}

// AFTER:
foreach ($dataProductVariants as $item) {
    $product->productVariants()->create($item);
}
```

#### Change 2: Line 140 in edit() method
```php
// BEFORE:
$data = $this->productService->findIDRelationProduct($id, ['product_variant']);

// AFTER:
$data = $this->productService->findIDRelationProduct($id, ['productVariants']);
```

### IMPACT ON VIEWS

**File:** `resources/views/admin/products/edit.blade.php`

```blade
// BEFORE:
@foreach($data->product_variant as $variant)
    ...
@endforeach

// AFTER:
@foreach($data->productVariants as $variant)
    ...
@endforeach
```

---

## 🔴 FIX #2: Create Cart Model

### Create Model File
**File:** `app/Models/Cart.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id'
    ];

    /**
     * Get the user that owns the cart
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all cart items for this cart
     */
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Get total price of cart
     */
    public function getTotalPrice()
    {
        return $this->cartItems->sum(function($item) {
            return $item->productVariant->price * $item->cart_item_quantity;
        });
    }

    /**
     * Get total items count
     */
    public function getTotalItems()
    {
        return $this->cartItems->sum('cart_item_quantity');
    }
}
```

### Create Migration File
**File:** `database/migrations/2024_01_01_000000_create_carts_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');
            $table->timestamps();
            
            // Add unique constraint to prevent multiple carts per user
            $table->unique('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
```

### Run Migration
```bash
php artisan migrate
```

### Update CartItem Model
**File:** `app/Models/CartItem.php`

```php
// Add this relationship to CartItem model
public function cart()
{
    return $this->belongsTo(Cart::class);
}
```

---

## 🟡 FIX #3: Add CartItem::product() Relationship

### BEFORE
```php
// app/Models/CartItem.php
public function user(){
    return $this->belongsTo(User::class);
}

public function productVariant(){
    return $this->belongsTo(ProductVariant::class);
}
```

### AFTER
```php
// app/Models/CartItem.php
public function user(){
    return $this->belongsTo(User::class);
}

public function productVariant(){
    return $this->belongsTo(ProductVariant::class);
}

public function cart(){
    return $this->belongsTo(Cart::class);
}

/**
 * Get the product through product variant
 */
public function product(){
    return $this->productVariant->product;
}
```

### Usage Example
```php
// Before (had to go through ProductVariant):
$product = $cartItem->productVariant->product;

// After (direct access):
$product = $cartItem->product;
```

---

## 🟡 FIX #4: Remove Unused Fields from Product Model

### Option 1: Remove from Model (Recommended if not needed)

**File:** `app/Models/Product.php`

```php
// BEFORE:
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

// AFTER:
protected $fillable = [
    'category_id',
    'pro_name',
    'pro_sku',
    'pro_description',
    'pro_img_thumbnail',
    'pro_price_regular',
    'pro_price_sale',
    'pro_featured',
    'is_active'
];

protected $casts = [
    'pro_price_regular' => 'decimal:0',
    'pro_price_sale' => 'decimal:0',
    'pro_featured' => 'boolean',
    'is_active' => 'boolean'
];
```

### Create Migration to Drop Columns
**File:** `database/migrations/2024_01_01_000001_drop_unused_columns_from_products_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'pro_slug',
                'pro_views',
                'pro_prating',
                'is_hot'
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('pro_slug')->nullable();
            $table->integer('pro_views')->default(0);
            $table->decimal('pro_prating', 3, 1)->default(0);
            $table->boolean('is_hot')->default(false);
        });
    }
};
```

### Option 2: Use the Fields (If needed)

**Add to Product Model:**
```php
// Track product views
public function incrementViews()
{
    $this->increment('pro_views');
}

// Get average rating
public function getAverageRating()
{
    return $this->pro_prating;
}

// Check if product is hot
public function isHot()
{
    return $this->is_hot;
}

// Get slug for URL
public function getSlug()
{
    return $this->pro_slug ?? \Str::slug($this->pro_name);
}
```

**Add to ProductController:**
```php
// In detail() method:
public function detail(string $id)
{
    $dataDetails = $this->productService->findIDRelationProduct($id, ['category', 'galleries', 'product_variant']);
    
    // Increment views
    $dataDetails->incrementViews();
    
    // ... rest of code
}
```

**Add to Views:**
```blade
<!-- Show views count -->
<p>{{ $product->pro_views }} views</p>

<!-- Show rating -->
<p>Rating: {{ $product->pro_prating }}/5</p>

<!-- Show if hot -->
@if($product->is_hot)
    <span class="badge badge-danger">HOT</span>
@endif
```

---

## 🟡 FIX #5: Remove Unused Fields from User Model

### Option 1: Remove from Model

**File:** `app/Models/User.php`

```php
// BEFORE:
protected $fillable = [
    'name',
    'email',
    'password',
    'slug',
    'type',
    'avatar',
    'phone',
    'address',
    'gender',
    'birthday',
    'is_active',
    'auth_provider',
    'auth_provider_id'
];

protected $casts = [
    'email_verified_at' => 'datetime',
    'password' => 'hashed',
    'gender' => 'boolean',
    'birthday' => 'date',
    'is_active' => 'boolean'
];

// AFTER:
protected $fillable = [
    'name',
    'email',
    'password',
    'type',
    'is_active'
];

protected $casts = [
    'email_verified_at' => 'datetime',
    'password' => 'hashed',
    'is_active' => 'boolean'
];
```

### Create Migration to Drop Columns
**File:** `database/migrations/2024_01_01_000002_drop_unused_columns_from_users_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'slug',
                'avatar',
                'phone',
                'address',
                'gender',
                'birthday',
                'auth_provider',
                'auth_provider_id'
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('slug')->nullable();
            $table->string('avatar')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->boolean('gender')->nullable();
            $table->date('birthday')->nullable();
            $table->string('auth_provider')->nullable();
            $table->string('auth_provider_id')->nullable();
        });
    }
};
```

### Option 2: Use the Fields (If needed)

**Create UserProfile Model:**
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'avatar',
        'phone',
        'address',
        'gender',
        'birthday'
    ];

    protected $casts = [
        'gender' => 'boolean',
        'birthday' => 'date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
```

**Update User Model:**
```php
public function profile()
{
    return $this->hasOne(UserProfile::class);
}
```

---

## 🟡 FIX #6: Remove Unused Fields from Category Model

**File:** `app/Models/Category.php`

```php
// BEFORE:
protected $fillable = [
    'cate_name',
    'cate_image',
    'slug',
    'is_active'
];

// AFTER:
protected $fillable = [
    'cate_name',
    'is_active'
];
```

**Create Migration:**
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['cate_image', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('cate_image')->nullable();
            $table->string('slug')->nullable();
        });
    }
};
```

---

## 🟡 FIX #7: Remove Unused Fields from Color/Capacity Models

**File:** `app/Models/Color.php`

```php
// BEFORE:
protected $fillable = [
    'color_name',
    'slug',
    'is_active'
];

// AFTER:
protected $fillable = [
    'color_name',
    'is_active'
];
```

**File:** `app/Models/Capacity.php`

```php
// BEFORE:
protected $fillable = [
    'cap_name',
    'slug',
    'is_active'
];

// AFTER:
protected $fillable = [
    'cap_name',
    'is_active'
];
```

**Create Migration:**
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('colors', function (Blueprint $table) {
            $table->dropColumn('slug');
        });

        Schema::table('capacities', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }

    public function down(): void
    {
        Schema::table('colors', function (Blueprint $table) {
            $table->string('slug')->nullable();
        });

        Schema::table('capacities', function (Blueprint $table) {
            $table->string('slug')->nullable();
        });
    }
};
```

---

## 🟡 FIX #8: Remove Unused Fields from ProductVariant Model

**File:** `app/Models/ProductVariant.php`

```php
// BEFORE:
protected $fillable = [
    'product_id',
    'color_id',
    'capacity_id',
    'quantity',
    'price',
    'price_sale'
];

protected $casts = [
    'quantity' => 'integer',
    'price' => 'integer',
    'price_sale' => 'integer'
];

// AFTER:
protected $fillable = [
    'product_id',
    'color_id',
    'capacity_id',
    'quantity'
];

protected $casts = [
    'quantity' => 'integer'
];
```

**Create Migration:**
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropColumn(['price', 'price_sale']);
        });
    }

    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->integer('price')->nullable();
            $table->integer('price_sale')->nullable();
        });
    }
};
```

---

## ✅ TESTING AFTER FIXES

### Unit Test Example
```php
<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\ProductVariant;
use Tests\TestCase;

class ProductVariantTest extends TestCase
{
    public function test_product_has_many_variants()
    {
        $product = Product::factory()->create();
        $variants = ProductVariant::factory(3)->create([
            'product_id' => $product->id
        ]);

        $this->assertCount(3, $product->productVariants);
        $this->assertTrue($product->productVariants->contains($variants[0]));
    }

    public function test_cart_model_exists()
    {
        $this->assertTrue(class_exists(\App\Models\Cart::class));
    }

    public function test_cart_item_has_product_relationship()
    {
        $cartItem = \App\Models\CartItem::factory()->create();
        $this->assertNotNull($cartItem->product);
    }
}
```

### Feature Test Example
```php
<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    public function test_product_edit_page_loads()
    {
        $user = User::factory()->create(['type' => 'admin']);
        $product = Product::factory()->create();

        $response = $this->actingAs($user)
                        ->get(route('products.edit', $product));

        $response->assertStatus(200);
        $response->assertViewHas('data');
    }
}
```

---

## 📋 MIGRATION EXECUTION ORDER

```bash
# 1. Create Cart model and migration
php artisan make:model Cart -m

# 2. Run all migrations
php artisan migrate

# 3. Run tests
php artisan test

# 4. Clear cache
php artisan cache:clear
php artisan config:cache
```

---

**Document created by:** Context Gathering Agent
**Date:** 2024
**Version:** 1.0
