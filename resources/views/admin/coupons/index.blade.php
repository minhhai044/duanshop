@extends('admin.layouts.master')
@section('title')
    Coupons
@endsection
@section('content')
    <div class="container-fluid">
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <span style="font-size: 25px" class="m-0 font-weight-bold text-primary">List Coupons</span>
                <a class="btn btn-primary" style="float: right" href="{{ route('coupons.create') }}" role="button">Thêm
                    mới</a>

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
                                <th>Code</th>
                                <th>Discount</th>

                                <th> Limit</th>
                                <th> Used</th>
                                <th> Status</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($Coupons as $item)
                                <tr class="text-center">
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->coupon_code }}</td>
                                    <td>{{ $item->discount_value }} {{ $item->discount_type ? 'VND' : '%' }}</td>

                                    <td>{{ $item->coupon_limit }}</td>
                                    <td>{{ $item->coupon_used }}</td>
                                    <td>
                                        @if ($item->coupon_status)
                                            <button disabled class="btn btn-success">Valid</button>
                                        @else
                                            <button disabled class="btn btn-danger">Expired</button>
                                        @endif
                                    </td>
                                    <td> {{ $item->start_date }} </td>
                                    <td> {{ $item->end_date }} </td>

                                    <td style="display: flex">
                                        <a class="btn btn-warning mr-2" href="{{ route('coupons.show', $item) }}"
                                            role="button">Show</a>
                                        <a class="btn btn-dark mr-2" href="{{ route('coupons.edit', $item) }}"
                                            role="button">Edit</a>

                                        <form action="{{ route('coupons.destroy', $item) }}" method="post"
                                            enctype="multipart/form-data">
                                            @csrf
                                            @method('DELETE')
                                            <button onclick="return confirm('Bạn có chắc chắn xóa không !!!')"
                                                type="submit" class="btn btn-danger">Delete</button>
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
