@extends('admin.layouts.master')
@section('title')
    Create Coupon
@endsection
@section('content')
    <div class="container-fluid">

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <span style="font-size: 25px" class="m-0 font-weight-bold text-primary">Create Coupon</span>

            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $item)
                                <li>{{ $item }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (session()->has('error'))
                    <div class="alert alert-danger">
                        {{ session()->get('error') }}
                    </div>
                @endif
                <div class="table-responsive">
                    <form action="{{ route('coupons.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf



                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header align-items-center d-flex">
                                        <h4 class="card-title mb-0 flex-grow-1">Form Coupon</h4>
                                        <a class="btn btn-primary" style="float: right"
                            href="{{ route('coupons.index') }}" role="button">Quay lại</a>
                                    </div><!-- end card header -->
                                    <div class="card-body">



                                        <div class="form-group">
                                            <label for="coupon_code">Coupon code</label>
                                            <input type="text" value="{{ old('coupon_code') }}" name="coupon_code"
                                                id="coupon_code" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="discount_type">Discount type</label>
                                            <br>
                                            <span>Tỉ lệ % :</span>
                                            <input type="radio" value="0" checked name="discount_type"
                                                id="discount_type" class="form-checkbox">

                                            <span class="ml-3">Tỉ lệ giá :</span>
                                            <input type="radio" value="1" name="discount_type" id="discount_type"
                                                class="form-checkbox">
                                        </div>
                                        <div class="form-group">
                                            <label for="discount_value">Discount Value</label>
                                            <input type="text" value="{{ old('discount_value') }}" name="discount_value"
                                                id="discount_value" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="start_date">Start Date</label>
                                            <input type="datetime-local" value="{{ old('start_date') }}" name="start_date"
                                                id="start_date" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="end_date">End Date</label>
                                            <input type="datetime-local" value="{{ old('end_date') }}" name="end_date"
                                                id="end_date" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="coupon_limit">Coupon Limit</label>
                                            <input type="number" value="{{ old('coupon_limit') }}" name="coupon_limit"
                                                id="coupon_limit" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="coupon_description">Coupon Description</label>
                                            <textarea name="coupon_description" id="coupon_description" class="form-control">{{ old('coupon_description') }}</textarea>
                                        </div>

                                    </div>

                                </div>
                            </div>
                            <!--end col-->
                        </div>



                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header align-items-center d-flex">
                                        <h4 class="card-title mb-0 flex-grow-1">Products Coupon</h4>
                                    </div><!-- end card header -->
                                    <div class="card-body">

                                        <div class="form-group">
                                            <select name="product_id[]" class="form-control" id="product_id" multiple>
                                                @foreach ($products as $id => $item)
                                                    <option value="{{ $id }}">{{ $item }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                    </div>

                                </div>
                            </div>
                            <!--end col-->
                        </div>
                        <button type="submit" class="btn btn-primary w-100 my-5">Thêm mới</button>
                    </form>

                </div>
            </div>
        </div>

    </div>
@endsection
