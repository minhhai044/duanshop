<?php

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


if (! function_exists('statusOrders')) {
    function statusOrders($value)
    {
        switch ($value) {
            case STATUS_ORDER_PENDING:
                return "Chờ xác nhận";
            case STATUS_ORDER_CONFIRMED:
                return "Đã xác nhận";
            case STATUS_ORDER_PREPARING_GOODS:
                return "Đang chuẩn bị hàng";
            case STATUS_ORDER_SHIPPING:
                return "Đang vận chuyển";
            case STATUS_ORDER_DELIVERED:
                return "Đã giao hàng";
            case STATUS_ORDER_CANCELED:
                return "Đơn hàng đã bị hủy";
        }
    }
}
if (! function_exists('methodPayment')) {

    function methodPayment($value)
    {
        switch ($value) {
            case METHOD_PAYMENT_VNPAY:
                return "Thanh toán VNPay";
            case METHOD_PAYMENT_DELIVERY:
                return "Thanh toán khi nhận hàng";
        }
    }
}


if (! function_exists('statusPayment')) {

    function statusPayment($value)
    {
        switch ($value) {
            case STATUS_PAYMENT_PAID:
                return "Đã thanh toán";

            case STATUS_PAYMENT_UNPAID:
                return "Chưa thanh toán";
        }
    }
}
define('STATUS_ORDER_PENDING', 'pending');
define('STATUS_ORDER_CONFIRMED', 'confirmed');
define('STATUS_ORDER_PREPARING_GOODS', 'preparing_goods');
define('STATUS_ORDER_SHIPPING', 'shipping');
define('STATUS_ORDER_DELIVERED', 'delivered');
define('STATUS_ORDER_CANCELED', 'canceled');

define('METHOD_PAYMENT_DELIVERY', 'cash_delivery');
define('METHOD_PAYMENT_VNPAY', 'vnpay_payment');

define('STATUS_PAYMENT_UNPAID', 'unpaid');
define('STATUS_PAYMENT_PAID', 'paid');






/**
 * Giới hạn hiển thị text
 * 
 * @param string $text  Đoạn text muốn giới hạn
 * @param int    $limit Giới hạn từ muốn hiển thị
 * @param string $end   Chuỗi string sẽ nối vào cuối cùng của chuỗi sau khi giới hạn
 * 
 * @return string Trả về string sau khi đã giới hạn
 */
if (!function_exists('limitText')) {
    function limitText($text, $limit, $end = "...")
    {
        return Str::length($text) > $limit ? Str::limit($text, $limit, $end) : $text;
    }
}

/**
 * Tạo chuỗi id ngẫu nhiên 
 * @return mixed Trả về chuỗi id ngẫu nhiên có định dạng xxx-xxxx-xxx-xxx
 */
if (!function_exists('generateSlugIds')) {
    function generateSlugIds()
    {
        $characters = 'abcdefghjklmnpqrstuvwxyz123456789';
        $charLength = strlen($characters);

        $code = '';

        for ($i = 0; $i < 26; $i++) {
            $code .= $characters[rand(0, $charLength - 1)];
        }

        $pattern = [3, 4, 3, 3, 3, 4, 3, 3];

        $parts = [];
        $start = 0;
        foreach ($pattern as $len) {
            $parts[] = substr($code, $start, $len);
            $start += $len;
        }

        return implode('-', $parts);
    }
}

/**
 * Tạo chuỗi slug theo name
 * @param string $name Chuỗi name cần tạo slug
 * @return string      Trả về chuỗi slug
 * 
 */

if (!function_exists('generateSlug')) {
    function generateSlug($name)
    {
        return Str::slug($name . '-' . generateSlugIds());
    }
}

/**
 * Tạo thư mục lưu trữ ảnh
 * @param mixed $folder Thư mục lưu trữ trong storage/app/public
 * @param mixed $file   File ảnh cần lưu trữ
 * 
 * @return string       Trả về đường dẫn lưu trữ ảnh
 */


if (!function_exists('createImageStorage')) {
    function createImageStorage($folder, $file)
    {
        return Storage::put($folder, $file);
    }
}


/**
 * Xóa ảnh trong storage
 * 
 * @param mixed $path Đường dẫn ảnh trong storage/app/public
 * 
 * @return bool       Trả về true nếu xóa thành công, ngược lại trả về true
 */

if (!function_exists('deleteImageStorage')) {
    function deleteImageStorage($path)
    {
        if ($path && Storage::exists($path)) {
            Storage::delete($path);
        }
        return true;
    }
}

/**
 * Lấy đường dẫn ảnh trong storage
 * 
 * @param mixed $path Đường dẫn ảnh trong storage/app/public
 * 
 * @return string     Trả về đường dẫn ảnh
 */
if (!function_exists('getImageStorage')) {
    function getImageStorage($path)
    {
        if ($path && Storage::exists($path)) {
            return Storage::url($path);
        }
        return asset('images/no-product-image.png');
    }
}





/**
 * Tạo key name
 * @param string $name  Tên người dùng
 * @return string Trả về name
 */

if (!function_exists('codeName')) {
    function codeName($name)
    {
        // Bỏ khoảng trắng đầu/cuối
        $name = trim($name);

        // Bỏ dấu tiếng Việt ổn định
        $name = Str::ascii($name);

        // về chữ thường
        $name = mb_strtolower($name, 'UTF-8');

        // Mọi ký tự không phải [a-z0-9_] -> chuyển thành "_"
        $name = preg_replace('/[^\w]+/u', '_', $name);

        // Gom nhiều "_" liên tiếp còn một, và bỏ "_" ở đầu/cuối
        $name = preg_replace('/_+/', '_', trim($name, '_'));

        return $name;
    }
}
