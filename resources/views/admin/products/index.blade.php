@extends('admin.layouts.master')
@section('title')
    Product Management
@endsection
@section('content')
    <div class="container-fluid">

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <span style="font-size: 25px" class="m-0 font-weight-bold text-primary">Product Management</span>
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
                                <th>Category</th>
                                <th>SKU</th>
                                <th>Name</th>
                                <th>Slug</th>
                                <th>Image</th>
                                <th>Price</th>
                                <th>Featured</th>
                                <th>Hot</th>
                                <th>Status</th>
                                <th>Updated At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $item)
                                <tr class="{{ !$item->is_active ? 'table-secondary' : '' }}">
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->category->cate_name }}</td>
                                    <td>{{ $item->pro_sku }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="{{ !$item->is_active ? 'text-muted' : '' }}">{{ $item->pro_name }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $item->pro_slug }}</small>
                                    </td>
                                    <td>
                                        @if ($item->pro_img_thumbnail)
                                            <img src="{{ getImageStorage($item->pro_img_thumbnail) }}" width="50px"
                                                height="50px" alt="{{ $item->pro_name }}" class="img-thumbnail">
                                        @else
                                            <span class="text-muted">Không có ảnh</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ number_format($item->pro_price_regular) }} VND</strong>
                                            @if($item->pro_price_sale > 0)
                                                <br><small class="text-danger">{{ number_format($item->pro_price_sale) }} VND</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $item->pro_featured ? 'success' : 'secondary' }}">
                                            {{ $item->pro_featured ? 'Featured' : 'Normal' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $item->is_hot ? 'danger' : 'secondary' }}">
                                            {{ $item->is_hot ? 'Hot' : 'Normal' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $item->is_active ? 'success' : 'warning' }}">
                                            {{ $item->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>{{ $item->updated_at->format('d/m/Y H:i:s') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a class="btn btn-sm btn-dark" href="{{ route('products.edit', $item) }}"
                                                role="button" title="Edit Product">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <a href="{{ route('products.toggleStatus', $item) }}" 
                                               class="btn btn-sm btn-{{ $item->is_active ? 'warning' : 'success' }}" 
                                               onclick="return confirm('Bạn có chắc chắn {{ $item->is_active ? 'vô hiệu hóa' : 'kích hoạt' }} sản phẩm này không?')" 
                                               title="{{ $item->is_active ? 'Deactivate' : 'Activate' }} Product">
                                                <i class="fas fa-{{ $item->is_active ? 'ban' : 'check' }}"></i>
                                                {{ $item->is_active ? 'Deactivate' : 'Activate' }}
                                            </a>
                                        </div>
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
