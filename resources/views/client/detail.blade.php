@extends('client.layouts.master')
@section('document')
    ShopSieuReOk
@endsection
@section('content')
    <!-- Product Details Section -->
    <div class="container">
        <div class="row my-5">
            <div class="col-lg-6">
                @if ($dataDetails->pro_img_thumbnail)
                    <img src="{{ Storage::url($dataDetails->pro_img_thumbnail) }}" width="450px" height="450px"
                        class="img-fluid p-4 shadow rounded" alt="Product Thumbnail">
                @endif
                <div class="d-flex mt-3">
                    @foreach ($dataDetails->galleries as $item)
                        <img width="100px" class="me-3 shadow-sm rounded" height="100px"
                            src="{{ Storage::url($item->image) }}" alt="Gallery Image">
                    @endforeach
                </div>
            </div>

            <div class="col-lg-6">
                @if (session()->has('error'))
                    <div class="alert alert-danger">{{ session()->get('error') }}</div>
                @endif
                <h1 class="text-center display-6 fw-bold">{{ $dataDetails->pro_name }}</h1>
                <div class="mt-4 " style="word-wrap: break-word; overflow-wrap: break-word;">
                    {{ $dataDetails->pro_description }}</div>
                <form action="{{ route('addcart') }}" method="post">
                    @csrf
                    <!-- Storage Options -->
                    <div class="mt-4">
                        <h5 class="fw-bold">Select Capacity:</h5>

                        <!-- Error Message for Capacity -->
                        @error('capacity_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror

                        <div class="d-flex gap-3 flex-wrap" role="group" aria-label="Storage Options">
                            @foreach ($Capacities as $id => $item)
                                <input type="radio" class="btn-check gb" name="capacity_id"
                                    id="storage-{{ $id }}" autocomplete="off" value="{{ $id }}"
                                    @if ($id == 3) checked @endif>

                                <label class="btn btn-outline-dark"
                                    for="storage-{{ $id }}">{{ $item }}</label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Color Options -->
                    <div class="mt-4">
                        <h5 class="fw-bold">Select Color:</h5>
                        @error('color_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        <div class="d-flex gap-3 flex-wrap" role="group" aria-label="Color Options">
                            @foreach ($colors as $id => $item)
                                <input type="radio" @if ($id == 1) checked @endif
                                    class="btn-check color-radio" name="color_id" id="color-{{ $id }}"
                                    autocomplete="off" value="{{ $id }}">

                                <label class="color-box" for="color-{{ $id }}"
                                    style="background-color: {{ $item }}; 
                   width: 40px; height: 40px; border-radius: 5px; border: 2px solid #000;"></label>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-3 mt-3">
                        <label for="quantity" class="form-label">Quantity:</label>
                        <span id="quantity_show"></span> <br>


                        <input type="number" class="form-control" min="1" required value="1" id="quantity"
                            placeholder="Enter quantity" name="quantity">


                    </div>

                    <!-- Pricing -->
                    <div class="mt-4">
                        <h5>Price:</h5>
                        <span
                            class="text-decoration-line-through me-2">{{ number_format($dataDetails->pro_price_regular) }}
                            đ</span>
                        <span class="text-danger fw-bold">{{ number_format($dataDetails->pro_price_sale) }} đ</span>
                    </div>

                    <input type="text" hidden name="id" value="{{ $dataDetails->id }}">
                    <!-- Add to Cart Button -->
                    <div class="mt-4">
                        <button style="margin-bottom: 200px" class="btn btn-success p-3 w-100 ">Add to Cart</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Product Details Section -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get references to the radio buttons and quantity field
            const capacityRadios = document.querySelectorAll('input[name="capacity_id"]');
            const colorRadios = document.querySelectorAll('input[name="color_id"]');
            const quantityField = document.getElementById('quantity');
            const quantity_show = document.getElementById('quantity_show');
            const Capacities = @json($Capacities);
            const colors = @json($colors);
            const variants = @json($variants);

            let selectedCapacity = '';
            let selectedColor = '';
            let quantity = 1;

            // Function to log selected values
            let value = 0;

            function logSelectedValues() {
                selectedCapacity = document.querySelector('input[name="capacity_id"]:checked')?.value;
                selectedColor = document.querySelector('input[name="color_id"]:checked')?.value;
                quantity = quantityField.value;

                let value = variants.forEach((item) => {
                    if (item.capacity_id == selectedCapacity && item.color_id == selectedColor) {

                        quantity_show.innerHTML = item.quantity;
                    }
                });


            }


            capacityRadios.forEach(radio => {
                radio.addEventListener('change', logSelectedValues);
            });

            colorRadios.forEach(radio => {
                radio.addEventListener('change', logSelectedValues);
            });

            quantityField.addEventListener('input', logSelectedValues);

        });
    </script>
@endsection
