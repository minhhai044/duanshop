@extends('client.layouts.master')
@section('document')
    Furni Free Bootstrap 5 Template for Furniture and Interior Design Websites by Untree.co
@endsection
@section('content')
    <!-- Product Details Section -->
    <div class="container">
        <div class="row my-5">
            <div class="col-lg-6">
                <img src="{{ Storage::url($dataDetails->pro_img_thumbnail) }}" class="img-fluid p-4 shadow rounded"
                    alt="Product Thumbnail">
                <div class="d-flex mt-3">
                    @foreach ($dataDetails->galleries as $item)
                        <img width="100px" class="me-3 shadow-sm rounded" height="100px"
                            src="{{ Storage::url($item->image) }}" alt="Gallery Image">
                    @endforeach
                </div>
            </div>

            <div class="col-lg-6">
                <h1 class="text-center display-6 fw-bold">{{ $dataDetails->pro_name }}</h1>
                <p class="mt-4">{{ $dataDetails->pro_description }}</p>
                <form action="{{ route('addcart') }}" method="post">
                    @csrf
                    <!-- Storage Options -->
                    <div class="mt-4">
                        <h5 class="fw-bold">Select Storage:

                            @error('capacity_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror

                        </h5>
                        <div class="d-flex gap-2 flex-wrap" role="group" aria-label="Storage Options">

                            @foreach ($Capacities as $item)
                                <input type="radio" class="btn-check @error('capacity_id') @enderror" name="capacity_id"
                                    id="storage-{{ $item->id }}" autocomplete="off" value="{{ $item->id }}">


                                <label class="btn btn-success"
                                    for="storage-{{ $item->id }}">{{ $item->cap_name }}</label>
                            @endforeach
                        </div>
                    </div>



                    <!-- Color Options -->
                    <div class="mt-4 ">
                        <h5 class="fw-bold">Select Color:
                            @error('color_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </h5>
                        <select class="form-select w-50 @error('color_id') @enderror" name="color_id">
                            <option value="">--- Vui lòng chọn ---</option>
                            @foreach ($colors as $item)
                                <option value="{{ $item->id }}">{{ $item->color_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Pricing -->
                    <div class="mt-4">
                        <h5>Price:</h5>
                        <span
                            class="text-decoration-line-through me-2">{{ number_format($dataDetails->pro_price_regular) }}
                            đ</span>
                        <span class="text-danger fw-bold">{{ number_format($dataDetails->pro_price_sale) }} đ</span>
                    </div>

                    <!-- Add to Cart Button -->
                    <div class="mt-4">
                        <button class="btn btn-success rounded-pill shadow">Add to Cart</button>
                    </div>
                </form>



            </div>
        </div>
    </div>
    <!-- End Product Details Section -->
@endsection
