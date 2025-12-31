<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // // Doanh thu hàng tháng
        // $startMonth = now()->startOfMonth()->toDateString();
        // $endMonth = now()->endOfMonth()->toDateString();
        // $total_price_month = DB::table('orders')
        //     ->select(DB::raw("SUM(order_total_price) as total"))
        //     ->where('status_payment', STATUS_PAYMENT_PAID)
        //     ->whereBetween('created_at', [$startMonth, $endMonth])
        //     ->first();

        // // Doanh thu hàng năm
        // $startYear = now()->startOfYear()->toDateString();
        // $endYear = now()->endOfYear()->toDateString();
        // $total_price_year = DB::table('orders')
        //     ->select(DB::raw("SUM(order_total_price) as total"))
        //     ->where('status_payment', STATUS_PAYMENT_PAID)
        //     ->whereBetween('created_at', [$startYear, $endYear])
        //     ->first();

        // // Trung bình mỗi đơn hàng 
        // $avg_total = DB::table('orders')
        //     ->select(DB::raw("AVG(order_total_price) as total"))
        //     ->groupBy('status_payment')
        //     ->having('status_payment', STATUS_PAYMENT_PAID)
        //     ->first();

        // // Hoàn tất 
        // $count_success = DB::table('orders')
        //     ->select(DB::raw("COUNT(status_order) as count"))
        //     ->groupBy('status_order')
        //     ->having('status_order', STATUS_ORDER_DELIVERED)
        //     ->first();
        // //Đang sử lý
        // $count_warning = DB::table('orders')
        //     ->select(DB::raw("COUNT(status_order) as count"))
        //     ->where([
        //         ['status_order', '<>', STATUS_ORDER_DELIVERED],
        //         ['status_order', '<>', STATUS_ORDER_CANCELED]
        //     ])->first();
        // //Đã hủy
        // $count_canceled = DB::table('orders')
        //     ->select(DB::raw("COUNT(status_order) as count"))
        //     ->groupBy('status_order')
        //     ->having('status_order', STATUS_ORDER_CANCELED)
        //     ->first();
        // // Tính tổng số lượng đơn hàng thanh toán khi nhận hàng
        // $payment_deliver = DB::table('orders')
        //     ->select(DB::raw("SUM(order_total_price) as total"))
        //     ->where('status_payment', '=', STATUS_PAYMENT_PAID)
        //     ->where('method_payment', '=', METHOD_PAYMENT_DELIVERY)
        //     ->groupBy('method_payment')
        //     ->first();

        // // Tính tổng số lượng đơn hàng thanh toán VNpay
        // $payment_vnpay = DB::table('orders')
        //     ->select(DB::raw("SUM(order_total_price) as total"))
        //     ->where('status_payment', STATUS_PAYMENT_PAID)
        //     ->where('method_payment', METHOD_PAYMENT_VNPAY)
        //     ->groupBy('method_payment')
        //     ->first();
        // //

        // //Tính doanh thu của từng tháng
        // $totalTwMonth = [];
        // for ($i = 0; $i < 12; $i++) {
        //     $stMonth = now()->startOfMonthParam($i + 1)->toDateString();
        //     $edMonth = now()->endOfMonthParams($i + 1)->toDateString();
        //     $data = DB::table('orders')
        //         ->select(DB::raw("SUM(order_total_price) as total"))
        //         ->whereBetween('created_at', [$stMonth, $edMonth])
        //         ->where('status_payment', STATUS_PAYMENT_PAID)
        //         ->first();
        //     $dataTotal = $data->total;
        //     $dataTotal ??= 0;
        //     $totalTwMonth[] = $dataTotal;
        // }
        // //Số lượng mỗi sản phẩm

        // // $quantityProCate = DB::table('products as p')
        // //     ->select(DB::raw("COUNT(category_id) as countCate"), 'c.cate_name')
        // //     ->join('categories as c', 'c.id', '=', 'p.category_id')
        // //     ->where('p.deleted_at', null)
        // //     ->groupBy('category_id')
        // //     ->get();
        // $quantityPro = DB::table('product_variants as pv')
        //     ->select(DB::raw("SUM(quantity) as quantityproduct"), 'p.pro_name')
        //     ->join('products as p', 'p.id', '=', 'pv.product_id')
        //     ->groupBy('product_id')
        //     ->get();
        // //Top 5 sản phẩm bán chạy
        // $top5product = DB::table('order_items as oi')
        //     ->select(DB::raw("SUM(oi.order_item_quantity) as quantity"), 'p.pro_name', 'p.id', 'p.pro_price_regular')
        //     ->join('product_variants as pv', 'pv.id', '=', 'oi.product_variant_id')
        //     ->join('products as p', 'p.id', '=', 'pv.product_id')
        //     ->join('orders as o', 'o.id', '=', 'oi.order_id')
        //     ->where('o.status_payment', STATUS_PAYMENT_PAID)
        //     ->groupBy('p.id')
        //     ->orderByDesc('quantity')
        //     ->limit(5)
        //     ->get();
        // //Doanh thu các ngày trong tháng

        // $start = now()->startOfMonth();
        // $late = now()->endOfMonth();
        // // Tính số lương ngày trong tháng 
        // $daysInMonth = $start->diffInDays($late) + 1;
        // $total_day = [];
        // for ($i = 0; $i < $daysInMonth; $i++) {
        //     $day = now()->startOfMonthDay($i + 1)->toDateString();
        //     $orders_day = DB::table('orders')
        //         ->select(DB::raw("SUM(order_total_price) as total_price"))
        //         ->where('status_payment', STATUS_PAYMENT_PAID)
        //         ->whereDate('created_at', $day)
        //         ->first();
        //     $orders_day->total_price ??= 0;
        //     $total_day[] = $orders_day->total_price;
        // }
// compact('total_price_month', 'total_price_year', 'avg_total', 'count_success', 'count_warning', 'count_canceled', 'payment_deliver', 'payment_vnpay', 'totalTwMonth', 'quantityPro', 'top5product', 'total_day')
        return view('admin.index',[] );
    }
}
