@extends('client.layouts.master')
@section('document')
    Furni Free Bootstrap 5 Template for Furniture and Interior Design Websites by Untree.co
@endsection
@section('content')

    <div class="untree_co-section product-section before-footer-section">

        <div class="container">

            <div class="row">
                @if (!empty($products->toArray()))
                    <h3 class="mb-4 container">Kết quả tìm kiếm</h3>
                    @foreach ($products as $item)
                        <div class="col-12 col-md-4 col-lg-3 mb-5">
                            <a class="product-item" href="{{ route('detail', $item) }}">
                                <img src="{{ Storage::url($item->pro_img_thumbnail) }}" style="height: 280px" width="280px"
                                    class="img-fluid product-thumbnail">
                                <h3 class="product-title">{{ $item->pro_name }}</h3>
                                <strong class="product-price">{{ number_format($item->pro_price_regular) }} VND</strong>

                                <span class="icon-cross">
                                    <img src="/client/images/cross.svg" class="img-fluid">
                                </span>
                            </a>
                        </div>
                    @endforeach
                @else
                    <div class="text-center">
                        <img src="/client/images/Search-Empty.webp" style="width: 280px;height: 208px;" alt="">
                        <p class="fs-4 mt-4">Không có kết quả bạn cần tìm</p>
                        <a href="{{ route('shop') }}"><button class="btn btn-success">Continue
                                Shopping</button></a>
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection
