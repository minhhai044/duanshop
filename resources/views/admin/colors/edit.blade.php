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
                                            <label for="color_code">Mã màu (Color Code)</label>
                                            <input type="color" value="{{ old('color_code', $data->color_code) }}" name="color_code"
                                                id="color_code" class="form-control" style="height: 50px;">
                                            <small class="form-text text-muted">Chọn màu nền cho màu sắc này</small>
                                        </div>

                                        <div class="form-group">
                                            <label for="color_text">Màu chữ (Text Color)</label>
                                            <input type="color" value="{{ old('color_text', $data->color_text) }}" name="color_text"
                                                id="color_text" class="form-control" style="height: 50px;">
                                            <small class="form-text text-muted">Chọn màu chữ hiển thị trên nền màu</small>
                                        </div>

                                        <div class="form-group">
                                            <label>Xem trước màu sắc</label>
                                            <div id="color-preview" style="padding: 20px; border-radius: 5px; text-align: center; font-weight: bold; background-color: {{ $data->color_code }}; color: {{ $data->color_text }};">
                                                {{ $data->color_name }}
                                            </div>
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const colorCodeInput = document.getElementById('color_code');
        const colorTextInput = document.getElementById('color_text');
        const colorPreview = document.getElementById('color-preview');
        const colorNameInput = document.getElementById('color_name');

        function updatePreview() {
            const backgroundColor = colorCodeInput.value;
            const textColor = colorTextInput.value;
            const colorName = colorNameInput.value || 'Màu sắc mẫu';
            
            colorPreview.style.backgroundColor = backgroundColor;
            colorPreview.style.color = textColor;
            colorPreview.textContent = colorName;
        }

        colorCodeInput.addEventListener('input', updatePreview);
        colorTextInput.addEventListener('input', updatePreview);
        colorNameInput.addEventListener('input', updatePreview);
        
        // Initial preview update
        updatePreview();
    });
</script>
@endpush
