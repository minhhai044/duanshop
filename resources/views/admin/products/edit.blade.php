@extends('admin.layouts.master')
@section('title')
    Edit Product
@endsection

@section('content')
    <div class="container-fluid">

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <span style="font-size: 25px" class="m-0 font-weight-bold text-primary">Edit Product</span>
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
                    <form action="{{ route('products.update', $data) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header align-items-center d-flex">
                                        <h4 class="card-title mb-0 flex-grow-1">Form Product</h4>
                                    </div><!-- end card header -->
                                    <div class="card-body">

                                        <div class="form-group">
                                            <label for="name">Category <span class="text-danger">*</span></label>
                                            <select class="form-control" name="category_id" id="category_id" required>
                                                <option value="">---Chọn Danh Mục---</option>
                                                @foreach ($categories as $id => $name)
                                                    <option @selected(old('category_id', $data->category_id) == $id) value="{{ $id }}">
                                                        {{ $name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="pro_name">Name <span class="text-danger">*</span></label>
                                            <input type="text" value="{{ old('pro_name', $data->pro_name) }}" name="pro_name"
                                                id="pro_name" class="form-control" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="pro_slug">Slug</label>
                                            <input type="text" value="{{ old('pro_slug', $data->pro_slug) }}" name="pro_slug"
                                                id="pro_slug" class="form-control" placeholder="Để trống để tự động tạo">
                                        </div>
                                        
                                        <div class="mt-3">
                                            <label for="sku" class="form-label">SKU <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="pro_sku" id="sku"
                                                value="{{ old('pro_sku', $data->pro_sku) }}" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="pro_img_thumbnail">Image</label>
                                            <input type="file" name="pro_img_thumbnail" id="pro_img_thumbnail"
                                                class="form-control p-1" accept="image/*">
                                            @if ($data->pro_img_thumbnail)
                                                <div class="mt-2">
                                                    <img src="{{ getImageStorage($data->pro_img_thumbnail) }}" class="img-thumbnail"
                                                        width="100px" alt="{{ $data->pro_name }}">
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="pro_price_regular">Price Regular <span class="text-danger">*</span></label>
                                            <input type="number" value="{{ old('pro_price_regular', $data->pro_price_regular) }}"
                                                name="pro_price_regular" id="pro_price_regular" class="form-control" 
                                                min="0" step="1000" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="pro_price_sale">Price Sale</label>
                                            <input type="number" value="{{ old('pro_price_sale', $data->pro_price_sale ?: '') }}" name="pro_price_sale"
                                                id="pro_price_sale" class="form-control" min="0" step="1000" placeholder="Để trống nếu không có giá khuyến mãi">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="pro_prating">Rating (0-10)</label>
                                            <input type="number" value="{{ old('pro_prating', $data->pro_prating) }}" name="pro_prating"
                                                id="pro_prating" class="form-control" min="0" max="10" step="0.1">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="pro_views">Views</label>
                                            <input type="number" value="{{ old('pro_views', $data->pro_views) }}" name="pro_views"
                                                id="pro_views" class="form-control" min="0">
                                        </div>
                                        
                                        <div class="form-group">
                                            <div class="form-check">
                                                <input type="checkbox" name="pro_featured" id="pro_featured"
                                                    class="form-check-input" value="1" {{ old('pro_featured', $data->pro_featured) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="pro_featured">Featured Product</label>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <div class="form-check">
                                                <input type="checkbox" name="is_hot" id="is_hot"
                                                    class="form-check-input" value="1" {{ old('is_hot', $data->is_hot) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_hot">Hot Product</label>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <div class="form-check">
                                                <input type="checkbox" name="is_active" id="is_active"
                                                    class="form-check-input" value="1" {{ old('is_active', $data->is_active) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_active">Active</label>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="pro_description">Description</label>
                                            <textarea name="pro_description" id="pro_description" class="form-control" rows="4">{{ old('pro_description', $data->pro_description) }}</textarea>
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
                                        <h4 class="card-title mb-0 flex-grow-1">Product Variants</h4>
                                        <small class="text-muted">Cập nhật giá cho từng biến thể sản phẩm</small>
                                    </div><!-- end card header -->
                                    <div class="card-body" style="height: 450px; overflow-y: scroll">
                                        <div class="live-preview">
                                            <div class="row gy-4">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered">
                                                        <tr class="text-center">
                                                            <th>Capacity</th>
                                                            <th>Color</th>
                                                            <th>Quantity</th>
                                                            <th>Price</th>
                                                            <th>Sale Price</th>
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

                                                                    <td style="width: 100px;">
                                                                        <div class="d-flex align-items-center justify-content-center">
                                                                            <div style="width: 30px; height: 30px; background: {{ $colorName }}; border: 1px solid #ccc; margin-right: 8px;"></div>
                                                                            <span>{{ $colorName }}</span>
                                                                        </div>
                                                                    </td>
                                                                    <td style="width: 120px;">
                                                                        @php($variant = $data->productVariants->where('color_id', $colorID)->where('capacity_id', $gbID)->first())
                                                                        <input type="number" class="form-control"
                                                                            value="{{ old('product_variants.' . $gbID . '-' . $colorID . '.quantity', $variant ? $variant->quantity : 0) }}" min="0"
                                                                            name="product_variants[{{ $gbID . '-' . $colorID }}][quantity]"
                                                                            placeholder="Số lượng">
                                                                    </td>
                                                                    <td style="width: 150px;">
                                                                        <input type="number" class="form-control"
                                                                            value="{{ old('product_variants.' . $gbID . '-' . $colorID . '.price', $variant && $variant->price ? $variant->price : '') }}" min="0" step="1000"
                                                                            name="product_variants[{{ $gbID . '-' . $colorID }}][price]"
                                                                            placeholder="Giá gốc" required>
                                                                    </td>
                                                                    <td style="width: 150px;">
                                                                        <input type="number" class="form-control"
                                                                            value="{{ old('product_variants.' . $gbID . '-' . $colorID . '.price_sale', $variant && $variant->price_sale ? $variant->price_sale : '') }}" min="0" step="1000"
                                                                            name="product_variants[{{ $gbID . '-' . $colorID }}][price_sale]"
                                                                            placeholder="Giá khuyến mãi">
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
                                        <h4 class="card-title mb-0 flex-grow-1">Gallery Image</h4>
                                        <p class="btn btn-primary" id="add_gallery_update">Thêm gallery</p>
                                    </div><!-- end card header -->
                                    <div id="body-gallery_update" class="card-body">

                                        @foreach ($data->galleries as $item)
                                            <div class="mb-3 d-flex align-items-center">
                                                @if ($item->image)
                                                    <label for="image_gallery_{{ $item->id }}" class="col-4 col-form-label">
                                                        <img src="{{ getImageStorage($item->image) }}" width="100px"
                                                            height="100px" alt="Gallery Image" class="img-thumbnail">
                                                    </label>
                                                @endif

                                                <input type="file" name="image_galleries[{{ $item->id }}]"
                                                    class="form-control p-1" id="image_gallery_{{ $item->id }}" accept="image/*">
                                            </div>
                                        @endforeach
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

@section('scripts')
<script>
    // Auto-generate slug from product name
    document.getElementById('pro_name').addEventListener('input', function() {
        const name = this.value;
        const slug = name.toLowerCase()
            .replace(/[^\w\s-]/g, '') // Remove special characters
            .replace(/\s+/g, '-') // Replace spaces with hyphens
            .replace(/-+/g, '-') // Replace multiple hyphens with single hyphen
            .trim();
        document.getElementById('pro_slug').value = slug;
    });

    // Validate sale price is less than regular price
    document.getElementById('pro_price_sale').addEventListener('input', function() {
        const regularPrice = parseFloat(document.getElementById('pro_price_regular').value) || 0;
        const salePrice = parseFloat(this.value) || 0;
        
        if (salePrice > regularPrice && regularPrice > 0) {
            alert('Giá khuyến mãi không được lớn hơn giá gốc!');
            this.value = '';
        }
    });

    // Validate variant sale prices
    document.addEventListener('input', function(e) {
        if (e.target.name && e.target.name.includes('[price_sale]')) {
            const row = e.target.closest('tr');
            const priceInput = row.querySelector('input[name*="[price]"]:not([name*="[price_sale]"])');
            const salePriceInput = e.target;
            
            const price = parseFloat(priceInput.value) || 0;
            const salePrice = parseFloat(salePriceInput.value) || 0;
            
            if (salePrice > price && price > 0) {
                alert('Giá khuyến mãi của biến thể không được lớn hơn giá gốc!');
                salePriceInput.value = '';
            }
        }
    });
</script>
@endsection
