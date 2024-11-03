@extends('client.layouts.master')
@section('document')
    Furni Free Bootstrap 5 Template for Furniture and Interior Design Websites by Untree.co
@endsection
@section('content')
    <div class="untree_co-section product-section before-footer-section">
        <div class="container">
            <form action="{{ route('searchfilter') }}" method="POST" class="d-flex mb-4">
                @csrf
                {{-- Lọc theo danh mục --}}
                <select name="category_id" style="width: 200px;" class="form-select me-2">
                    <option value="">Chọn danh mục</option>

                    @foreach ($categories as $id => $value)
                        <option value="{{ $id }}">{{ $value }}</option>
                    @endforeach
                </select>

                {{-- Lọc theo giá --}}
                <select name="filter_price" class="form-select">
                    <option value="0-100000000">Chọn khoảng giá</option>
                    <option value="0-1000000">Dưới 1,000,000 VND</option>
                    <option value="1000000-5000000">1,000,000 - 5,000,000 VND</option>
                    <option value="5000000-10000000">5,000,000 - 10,000,000 VND</option>
                    <option value="5000000-10000000">10,000,000 - 20,000,000 VND</option>
                    <option value="20000000-100000000">Trên 20,000,000VND</option>
                </select>

                <button type="submit" class="btn btn-primary ms-2">Filter</button>
            </form>
            @if (!$products->isEmpty())
                <h3 class="mb-4 container">Products List</h3>
                <div class="row">

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

                    {{ $products->links() }}
                </div>
            @else
                <div class="text-center">
                    <img src="/client/images/Search-Empty.webp" style="width: 280px;height: 208px;" alt="">
                    <p class="fs-4 mt-4">Không có kết quả bạn cần tìm</p>
                    <a href="{{ route('shop') }}"><button class="btn btn-success">Continue Shopping</button></a>
                </div>
            @endif
        </div>
    </div>
@endsection
