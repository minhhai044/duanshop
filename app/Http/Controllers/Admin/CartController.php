<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function listcart()
    {
        $listCart = Order::with(['orderItems', 'user'])->latest('id')->get();

        return view('admin.carts.index', compact('listCart'));
    }
    public function showCart(string $id)
    {
        $data = Order::with(['orderItems', 'user'])->findOrFail($id);
        return view('admin.carts.show', compact('data'));
    }
    public function updateCart(Request $request, string $id)
    {
        $data = Order::findOrFail($id);

        $this->updateOrderStatus($data, $request->status_order);

        return redirect()->route('dashboard.cart')->with('success', 'Cập nhật trạng thái đơn hàng thành công !!!');
    }
    private function updateOrderStatus($order, string $status)
    {
        switch ($status) {
            case STATUS_ORDER_PENDING:
                $order->update([
                    "status_order" => STATUS_ORDER_CONFIRMED
                ]);
                break;
            case STATUS_ORDER_CONFIRMED:
                $order->update([
                    "status_order" => STATUS_ORDER_PREPARING_GOODS
                ]);
                break;
            case STATUS_ORDER_PREPARING_GOODS:
                $order->update([
                    "status_order" => STATUS_ORDER_SHIPPING
                ]);
                break;
            case STATUS_ORDER_SHIPPING:
                $order->update([
                    "status_order" => STATUS_ORDER_DELIVERED,
                    "status_payment" => STATUS_PAYMENT_PAID
                ]);
                break;
        }
    }
    public function cancelCart(Request $request, string $id)
    {
        $data = Order::findOrFail($id);
        if ($request->status_order == STATUS_ORDER_PENDING) {
            $data->update([
                "status_order" => STATUS_ORDER_CANCELED
            ]);
            return redirect()->route('dashboard.cart')->with('success', 'Hủy đơn hàng thành công !!!');
        }
        return redirect()->route('dashboard.cart')->with('error', 'Đơn hàng không thể hủy !!!');
    }
}
