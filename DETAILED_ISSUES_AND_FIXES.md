# CHI TIẾT CÁC ISSUES VÀ CÁCH SỬA

## 🔴 CRITICAL ISSUE #1: Product::productVariant() - Sai Relationship Type

### Vấn đề
- **Hiện tại:** `hasOne(ProductVariant::class)` - Một sản phẩm chỉ có một biến thể
- **Thực tế:** Một sản phẩm có nhiều biến thể (nhiều màu, nhiều dung lượng)
- **Impact:** Không thể lấy tất cả biến thể của sản phẩm

### Ví dụ
```php
// Hiện tại (SAI):
$product = Product::find(1);
$variant = $product->productVariant; // Chỉ lấy 1 biến thể

// Nên là (ĐÚNG):
$product = Product::find(1);
$variants = $product->productVariants; // Lấy tất cả biến thể
```

### Cách sửa

**File:** `app/Models/Product.php`

```php
// Dòng 48-50 - Thay đổi từ:
public function productVariant(){
    return $this->hasOne(ProductVariant::class);
}

// Thành:
public function productVariants(){
    return $this->hasMany(ProductVariant::class);
}
```

### Files cần cập nhật

1. **app/Http/Controllers/Admin/ProductController.php**
   - Dòng 95: `$product->product_variant()->create($item);` → `$product->productVariants()->create($item);`
   - Dòng 140: `$data->product_variant` → `$data->productVariants`

2. **resources/views/admin/products/edit.blade.php**
   - Tìm và thay tất cả `$data->product_variant` → `$data->productVariants`

---

## 🔴 CRITICAL ISSUE #2: Cart Model - MISSING

### Vấn đề
- Controllers sử dụng `Cart` model nhưng không tìm thấy trong `app/Models/`
- Có thể đã bị xóa hoặc chưa được tạo

### Ví dụ sử dụng
```php
// app/Http/Controllers/Client/CartController.php - Dòng 32
$cart = Cart::query()->firstOrCreate(['user_id' => $user_id]);

// app/Http/Controllers/Client/OrderController.php - Dòng 60
$cart = Cart::query()->where('user_id', $user->id)->first();
```

### Cách sửa

**Tạo file:** `app/Models/Cart.php`

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

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function cartItems(){
        return $this->hasMany(CartItem::class);
    }
}
```

**Tạo migration:** `database/migrations/YYYY_MM_DD_HHMMSS_create_carts_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
```

**Chạy migration:**
```bash
php artisan migrate
```

---

## 🟡 MEDIUM ISSUE #1: CartItem Model - Thiếu Relationship

### Vấn đề
- CartItem không có direct relationship tới Product
- Phải đi qua ProductVariant để lấy Product

### Cách sửa

**File:** `app/Models/CartItem.php`

```php
// Thêm vào class CartItem:
public function product(){
    return $this->productVariant->product;
}
```

### Ví dụ sử dụng
```php
// Trước (phải đi qua ProductVariant):
$product = $cartItem->productVariant->product;

// Sau (trực tiếp):
$product = $cartItem->product;
```

---

## 🟡 MEDIUM ISSUE #2: Unused Fields trong Product Model

### Vấn đề
- Các fields sau không được sử dụng trong views/controllers:
  - `pro_slug`
  - `pro_views`
  - `pro_prating`
  - `is_hot`

### Khuyến nghị

**Option 1: Xóa fields (nếu không cần)**

```php
// app/Models/Product.php - Xóa từ $fillable:
protected $fillable = [
    'category_id',
    'pro_name',
    'pro_sku',
    // 'pro_slug',  // XÓA
    'pro_description',
    'pro_img_thumbnail',
    'pro_price_regular',
    'pro_price_sale',
    // 'pro_views',  // XÓA
    'pro_featured',
    // 'pro_prating',  // XÓA
    // 'is_hot',  // XÓA
    'is_active'
];

// Xóa từ $casts:
protected $casts = [
    'pro_price_regular' => 'decimal:0',
    'pro_price_sale' => 'decimal:0',
    // 'pro_views' => 'integer',  // XÓA
    'pro_featured' => 'boolean',
    // 'pro_prating' => 'decimal:1',  // XÓA
    // 'is_hot' => 'boolean',  // XÓA
    'is_active' => 'boolean'
];
```

**Option 2: Sử dụng fields (nếu cần)**

```php
// Thêm vào views để hiển thị:
- pro_slug: Sử dụng cho URL friendly (route model binding)
- pro_views: Hiển thị số lượt xem sản phẩm
- pro_prating: Hiển thị rating sản phẩm
- is_hot: Đánh dấu sản phẩm hot/trending

// Thêm vào controllers để cập nhật:
- pro_views: Tăng khi xem chi tiết sản phẩm
- pro_prating: Cập nhật khi có review/rating
- is_hot: Cập nhật thủ công hoặc tự động
```

---

## 🟡 MEDIUM ISSUE #3: Unused Fields trong User Model

### Vấn đề
- Các fields sau không được sử dụng:
  - `slug`
  - `avatar`
  - `phone`
  - `address`
  - `gender`
  - `birthday`
  - `auth_provider`
  - `auth_provider_id`

### Khuyến nghị

**Option 1: Xóa fields**

```php
// app/Models/User.php - Xóa từ $fillable:
protected $fillable = [
    'name',
    'email',
    'password',
    // 'slug',  // XÓA
    'type',
    // 'avatar',  // XÓA
    // 'phone',  // XÓA
    // 'address',  // XÓA
    // 'gender',  // XÓA
    // 'birthday',  // XÓA
    'is_active',
    // 'auth_provider',  // XÓA
    // 'auth_provider_id'  // XÓA
];

// Xóa từ $casts:
protected $casts = [
    'email_verified_at' => 'datetime',
    'password' => 'hashed',
    // 'gender' => 'boolean',  // XÓA
    // 'birthday' => 'date',  // XÓA
    'is_active' => 'boolean'
];
```

**Option 2: Sử dụng fields**

```php
// Tạo profile page để hiển thị/chỉnh sửa:
- avatar: Hình đại diện người dùng
- phone: Số điện thoại
- address: Địa chỉ mặc định
- gender: Giới tính
- birthday: Ngày sinh
- slug: URL friendly profile

// Sử dụng OAuth:
- auth_provider: Nhà cung cấp (google, facebook, github)
- auth_provider_id: ID từ nhà cung cấp
```

---

## 🟡 MEDIUM ISSUE #4: Unused Fields trong Category Model

### Vấn đề
- `cate_image`: Không được sử dụng
- `slug`: Không được sử dụng

### Khuyến nghị

**Option 1: Xóa fields**

```php
// app/Models/Category.php - Xóa từ $fillable:
protected $fillable = [
    'cate_name',
    // 'cate_image',  // XÓA
    // 'slug',  // XÓA
    'is_active'
];
```

**Option 2: Sử dụng fields**

```php
// Thêm vào views:
- cate_image: Hiển thị ảnh danh mục
- slug: Sử dụng cho URL friendly

// Thêm vào controllers:
- Xử lý upload ảnh danh mục
- Tạo URL friendly slug
```

---

## 🟡 MEDIUM ISSUE #5: Unused Fields trong Color/Capacity Model

### Vấn đề
- `slug` không được sử dụng trong cả hai model

### Khuyến nghị

**Option 1: Xóa fields**

```php
// app/Models/Color.php
protected $fillable = [
    'color_name',
    // 'slug',  // XÓA
    'is_active'
];

// app/Models/Capacity.php
protected $fillable = [
    'cap_name',
    // 'slug',  // XÓA
    'is_active'
];
```

**Option 2: Sử dụng fields**

```php
// Sử dụng slug cho URL friendly
// Ví dụ: /products?color=red, /products?capacity=64gb
```

---

## 🟡 MEDIUM ISSUE #6: Unused Fields trong ProductVariant Model

### Vấn đề
- `price`: Không được sử dụng (sử dụng pro_price_regular từ Product)
- `price_sale`: Không được sử dụng (sử dụng pro_price_sale từ Product)

### Khuyến nghị

**Option 1: Xóa fields**

```php
// app/Models/ProductVariant.php - Xóa từ $fillable:
protected $fillable = [
    'product_id',
    'color_id',
    'capacity_id',
    'quantity',
    // 'price',  // XÓA
    // 'price_sale'  // XÓA
];

// Xóa từ $casts:
protected $casts = [
    'quantity' => 'integer',
    // 'price' => 'integer',  // XÓA
    // 'price_sale' => 'integer'  // XÓA
];
```

**Option 2: Sử dụng fields**

```php
// Cho phép override giá theo biến thể
// Ví dụ: iPhone 64GB có giá khác với iPhone 128GB
// Nếu price/price_sale NULL, sử dụng giá từ Product
// Nếu price/price_sale có giá trị, sử dụng giá từ ProductVariant

// Thêm vào ProductVariant model:
public function getPrice(){
    return $this->price ?? $this->product->pro_price_regular;
}

public function getPriceSale(){
    return $this->price_sale ?? $this->product->pro_price_sale;
}
```

---

## 🟢 MINOR ISSUE #1: Missing Controllers

### Vấn đề
- Gallery, ProductVariant, VnpayPayment không có controllers riêng
- Được quản lý trong ProductController, OrderController

### Khuyến nghị

**Giữ nguyên (Recommended):**
- Gallery: Quản lý trong ProductController (tạo/xóa ảnh khi tạo/sửa sản phẩm)
- ProductVariant: Quản lý trong ProductController (tạo/sửa biến thể khi tạo/sửa sản phẩm)
- VnpayPayment: Quản lý trong OrderController (tạo khi thanh toán)

**Hoặc tạo controllers riêng (nếu cần):**

```php
// app/Http/Controllers/Admin/GalleryController.php
// app/Http/Controllers/Admin/ProductVariantController.php
// app/Http/Controllers/Admin/VnpayPaymentController.php
```

---

## 🟢 MINOR ISSUE #2: Missing API Endpoints

### Vấn đề
- Không có API endpoints cho Product, Category, Color, Capacity
- Chỉ có API cho Auth, User, SearchProduct

### Khuyến nghị

**Tạo API Controllers (nếu cần):**

```php
// app/Http/Controllers/Api/ProductController.php
// app/Http/Controllers/Api/CategoryController.php
// app/Http/Controllers/Api/ColorController.php
// app/Http/Controllers/Api/CapacityController.php
```

**Hoặc sử dụng SearchProductController:**

```php
// Mở rộng SearchProductController để hỗ trợ tất cả endpoints
```

---

## 📋 DANH SÁCH KIỂM TRA (CHECKLIST)

### Sửa Critical Issues
- [ ] Sửa Product::productVariant() → productVariants()
- [ ] Cập nhật ProductController
- [ ] Cập nhật views
- [ ] Tạo Cart model
- [ ] Tạo Cart migration
- [ ] Chạy migration

### Sửa Medium Issues
- [ ] Thêm CartItem::product() relationship
- [ ] Xóa hoặc sử dụng unused fields trong Product
- [ ] Xóa hoặc sử dụng unused fields trong User
- [ ] Xóa hoặc sử dụng unused fields trong Category
- [ ] Xóa hoặc sử dụng unused fields trong Color/Capacity
- [ ] Xóa hoặc sử dụng unused fields trong ProductVariant

### Kiểm tra Minor Issues
- [ ] Xem xét tạo controllers riêng cho Gallery, ProductVariant, VnpayPayment
- [ ] Xem xét tạo API endpoints cho Product, Category, Color, Capacity

### Testing
- [ ] Unit tests cho models
- [ ] Integration tests cho controllers
- [ ] Functional tests cho views
- [ ] API tests

---

**Tài liệu được tạo bởi:** Context Gathering Agent
**Ngày tạo:** 2024
**Phiên bản:** 1.0
