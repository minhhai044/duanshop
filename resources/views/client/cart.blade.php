@extends('client.layouts.master')
@section('document')
    ShopSieuReOk
@endsection
@section('content')


    <div class="untree_co-section before-footer-section">
        <div class="container">

            {{-- @dd(($productVariants)) --}}
            @if (!empty($productVariants))
                <div class="row mb-5">
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
                                @foreach ($productVariants as $item)
                                    <tr>
                                        <td class="product-thumbnail">
                                            @if ($item->product->pro_img_thumbnail)
                                                <img src="{{ Storage::url($item->product->pro_img_thumbnail) }}"
                                                    width="80px" height="80px" alt="Image" class="img-fluid">
                                            @endif
                                        </td>
                                        <td class="product-name">
                                            <h2 class="h5 text-black">{{ $item->product->pro_name }}</h2>
                                        </td>
                                        @if ($item->product->pro_price_sale)
                                            <td style="width: 140px">{{ number_format($item->product->pro_price_sale) }} đ
                                            </td>
                                        @else
                                            <td style="width: 140px">{{ number_format($item->product->pro_price_regular) }}
                                                đ
                                            </td>
                                        @endif

                                        <td>
                                            <p>{{ $item->capacity->cap_name }}</p>
                                        </td>
                                        <td>
                                            <p>{{ $item->color->color_name }}</p>
                                        </td>
                                        <td>



                                            <div class="input-group mb-3 d-flex align-items-center quantity-container"
                                                style="max-width: 120px;">


                                                <input disabled min="1" max="{{ $item->quantity }}"
                                                    name="quantity_{{ $item->id }}" type="text"
                                                    class="form-control p-2 text-center"
                                                    @foreach ($item->cartitem as $value)
                                                        @if ($value->cart_id == $item->cart_id)
                                                            value="{{ $value->cart_item_quantity }}" 
                                                        @endif @endforeach>


                                            </div>
                                        </td>

                                        @if (!empty($item->product->pro_price_sale))
                                            @foreach ($item->cartitem as $value)
                                                @if ($value->cart_id == $item->cart_id)
                                                    <td style="width: 140px">
                                                        {{ number_format($item->product->pro_price_sale * $value->cart_item_quantity) }}
                                                        đ
                                                    </td>
                                                @endif
                                            @endforeach
                                        @else
                                            @foreach ($item->cartitem as $value)
                                                @if ($value->cart_id == $item->cart_id)
                                                    <td style="width: 140px">
                                                        {{ number_format($item->product->pro_price_regular * $value->cart_item_quantity) }}
                                                        đ
                                                    </td>
                                                @endif
                                            @endforeach
                                        @endif
                                        <td>
                                            @foreach ($item->cartitem as $value)
                                                @if ($value->cart_id == $item->cart_id)
                                                    <form action="{{ route('cart.delete', $value) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button onclick="return confirm('Bạn có chắc chắn xóa không !!!')"
                                                            type="submit" class="btn btn-black btn-sm">X</button>
                                                    </form>
                                                @endif
                                            @endforeach

                                        </td>
                                    </tr>
                                    <form id="myForm" action="{{ route('cart.delete.all', $item->cart_id) }}"
                                        method="post">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                @endforeach
                            </tbody>
                        </table>

                    </div>

                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="row mb-5">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <button onclick="return confirm('Bạn có chắc chắn xóa tất cả không !!!')" type="submit"
                                    id="submitButton" class="btn btn-success btn-block">Delete
                                    Cart</button></a>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ route('shop') }}"><button class="btn btn-success btn-block">Continue
                                        Shopping</button></a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label class="text-black h4" for="coupon">Coupon</label>
                                <p>Enter your coupon code if you have one.</p>
                            </div>
                            <form action="{{ route('coupons.cart') }}" method="post" class="d-flex gap-2">
                                @csrf
                                <div class="col-md-8 mb-3 mb-md-0">
                                    <input type="text" class="form-control h-100" name="coupon_code" id="coupon"
                                        placeholder="Coupon Code">
                                </div>
                                <div class="col-md-4 ">
                                    <button class="btn btn-success p-2">Apply Coupon</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-6 pl-5">

                        <div class="row justify-content-end">
                            <div class="col-md-7">
                                <div class="row mb-4">
                                    <div class="col-md-12 text-right border-bottom">
                                        <h3 class="text-black h4 text-uppercase">Cart Totals</h3>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <span class="text-black">Subtotal</span>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <strong class="text-black">{{ number_format($subtotal) }} đ</strong>
                                    </div>
                                </div>
                                <!-- Discount -->
                                @if ($dataCouponsProduct)
                                    @foreach ($dataCouponsProduct as $item)
                                        @foreach ($item->cartitem as $value)
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <span class="text-muted">{{ $item->product->pro_name }}</span>
                                                </div>
                                                <div class="col-md-6 text-end">
                                                    @if ($item->product->pro_price_sale)
                                                        @if ($coupons['discount_type'])
                                                            <span
                                                                class="text-danger">-{{ number_format($coupons['discount_value']) }}
                                                                đ</span>
                                                        @else
                                                            <span
                                                                class="text-danger">-{{ number_format($item->product->pro_price_sale * ($coupons['discount_value'] / 100) * $value->cart_item_quantity) }}
                                                                đ</span>
                                                        @endif
                                                    @else
                                                        @if ($coupons['discount_type'])
                                                            <span
                                                                class="text-danger">-{{ number_format($coupons['discount_value']) }}
                                                                đ</span>
                                                        @else
                                                            <span
                                                                class="text-danger">-{{ number_format($item->product->pro_price_regular * ($coupons['discount_value'] / 100) * $value->cart_item_quantity) }}
                                                                đ</span>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    @endforeach
                                @endif

                                <div class="row mb-4 border-top pt-3">
                                    <div class="col-md-6">
                                        <span class="text-black">Total</span>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <strong class="text-black">{{ number_format($total) }} đ</strong>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <a href="{{ route('checkout') }}" class="btn btn-success py-3 w-100">Proceed To
                                            Checkout</a>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            @else
                <div class="text-center">
                    <img src="{{ '/client/images/Cart-empty-v2.webp' }}" width="300px" alt="">
                    <p class="mt-3">Giỏ hàng của bạn đang trống.</p>
                    <p> Hãy chọn thêm sản phẩm để mua sắm nhé</p>

                    <a href="{{ route('shop') }}"><button class="btn btn-success">Continue
                            Shopping</button></a>

                </div>
            @endif

        </div>
    </div>

@endsection
