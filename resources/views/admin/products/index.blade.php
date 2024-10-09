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
                @if (session()->has('error'))
                    <div class="alert alert-danger">
                        {{ session()->get('error') }}
                    </div>
                @endif
                <div class="table-responsive">

                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>category</th>
                                <th>Sku</th>
                                <th>Name</th>
                                <th>Image</th>
                                <th>Price</th>
                                <th>Featured</th>
                                <th>Updated At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->category->cate_name }}</td>
                                    <td> {{ $item->pro_sku }} </td>
                                    <td> {{ $item->pro_name }} </td>
                                    <td>
                                        @if ($item->pro_img_thumbnail)
                                            <img src="{{ Storage::url($item->pro_img_thumbnail) }}" width="100px"
                                                height="100px" alt="">
                                        @endif
                                    </td>
                                    <td style="width: 130px;"> {{ number_format($item->pro_price_regular) }} VND</td>
                                    <td>
                                        @if ($item->pro_featured)
                                            <button disabled class="btn btn-success">New</button>
                                        @else
                                            <button disabled class="btn btn-warning">Old</button>
                                        @endif
                                    </td>
                                    <td> {{ $item->updated_at->format('d/m/Y H:i:s') }} </td>
                                    <td style="width: 130px;display: flex">
                                        <a class="btn btn-dark mr-2" href="{{ route('products.edit', $item) }}"
                                            role="button">Edit</a>
                                        <form action="{{ route('products.destroy', $item) }}" method="post"
                                            enctype="multipart/form-data">
                                            @csrf
                                            @method('DELETE')
                                            <button onclick="return confirm('Bạn có chắc chắn xóa không !!!')" type="submit" class="btn btn-danger">Delete</button>
                                        </form>

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
