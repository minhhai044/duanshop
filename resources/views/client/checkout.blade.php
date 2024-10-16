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
								<h1>Checkout</h1>
							</div>
						</div>
						<div class="col-lg-7">
							
						</div>
					</div>
				</div>
			</div> --}}
    <!-- End Hero Section -->

    <div class="untree_co-section">
        <form action="{{ route('store.checkout') }}" method="post">
            @csrf

            <div class="container">
                <div class="row">
                    <div class="col-md-6 mb-5 mb-md-0">
                        <h2 class="h3 mb-3 text-black">Billing Details</h2>
                        <div class="p-3 p-lg-5 border bg-white">

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $item)
                                            <li>
                                                {{ $item }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif


                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label for="order_user_name" class="text-black"> Name </label><span class="text-danger">
                                        *</span>
                                    <input value="{{ old('order_user_name') }}" placeholder="Name" type="text"
                                        class="form-control" id="order_user_name" name="order_user_name">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label for="order_user_address" class="text-black">Address <span
                                            class="text-danger">*</span></label>
                                    <input value="{{ old('order_user_address') }}" type="text" class="form-control"
                                        id="order_user_address" name="order_user_address" placeholder="Address">
                                </div>
                            </div>




                            <div class="form-group row mb-5">
                                <div class="col-md-6">
                                    <label for="order_user_email" class="text-black">Email <span
                                            class="text-danger">*</span></label>
                                    <input value="{{ old('order_user_email') }}" type="email" placeholder="Email"
                                        class="form-control" id="order_user_email" name="order_user_email">
                                </div>
                                <div class="col-md-6">
                                    <label for="order_user_phone" class="text-black">Phone <span
                                            class="text-danger">*</span></label>
                                    <input value="{{ old('order_user_phone') }}" type="number" class="form-control"
                                        id="order_user_phone" name="order_user_phone" placeholder="Phone Number">
                                </div>
                            </div>


                            <div class="form-group">
                                <label for="order_user_note" class="text-black">Order Notes</label>
                                <textarea name="order_user_note" id="order_user_note" cols="30" rows="5" class="form-control"
                                    placeholder="Write your notes here...">{{ old('order_user_note') }}</textarea>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-6">

                        <div class="row mb-5">
                            <div class="col-md-12">
                                <h2 class="h3 mb-3 text-black">Your Order</h2>
                                <div class="p-3 p-lg-5 border bg-white">
                                    <table class="table site-block-order-table mb-5">
                                        <thead>
                                            <th>Product</th>
                                            <th>Quantity</th>
                                            <th>Total</th>
                                        </thead>
                                        <tbody>

                                            @foreach ($productVariants as $item)
                                                <tr>
                                                    <td>{{ $item->product->pro_name }}</td>
                                                    <td>
                                                        @foreach ($item->cartitem as $value)
                                                            @if ($value->cart_id == $item->cart_id)
                                                                {{ $value->cart_item_quantity }}
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                    @foreach ($item->cartitem as $value)
                                                        @if ($value->cart_id == $item->cart_id)
                                                            @if (!empty($item->product->pro_price_sale))
                                                                <td>
                                                                    {{ number_format($value->cart_item_quantity * $item->product->pro_price_sale) }}
                                                                    đ
                                                                </td>
                                                            @endif
                                                        @endif
                                                    @endforeach

                                                </tr>
                                                <input type="hidden" name="order_total_price" value="{{ $total }}">
                                            @endforeach

                                            <tr>
                                                <td colspan="2" class="text-black font-weight-bold"><strong>Cart
                                                        Subtotal</strong></td>
                                                <td class="text-black">{{ number_format($total) }} đ</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" class="text-black font-weight-bold"><strong>Order
                                                        Total</strong></td>
                                                <td class="text-black font-weight-bold"><strong>{{ number_format($total) }}
                                                        đ</strong></td>
                                            </tr>

                                        </tbody>
                                    </table>

                                    <div class="mt-5">
                                        <h4 class="text-black">Phương thức thanh toán</h4>
                                        <div class="mt-3">

                                            {{-- @foreach ($METHOD_PAYMENT as $item) --}}
                                            <input checked type="radio" name="method_payment" value="cash_delivery"
                                                id="">
                                            {{ $METHOD_PAYMENT['cash_delivery'] }} <br>
                                            <input type="radio" name="method_payment" value="vnpay_payment"
                                                id="">
                                            {{ $METHOD_PAYMENT['vnpay_payment'] }}
                                            {{-- @endforeach --}}


                                        </div>
                                    </div>

                                    <div class="form-group mt-4">
                                        <button class="btn btn-black btn-lg py-3 btn-block">Place Order</button>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- </form> -->
            </div>
        </form>
    </div>
@endsection
