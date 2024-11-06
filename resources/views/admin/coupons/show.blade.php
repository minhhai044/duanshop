@extends('admin.layouts.master')
@section('title')
    Coupons
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
                    <h2 class="mb-3">Thông tin mã giảm giá<a class="btn btn-primary" style="float: right"
                            href="{{ route('coupons.index') }}" role="button">Quay lại</a></h2>

                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>ID</th>
                                <th>Code</th>
                                <th>Discount</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Limit</th>
                                <th>Used</th>
                                <th>Status</th>
                                <th>Description</th>

                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $data->id }}</td>
                                <td>{{ $data->coupon_code }}</td>
                                <td>{{ $data->discount_value }}</td>
                                <td>{{ $data->start_date }}</td>
                                <td>{{ $data->end_date }}</td>
                                <td>{{ $data->coupon_limit }}</td>
                                <td>{{ $data->coupon_used }}</td>
                                <td>
                                    @if ($data->coupon_status)
                                        <button disabled class="btn btn-success">Valid</button>
                                    @else
                                        <button disabled class="btn btn-danger">Expired</button>
                                    @endif
                                </td>
                                <td>{{ $data->coupon_description }}</td>
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
                                <th>Price Regular</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data->products as $value)
                                <tr>
                                    <td>{{ $value->id }}</td>
                                    <td>{{ $value->pro_sku }}</td>
                                    <td>{{ $value->pro_name }}</td>
                                    <td>
                                        @if ($value->pro_img_thumbnail)
                                            <img src="{{ Storage::url($value->pro_img_thumbnail) }}" width="100px"
                                                height="100px" class="img-thumbnail" alt="">
                                        @endif
                                    </td>
                                    <td>{{ number_format($value->pro_price_regular) }} đ</td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection
