@extends('client.layouts.master')
@section('document')
    Furni Free Bootstrap 5 Template for Furniture and Interior Design Websites by Untree.co
@endsection
@section('content')
    <!-- Start Hero Section -->
    {{-- <div class="hero">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-lg-5">
                    <div class="intro-excerpt">
                        <h1>My <span clsas="d-block">Cart</span></h1>
                        <p class="mb-4">Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam
                            vulputate velit imperdiet dolor tempor tristique.</p>

                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="hero-img-wrap">
                        <img src="/client/images/sofaaa.png" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
    <!-- End Hero Section -->



    <div class="untree_co-section before-footer-section">
        <div class="container">
            <div class="row mb-5">
                <form class="col-md-12" method="post">
                    <div class="site-blocks-table">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="product-thumbnail">Image</th>
                                    <th class="product-name">Product</th>
                                    <th class="product-price">Price</th>
                                    <th class="product-price">Capacity</th>
                                    <th class="product-price">Color</th>
                                    <th class="product-quantity">Quantity</th>
                                    <th class="product-total">Total</th>
                                    <th class="product-remove">Remove</th>
                                </tr>
                            </thead>
                            <tbody>
{{-- @dd($productVariant) --}}
                                    @foreach ($productVariant as $item)
                                        <tr>
                                            <td class="product-thumbnail">
                                                @if ($item->product->pro_img_thumbnail)
                                                    <img src="{{ Storage::url($item->product->pro_img_thumbnail) }}" width="80px"
                                                        height="80px" alt="Image" class="img-fluid">
                                                @endif
                                            </td>
                                            <td class="product-name">
                                                <h2 class="h5 text-black">{{ $item->product->pro_name }}</h2>
                                            </td>
                                            @if ($item->product->pro_price_sale)
                                                <td style="width: 140px">{{ number_format($item->product->pro_price_sale) }} đ</td>
                                            @else
                                                <td style="width: 140px">{{ number_format($item->product->pro_price_regular) }} đ
                                                </td>
                                            @endif

                                            <td>
                                                <p>{{ $item->capacity->cap_name }}</p>
                                            </td>
                                            <td>
                                                <p>{{ $item->color->color_name}}</p>
                                            </td>
                                            <td>
                                                <div class="input-group mb-3 d-flex align-items-center quantity-container"
                                                    style="max-width: 120px;">
                                                    <div class="input-group-prepend">
                                                        <button class="btn btn-outline-black decrease"
                                                            type="button">&minus;</button>
                                                    </div>

                                                    <input type="text"
                                                        class="form-control p-2 text-center quantity-amount"
                                                        value="{{ $item->cartitem->cart_item_quantity }}" placeholder=""
                                                        aria-label="Example text with button addon"
                                                        aria-describedby="button-addon1">

                                                    <div class="input-group-append">
                                                        <button class="btn btn-outline-black increase"
                                                            type="button">&plus;</button>
                                                    </div>
                                                </div>

                                            </td>
                                            @if ($item['pro_price_sale'])
                                                <td style="width: 140px">
                                                    {{ number_format($item->product->pro_price_sale * $item->cartitem->cart_item_quantity) }} đ
                                                </td>
                                            @else
                                                <td style="width: 140px">
                                                    {{ number_format($item->product->pro_price_regular * $item->cartitem->cart_item_quantity)}}đ
                                                </td>
                                            @endif
                                            <td><a href="#" class="btn btn-black btn-sm">X</a></td>
                                        </tr>
                                    @endforeach
                        


                          
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="row mb-5">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <button class="btn btn-black btn-sm btn-block">Update Cart</button>
                        </div>
                        <div class="col-md-6">
                            <button class="btn btn-outline-black btn-sm btn-block">Continue Shopping</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label class="text-black h4" for="coupon">Coupon</label>
                            <p>Enter your coupon code if you have one.</p>
                        </div>
                        <div class="col-md-8 mb-3 mb-md-0">
                            <input type="text" class="form-control py-3" id="coupon" placeholder="Coupon Code">
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-black">Apply Coupon</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 pl-5">
                    <div class="row justify-content-end">
                        <div class="col-md-7">
                            <div class="row">
                                <div class="col-md-12 text-right border-bottom mb-5">
                                    <h3 class="text-black h4 text-uppercase">Cart Totals</h3>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <span class="text-black">Subtotal</span>
                                </div>
                                <div class="col-md-6 text-right">
                                    <strong class="text-black">$230.00</strong>
                                </div>
                            </div>
                            <div class="row mb-5">
                                <div class="col-md-6">
                                    <span class="text-black">Total</span>
                                </div>
                                <div class="col-md-6 text-right">
                                    <strong class="text-black">$230.00</strong>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <button class="btn btn-black btn-lg py-3 btn-block"
                                        onclick="window.location='checkout.html'">Proceed To Checkout</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
