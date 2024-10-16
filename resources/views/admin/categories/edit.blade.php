@extends('admin.layouts.master')
@section('title')
    Edit Category
@endsection
@section('content')
    <div class="container-fluid">

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <span style="font-size: 25px" class="m-0 font-weight-bold text-primary">Edit Category</span>
                {{-- <a class="btn btn-primary" style="float: right" href="{{ route('Categorys.create') }}" role="button">Thêm mới</a> --}}

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
                        {{ session()->get('error') }}
                    </div>
                @endif
                <div class="table-responsive">
                    <form action="{{ route('categories.update', $data) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- @dd($data->category_id) --}}
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header align-items-center d-flex">
                                        <h4 class="card-title mb-0 flex-grow-1">Form Category</h4>
                                    </div><!-- end card header -->
                                    <div class="card-body">


                                        <div class="form-group">
                                            <label for="cate_name">Name</label>
                                            <input type="text" value="{{ $data->cate_name }}" name="cate_name"
                                                id="cate_name" class="form-control">
                                        </div>

                                    </div>

                                </div>
                            </div>
                            <!--end col-->
                        </div>


                        {{-- End Gallery --}}
                        <button type="submit" class="btn btn-primary w-100 my-5">Update</button>
                    </form>

                </div>
            </div>
        </div>

    </div>
@endsection