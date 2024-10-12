@extends('client.layouts.master')
@section('document')
    Furni Free Bootstrap 5 Template for Furniture and Interior Design Websites by Untree.co
@endsection
@section('content')
    <div class="untree_co-section product-section before-footer-section">
        <div class="container">
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
        </div>
    </div>
@endsection
