@extends('admin.layouts.master')
@section('title')
    Edit Color
@endsection
@section('content')
    <div class="container-fluid">

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <span style="font-size: 25px" class="m-0 font-weight-bold text-primary">Edit Color</span>

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
                    <form action="{{route('colors.update',$data)}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header align-items-center d-flex">
                                        <h4 class="card-title mb-0 flex-grow-1">Form Color</h4>
                                    </div><!-- end card header -->
                                    <div class="card-body">

                                        <div class="form-group">
                                            <label for="color_name">Name <span class="text-danger">*</span></label>
                                            <input type="text" value="{{ old('color_name', $data->color_name) }}" name="color_name"
                                                id="color_name" class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="slug">Slug</label>
                                            <input type="text" value="{{ old('slug', $data->slug) }}" name="slug"
                                                id="slug" class="form-control" placeholder="Để trống để tự động tạo">
                                        </div>

                                        <div class="form-group">
                                            <label for="is_active">Status</label>
                                            <select name="is_active" id="is_active" class="form-control">
                                                <option value="1" {{ old('is_active', $data->is_active) == '1' ? 'selected' : '' }}>Active</option>
                                                <option value="0" {{ old('is_active', $data->is_active) == '0' ? 'selected' : '' }}>Inactive</option>
                                            </select>
                                        </div>

                                    </div>

                                </div>
                            </div>
                            <!--end col-->
                        </div>

                        <button type="submit" class="btn btn-primary w-100 my-5">Update</button>
                    </form>

                </div>
            </div>
        </div>

    </div>
@endsection
