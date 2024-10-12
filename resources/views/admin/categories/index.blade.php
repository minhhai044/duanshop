@extends('admin.layouts.master')
@section('title')
    Category
@endsection
@section('content')
    <div class="container-fluid">

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <span style="font-size: 25px" class="m-0 font-weight-bold text-primary">List Category</span>
                <a class="btn btn-primary" style="float: right" href="{{ route('categories.create') }}" role="button">Thêm
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
                                <th>Name</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->cate_name }}</td>
                                    
                                    <td> {{ $item->created_at->format('d/m/Y H:i:s') }} </td>
                                    <td> {{ $item->updated_at->format('d/m/Y H:i:s') }} </td>
                                    <td style="width: 130px;display: flex">
                                        <a class="btn btn-dark mr-2" href="{{ route('categories.edit', $item) }}"
                                            role="button">Edit</a>
                                        <form action="{{ route('categories.destroy', $item) }}" method="post"
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
