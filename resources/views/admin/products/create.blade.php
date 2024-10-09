@extends('admin.layouts.master')
@section('title')
    Create Product
@endsection
@section('content')
    <div class="container-fluid">

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <span style="font-size: 25px" class="m-0 font-weight-bold text-primary">Create Product</span>
                {{-- <a class="btn btn-primary" style="float: right" href="{{ route('products.create') }}" role="button">Thêm mới</a> --}}

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
                        {{session()->get('error')}}
                    </div>
                @endif
                <div class="table-responsive">
                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf



                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header align-items-center d-flex">
                                        <h4 class="card-title mb-0 flex-grow-1">Form Product</h4>
                                    </div><!-- end card header -->
                                    <div class="card-body">


                                        <div class="form-group">
                                            <label for="name">Category</label>
                                            <select class="form-control" name="category_id" id="">
                                                <option selected value="">---Chọn Danh Mục---</option>

                                                @foreach ($categories as $key => $name)
                                                    <option value="{{ $key }}">{{ $name }}</option>
                                                @endforeach
                                            </select>

                                        </div>
                                        <div class="form-group">
                                            <label for="pro_name">Name</label>
                                            <input type="text" value="{{old('pro_name')}}" name="pro_name" id="pro_name" class="form-control">
                                        </div>
                                        <div class="mt-3">
                                            <label for="sku" class="form-label">SKU</label>
                                            <input type="text" class="form-control" name="pro_sku" id="sku"
                                                value="{{ strtoupper(\Str::random(8)) }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="pro_img_thumbnail">Image</label>
                                            <input type="file" name="pro_img_thumbnail" id="pro_img_thumbnail"
                                                class="form-control p-1">
                                        </div>
                                        <div class="form-group">
                                            <label for="pro_price_regular">Price Regular</label>
                                            <input type="number" value="{{old('pro_price_regular')}}" name="pro_price_regular" id="pro_price_regular"
                                                class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="pro_price_sale">Price Sale</label>
                                            <input type="number" value="{{old('pro_price_sale')}}" name="pro_price_sale" id="pro_price_sale"
                                                class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="pro_featured">Featured</label>
                                            <input  type="checkbox" name="pro_featured" id="pro_featured"
                                                class="form-checkbox" value="1">
                                        </div>
                                        <div class="form-group">
                                            <label for="pro_description">Description</label>
                                            <textarea name="pro_description" id="pro_description" class="form-control">{{old('pro_description')}}</textarea>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <!--end col-->
                        </div>


                        {{-- Biến thể --}}

                        <div class="row mt-3">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header align-items-center d-flex">
                                        <h4 class="card-title mb-0 flex-grow-1">Form Variant</h4>
                                    </div><!-- end card header -->
                                    <div class="card-body" style="height: 450px; overflow-y: scroll">
                                        <div class="live-preview">
                                            <div class="row gy-4">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered">
                                                        <tr class="text-center">
                                                            <th>GB</th>
                                                            <th>Color</th>
                                                            <th>Quantity</th>
                                                        </tr>

                                                        @foreach ($capacities as $gbID => $gbName)
                                                            @php($flagRowspan = true)

                                                            @foreach ($colors as $colorID => $colorName)
                                                                <tr class="text-center">

                                                                    @if ($flagRowspan)
                                                                        <td style="vertical-align: middle;"
                                                                            rowspan="{{ count($colors) }}">
                                                                            <b>{{ $gbName }}</b>
                                                                        </td>
                                                                    @endif

                                                                    @php($flagRowspan = false)

                                                                    <td style="width: 70px;">
                                                                        <div
                                                                            style="width: 50px ; height: 50px; background: {{ $colorName }};">
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <input type="number" class="form-control"
                                                                            value="0"
                                                                            name="product_variants[{{ $gbID . '-' . $colorID }}][quantity]">
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @endforeach
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <!--end col-->
                        </div>
                        {{-- End Biến thể --}}

                        {{-- Gallery --}}
                        <div class="row mt-3">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header align-items-center d-flex">
                                        <h4 class="card-title mb-0 flex-grow-1">Form Tag</h4>
                                    </div><!-- end card header -->
                                    <div class="card-body">

                                        <label for="tags" class="my-3">Tag</label>
                                        <select name="tags[]" multiple id="tags" class="form-control">
                                            @foreach ($tags as $id => $name)
                                                <option value="{{ $id }}">{{ $name }}</option>
                                            @endforeach
                                        </select>

                                    </div>
                                </div>
                            </div>
                            <!--end col-->
                        </div>
                        {{-- End Tag --}}

                        {{-- Tag --}}
                        <div class="row mt-3">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header align-items-center d-flex">
                                        <h4 class="card-title mb-0 flex-grow-1">Gallery Image</h4>
                                    </div><!-- end card header -->
                                    <div class="card-body">

                                        <div class="mb-3 row">
                                            <label for="image_1" class="col-4 col-form-label">Gallery 1</label>
                                            <div class="col-8">
                                                <input type="file" class="form-control p-1" name="image_galleries[]"
                                                    id="image_1" />
                                            </div>
                                        </div>

                                        <div class="mb-3 row">
                                            <label for="image_2" class="col-4 col-form-label">Gallery 2</label>
                                            <div class="col-8">
                                                <input type="file" class="form-control p-1" name="image_galleries[]"
                                                    id="image_2" />
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="image_3" class="col-4 col-form-label">Gallery 3</label>
                                            <div class="col-8">
                                                <input type="file" class="form-control p-1" name="image_galleries[]"
                                                    id="image_3" />
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="image_4" class="col-4 col-form-label">Gallery 4</label>
                                            <div class="col-8">
                                                <input type="file" class="form-control p-1" name="image_galleries[]"
                                                    id="image_4" />
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="image_5" class="col-4 col-form-label">Gallery 5</label>
                                            <div class="col-8">
                                                <input type="file" class="form-control p-1" name="image_galleries[]"
                                                    id="image_5" />
                                            </div>
                                        </div>
                                       
                                    </div>
                                </div>
                            </div>
                            <!--end col-->
                        </div>
                        {{-- End Gallery --}}
                        <button type="submit" class="btn btn-primary w-100 my-5">Thêm mới</button>
                    </form>

                </div>
            </div>
        </div>

    </div>
@endsection
