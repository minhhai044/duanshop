@extends('admin.layouts.master')
@section('title')
    Order
@endsection
@section('content')
    <div class="container-fluid">

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <span style="font-size: 25px" class="m-0 font-weight-bold text-primary">List Orders</span>

            </div>
            <div class="card-body">
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
                <div class="table-responsive">

                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Total</th>
                                <th>Status Orders</th>
                                <th>Method Payment</th>
                                <th>Status Payment</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($listCart as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->user->name }}</td>
                                    <td>{{ number_format($item->order_total_price) }} đ</td>
                                    <td>{{ statusOrders($item->status_order) }} </td>
                                    <td>{{ methodPayment($item->method_payment) }}</td>
                                    <td>{{ statusPayment($item->status_payment) }}</td>
                                    <td> {{ $item->created_at->format('d/m/Y H:i:s') }} </td>
                                    <td style="width: 300px;display: flex">
                                        <a class="btn btn-warning mr-2" href="{{ route('cart.show', $item) }}"
                                            role="button">Show</a>
                                        <form action="{{ route('cart.update', $item) }}" method="post">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status_order" value="{{ $item->status_order }}">
                                            @if ($item->status_order == STATUS_ORDER_CANCELED || $item->status_order == STATUS_ORDER_DELIVERED)
                                                <button onclick="return confirm('Bạn có chắc chắn cập nhật không !!!')"
                                                    class="btn btn-success mr-2" disabled type="submit">Update Status
                                                    Orders</button>
                                            @else
                                                <button onclick="return confirm('Bạn có chắc chắn cập nhật không !!!')"
                                                    class="btn btn-success mr-2" type="submit">Update Status
                                                    Orders</button>
                                            @endif

                                        </form>


                                        <form action="{{ route('cart.cancel', $item) }}" method="post">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status_order" value="{{ $item->status_order }}">

                                            <button onclick="return confirm('Bạn có chắc chắn hủy không !!!')"
                                            @disabled($item->status_order !== STATUS_ORDER_PENDING || $item->status_order == STATUS_ORDER_CANCELED || $item->status_payment == STATUS_PAYMENT_PAID )
                                                class="btn btn-danger mr-2" type="submit">Cancel Order</button>

                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection
