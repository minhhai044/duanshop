@extends('admin.layouts.master')
@section('title')
    Product
@endsection
@section('content')
    <div class="container-fluid">

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <span style="font-size: 25px" class="m-0 font-weight-bold text-primary">List Product</span>
                <a class="btn btn-primary" style="float: right" href="{{ route('products.create') }}" role="button">Thêm
                    mới</a>

            </div>
            <div class="card-body">
                @if (session()->has('success'))
                    <div class="alert alert-success">
                        {{ session()->get('success') }}
                    </div>
                @endif
                <div class="table-responsive">

                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Sku</th>
                                <th>Name</th>
                                <th>Image</th>
                                <th>Price</th>
                                <th>Featured</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $item)
                                <tr>
                                    <td> {{ $item->pro_sku }} </td>
                                    <td> {{ $item->pro_name }} </td>
                                    <td> 
                                        @if ($item->pro_img_thumbnail)
                                            <img src="{{Storage::url($item->pro_img_thumbnail)}}" width="100px" alt="">
                                        @endif    
                                    </td>
                                    <td> {{ $item->pro_price_regular }} </td>
                                    <td> {{ $item->pro_featured }} </td>
                                    <td> {{ $item->created_at }} </td>
                                    <td> {{ $item->updated_at }} </td>
                                    <td style="">
                                        <a class="btn btn-primary" href="{{route('products.edit',$item)}}" role="button">Edit</a>


                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection
