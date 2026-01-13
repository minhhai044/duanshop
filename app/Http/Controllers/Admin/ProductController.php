<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\ProductVariant;
use App\Services\CapacityService;
use App\Services\CategoryService;
use App\Services\ColorService;
use App\Services\GalleryService;
use App\Services\ProductService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    protected $productService;
    protected $categoryService;
    protected $colorService;
    protected $capacityService;
    protected $galleryService;
    public function __construct(
        ProductService $productService,
        CategoryService $categoryService,
        ColorService $colorService,
        CapacityService $capacityService,
        GalleryService $galleryService
    ) {
        $this->productService = $productService;
        $this->categoryService = $categoryService;
        $this->colorService = $colorService;
        $this->capacityService = $capacityService;
        $this->galleryService = $galleryService;
    }
    public function index()
    {
        $products = Cache::rememberForever('products',function (){
            return $this->productService->getProduct(['category']);
        });

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories =   $this->categoryService->pluckCategory('cate_name', 'id');
        $colors     =   $this->colorService->pluckColor('color_name', 'id');
        $capacities =   $this->capacityService->pluckCapacity('cap_name', 'id');

        return view('admin.products.create', compact(['categories', 'colors', 'capacities']));
    }
    /**
     * Store a newly created resource in storage.
     */
    private function ProductLogicRequest($request)
    {
        //dataProduct
        $dataProduct = $request->except(['product_variants', 'image_galleries', 'add_galleries']);

        // Tự động tạo slug nếu không có
        if (empty($dataProduct['pro_slug'])) {
            $dataProduct['pro_slug'] = generateSlug($dataProduct['pro_name']);
        }

        // Set default values and handle null/empty values
        $dataProduct['pro_featured'] = $dataProduct['pro_featured'] ?? false;
        $dataProduct['is_hot'] = $dataProduct['is_hot'] ?? false;
        $dataProduct['is_active'] = $dataProduct['is_active'] ?? true;
        $dataProduct['pro_views'] = $dataProduct['pro_views'] ?? 0;
        $dataProduct['pro_prating'] = $dataProduct['pro_prating'] ?? 0;
        
        // Handle price_sale - convert empty string to 0
        $dataProduct['pro_price_sale'] = !empty($dataProduct['pro_price_sale']) ? $dataProduct['pro_price_sale'] : 0;
        
        // Ensure price_regular is not empty
        $dataProduct['pro_price_regular'] = !empty($dataProduct['pro_price_regular']) ? $dataProduct['pro_price_regular'] : 0;

        if ($request->hasFile('pro_img_thumbnail')) {
            $dataProduct['pro_img_thumbnail'] = createImageStorage('products', $request->file('pro_img_thumbnail'));
        }

        //Variant
        $dataProductVariantsTmp = $request->product_variants;
        $dataProductVariants = [];

        foreach ($dataProductVariantsTmp as $key => $value) {
            $tmp = explode('-', $key);
            
            // Only create variant if it has meaningful data (quantity > 0 or price > 0)
            if ((!empty($value['quantity']) && $value['quantity'] > 0) || 
                (!empty($value['price']) && $value['price'] > 0)) {
                
                $dataProductVariants[] = [
                    'capacity_id' => $tmp[0],
                    'color_id' => $tmp[1],
                    'quantity' => $value['quantity'] ?? 0,
                    'price' => !empty($value['price']) ? $value['price'] : 0,
                    'price_sale' => !empty($value['price_sale']) ? $value['price_sale'] : 0,
                ];
            }
        }

        //Galleries
        $dataProductGalleriesTmp = $request->image_galleries ?? [];
        $dataProductGalleries = [];

        foreach ($dataProductGalleriesTmp as $key => $image) {
            if (!empty($image)) {
                $dataProductGalleries[$key] = createImageStorage('galleries', $image);
            }
        }

        // Thêm gallery khi update
        $dataGalleriesUpdateTmp = $request->add_galleries ?? [];
        $dataGalleriesUpdate = [];

        foreach ($dataGalleriesUpdateTmp as $image) {
            if (!empty($image)) {
                $dataGalleriesUpdate[] = createImageStorage('galleries', $image);
            }
        }
        
        return [$dataProduct, $dataProductVariants, $dataProductGalleries, $dataGalleriesUpdate];
    }
    public function store(StoreProductRequest $request)
    {
        // Lay data

        try {
            list(
                $dataProduct,
                $dataProductVariants,
                $dataProductGalleries
            ) = $this->ProductLogicRequest($request);

            DB::transaction(function () use ($dataProduct, $dataProductVariants, $dataProductGalleries) {
                $product = $this->productService->createProduct($dataProduct);

                foreach ($dataProductVariants as $item) {
                    $product->productVariants()->create($item);
                }

                foreach ($dataProductGalleries as $item) {
                    $product->galleries()->create(['image' => $item]);
                }
                Cache::forget('products');
            });
            
            return redirect()
                ->route('products.index')
                ->with('success', 'Thao tác thành công!');
        } catch (\Throwable $th) {
            if (!empty($dataProduct['pro_img_thumbnail'])) {
                deleteImageStorage($dataProduct['pro_img_thumbnail']);
            }

            foreach ($dataProductGalleries as $item) {
                if (!empty($item)) {
                    deleteImageStorage($item);
                }
            }
            Log::error(__CLASS__ . '@' . __FUNCTION__, context: [$th->getMessage()]);
            return back()->with('error', 'Thêm mới không thành công !!!');
        }
    }
    public function edit(string $id)
    {
        $data       =   $this->productService->findIDRelationProduct($id, ['productVariants']);

        $categories =   $this->categoryService->pluckCategory('cate_name', 'id');
        $colors     =   $this->colorService->pluckColor('color_name', 'id');
        $capacities =   $this->capacityService->pluckCapacity('cap_name', 'id');

        return view('admin.products.edit', compact(
            'data',
            'categories',
            'colors',
            'capacities'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, string $id)
    {
        try {
            // Lay data
            list(
                $dataProduct,
                $dataProductVariants,
                $dataProductGalleries,
                $dataGalleriesUpdate
            ) = $this->ProductLogicRequest($request);

            DB::transaction(function () use ($dataProduct, $dataProductVariants, $dataProductGalleries, $id, $dataGalleriesUpdate) {
                $product = $this->productService->findIDProduct($id);

                if (!empty($dataProduct['pro_img_thumbnail']) && $product->pro_img_thumbnail) {
                    deleteImageStorage($product->pro_img_thumbnail);
                }

                $product->update($dataProduct);

                foreach ($dataProductVariants as $item) {
                    // Use updateOrCreate to handle the composite unique constraint
                    ProductVariant::updateOrCreate(
                        [
                            'product_id' => $id,
                            'capacity_id' => $item['capacity_id'],
                            'color_id' => $item['color_id']
                        ],
                        [
                            'quantity' => $item['quantity'],
                            'price' => $item['price'],
                            'price_sale' => $item['price_sale']
                        ]
                    );
                }

                foreach ($dataProductGalleries ?? [] as $key => $image) {
                    $gallery = $this->galleryService->findIdGallery($key);

                    if (!empty($image) && $gallery->image) {
                        deleteImageStorage($gallery->image);
                    }

                    $product->galleries()->where('id', $key)->update(['image' => $image]);
                }
                
                if (!empty($dataGalleriesUpdate)) {
                    foreach ($dataGalleriesUpdate ?? [] as $item) {
                        $product->galleries()->create(['image' => $item]);
                    }
                }
                Cache::forget('products');
            });
            
            return redirect()
                ->route('products.index')
                ->with('success', 'Thao tác thành công!');
        } catch (\Throwable $th) {
            if (!empty($dataProduct['pro_img_thumbnail'])) {
                deleteImageStorage($dataProduct['pro_img_thumbnail']);
            }
            foreach ($dataProductGalleries as $item) {
                if (!empty($item)) {
                    deleteImageStorage($item);
                }
            }
            foreach ($dataGalleriesUpdate as $item) {
                if (!empty($item)) {
                    deleteImageStorage($item);
                }
            }
            Log::error(__CLASS__ . '@' . __FUNCTION__, [$th->getMessage()]);

            return back()->with('error', 'Update không thành công !!!');
        }
    }

    /**
     * Toggle the active status of the specified product.
     */
    public function toggleStatus(string $id)
    {
        try {
            $product = $this->productService->findIDProduct($id);
            $product->update(['is_active' => !$product->is_active]);
            
            $status = $product->is_active ? 'kích hoạt' : 'vô hiệu hóa';
            Cache::forget('products');
            
            return redirect()->route('products.index')->with('success', "Đã {$status} sản phẩm thành công!");
        } catch (\Throwable $th) {
            return redirect()->route('products.index')->with('error', 'Thao tác không thành công!');
        }
    }
}
