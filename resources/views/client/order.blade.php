@extends('client.layouts.master')
@section('document')
    Furni Free Bootstrap 5 Template for Furniture and Interior Design Websites by Untree.co
@endsection
@section('content')
    <div class="untree_co-section before-footer-section">
        <div class="container">
            {{-- @dd($listOrders->toArray()) --}}
            @if (!empty($listOrders))
                <div class="row mb-5">
                    @if (session()->has('success'))
                        <div class="alert alert-success">
                            {{ session()->get('success') }}
                        </div>
                    @endif

                    @if (session()->has('error'))
                        <div class="alert alert-danger">
                            {{ session()->get('error') }}
                        </div>
                    @endif

                    <table class="table mb-5">
                        <thead>
                            <tr>
                                <th>id</th>
                                <th>Name</th>
                                <th>Status Orders</th>
                                <th>Method Payment</th>
                                <th>Status Payment</th>
                                <th>Total</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($listOrders as $item)
                                <tr>
                                    <td>
                                        {{ $item->id }}
                                    </td>
                                    <td>
                                        {{ $item->user->name }}
                                    </td>
                                    <td>
                                        {{ statusOrders($item->status_order) }}
                                    </td>
                                    <td>
                                        {{ methodPayment($item->method_payment) }}
                                    </td>
                                    <td>
                                        {{ statusPayment($item->status_payment) }}
                                    </td>
                                    <td>
                                        {{ number_format($item->order_total_price) }} đ
                                    </td>
                                    <td>
                                        {{ $item->created_at->format('d/m/Y H:i:s') }}
                                    </td>
                                    <td class="d-flex gap-2">
                                        <a href="{{ route('show.orders', $item) }}">
                                            <button class="btn btn-primary">Show</button>
                                        </a>

                                        <form action="{{ route('orders.cancel', $item) }}" method="post">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status_order" value="{{ $item->status_order }}">
                                            <button onclick="return confirm('Bạn có chắc chắn hủy đơn hàng này không !!!')"
                                                @disabled($item->status_order !== STATUS_ORDER_PENDING) class="btn btn-danger"
                                                type="submit">Cancel</button>
                                        </form>

                                    </td>
                                <tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $listOrders->links() }}


                </div>
            @else
                <div class="text-center">
                    <img src="{{ '/client/images/Cart-empty-v2.webp' }}" width="300px" alt="">
                    <p class="mt-3">Đơn hàng của bạn đang trống.</p>
                    <p> Hãy chọn thêm sản phẩm để mua sắm nhé</p>

                    <a href="{{ route('shop') }}"><button class="btn btn-success">Continue
                            Shopping</button></a>

                </div>
            @endif

        </div>
    </div>
@endsection
