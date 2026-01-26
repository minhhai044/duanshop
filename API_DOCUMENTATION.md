# DuanShop API Documentation

## Tổng quan
API Documentation cho ứng dụng E-commerce DuanShop được tạo bằng Scribe.

## Truy cập Documentation

### 1. Web Interface
- **URL**: `https://duanshop.iongeyser.com/docs`
- Giao diện web đầy đủ với khả năng test API trực tiếp

### 2. OpenAPI Specification
- **URL**: `https://duanshop.iongeyser.com/docs.openapi`
- File YAML có thể import vào Swagger, Postman, Insomnia

### 3. Postman Collection
- **URL**: `https://duanshop.iongeyser.com/docs.postman`
- Collection JSON có thể import trực tiếp vào Postman

## Các nhóm API chính

### 1. Authentication (`/api/auths`)
- `POST /api/auths/login` - Đăng nhập
- `POST /api/auths/register` - Hoàn tất đăng ký
- `POST /api/auths/verify-otp` - Gửi OTP đăng ký
- `POST /api/auths/forgot-password` - Quên mật khẩu
- `POST /api/auths/reset-password` - Đặt lại mật khẩu
- `POST /api/auths/resend-otp` - Gửi lại OTP
- `POST /api/auths/logout` - Đăng xuất (yêu cầu auth)

### 2. Categories (`/api/categories`)
- `GET /api/categories` - Danh sách danh mục
- `GET /api/categories/{slug}` - Chi tiết danh mục

### 3. Products (`/api/products`)
- `GET /api/products` - Danh sách sản phẩm (có phân trang)
- `GET /api/products/search` - Tìm kiếm sản phẩm
- `GET /api/products/{slug}` - Chi tiết sản phẩm

## Xác thực (Authentication)

API sử dụng Laravel Sanctum với Bearer Token:

1. **Đăng nhập**: Gọi `POST /api/auths/login` để lấy token
2. **Sử dụng token**: Thêm header `Authorization: Bearer {token}` cho các API yêu cầu xác thực

## Cập nhật Documentation

Khi thay đổi API, chạy lệnh sau để cập nhật documentation:

```bash
/usr/local/bin/ea-php84 artisan scribe:generate
```

## Cấu hình Scribe

File cấu hình: `config/scribe.php`

### Các tính năng đã bật:
- ✅ Try It Out - Test API trực tiếp từ docs
- ✅ Authentication support
- ✅ Postman collection generation
- ✅ OpenAPI specification
- ✅ Tiếng Việt support

### Thêm annotation cho API mới:

```php
/**
 * @group Group Name
 * 
 * Description of the group
 */
class YourController extends Controller
{
    /**
     * Endpoint Title
     * 
     * Detailed description
     * 
     * @bodyParam field_name type required Description. Example: value
     * @queryParam param_name type Description. Example: value
     * @urlParam param_name type required Description. Example: value
     * @authenticated (nếu cần auth)
     * 
     * @response 200 {
     *   "status": true,
     *   "message": "Success message",
     *   "data": {}
     * }
     */
    public function method() {}
}
```

## Lưu ý quan trọng

1. **PHP Version**: Sử dụng PHP 8.4 (`/usr/local/bin/ea-php84`)
2. **CORS**: Đảm bảo CORS được cấu hình đúng cho Try It Out
3. **Environment**: Documentation được tạo dựa trên `APP_URL` trong `.env`
4. **Cache**: Scribe cache kết quả, chạy lại `scribe:generate` khi có thay đổi

## Troubleshooting

### Lỗi thường gặp:
1. **PHP Version mismatch**: Sử dụng đúng PHP 8.4
2. **Route không xuất hiện**: Kiểm tra `routes` config trong `scribe.php`
3. **Authentication không hoạt động**: Kiểm tra `auth` config và CORS headers

### Debug:
```bash
# Kiểm tra routes
/usr/local/bin/ea-php84 artisan route:list

# Xem log
tail -f storage/logs/laravel.log
```