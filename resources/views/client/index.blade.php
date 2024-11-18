@extends('client.layouts.master')
@section('document')
    ShopSieuReOk
@endsection
@section('content')
    <div class="testimonial-section">
        {{-- <div class="container"> --}}

        <div class="row justify-content-center">
            <div class="testimonial-slider-wrap text-center">

                <div id="testimonial-nav">
                    <span class="prev" data-controls="prev"><span class="fa fa-chevron-left"></span></span>
                    <span class="next" data-controls="next"><span class="fa fa-chevron-right"></span></span>
                </div>

                <div class="testimonial-slider">
                    <div class="testimonial-block text-center">
                        <blockquote class="">

                            <img src="{{ '/client/images/03_Mockup_Summer.jpg' }}" width="100%" height="666px"
                                alt="">
                        </blockquote>
                    </div>
                    <div class="testimonial-block text-center">
                        <blockquote class="">

                            <img src="{{ '/client/images/banner-sale.jpg' }}" width="100%" height="666px" alt="">
                        </blockquote>
                    </div>

                </div>

            </div>
        </div>
        {{-- </div> --}}
    </div>

    <!-- Start Product Section -->
    <div class="product-section">
        <div class="container">
            <div class="row">

                <!-- Start Column 2 -->
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

            </div>
        </div>
    </div>
    <!-- End Product Section -->

   

    <!-- Start Popular Product -->
    <div class="popular-product my-5">
        <div class="container">
            <div class="row">

                <div class="col-12 col-md-6 col-lg-4 mb-4 mb-lg-0">
                    <div class="product-item-sm d-flex">
                        <div class="thumbnail">
                            <img src="/client/images/product-1.png" alt="Image" class="img-fluid">
                        </div>
                        <div class="pt-3">
                            <h3>Nordic Chair</h3>
                            <p>Donec facilisis quam ut purus rutrum lobortis. Donec vitae odio </p>
                            <p><a href="#">Read More</a></p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-4 mb-4 mb-lg-0">
                    <div class="product-item-sm d-flex">
                        <div class="thumbnail">
                            <img src="/client/images/product-2.png" alt="Image" class="img-fluid">
                        </div>
                        <div class="pt-3">
                            <h3>Kruzo Aero Chair</h3>
                            <p>Donec facilisis quam ut purus rutrum lobortis. Donec vitae odio </p>
                            <p><a href="#">Read More</a></p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-4 mb-4 mb-lg-0">
                    <div class="product-item-sm d-flex">
                        <div class="thumbnail">
                            <img src="/client/images/product-3.png" alt="Image" class="img-fluid">
                        </div>
                        <div class="pt-3">
                            <h3>Ergonomic Chair</h3>
                            <p>Donec facilisis quam ut purus rutrum lobortis. Donec vitae odio </p>
                            <p><a href="#">Read More</a></p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- End Popular Product -->
@endsection
