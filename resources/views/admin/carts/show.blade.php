@extends('admin.layouts.master')
@section('title')
    Category
@endsection
@section('content')
    <div class="container-fluid">

        <!-- DataTales Example -->
        <div class="card shadow-lg">
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

                <!-- Customer Info Section -->
                <div class="mb-5">
                    <h2 class="mb-3">Thông tin khách hàng<a class="btn btn-primary" style="float: right"
                            href="{{ route('dashboard.cart') }}" role="button">Quay lại</a></h2>

                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Address</th>
                                <th>Note</th>
                                <th>Updated At</th>

                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $data->id }}</td>
                                <td>{{ $data->order_user_name }}</td>
                                <td>{{ $data->order_user_email }}</td>
                                <td>{{ $data->order_user_phone }}</td>
                                <td>{{ $data->order_user_address }}</td>
                                <td>{{ $data->order_user_note }}</td>
                                <td>{{ $data->updated_at->format('d/m/Y h:i:s') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Product List Section -->
                <div class="my-5">
                    <h2 class="mb-3">Danh sách sản phẩm</h2>
                    <table class="table table-striped table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>ID</th>
                                <th>Sku</th>
                                <th>Name</th>
                                <th>Image</th>
                                <th>Quantity</th>
                                <th>Price Regular</th>
                                <th>Capacity</th>
                                <th>Color</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data->orderItems as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->product_sku }}</td>
                                    <td>{{ $item->product_name }}</td>
                                    <td>
                                        @if ($item->product_img_thumbnail)
                                            <img src="{{ Storage::url($item->product_img_thumbnail) }}" width="100px"
                                                height="100px" class="img-thumbnail" alt="">
                                        @endif
                                    </td>
                                    <td>{{ $item->order_item_quantity }}</td>
                                    <td>{{ number_format($item->pro_price_regular) }} đ</td>
                                    <td>{{ $item->variant_capacity_name }}</td>
                                    <td>{{ $item->variant_color_name }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection
