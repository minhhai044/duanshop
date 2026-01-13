<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Services\CapacityService;
use App\Services\CategoryService;
use App\Services\ColorService;
use App\Services\GalleryService;
use App\Services\ProductService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

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
    public function store(ProductRequest $request)
    {
        try {
            $result = $this->productService->storeProduct($request->validated(), $request);
            
            return redirect()
                ->route('products.index')
                ->with('success', 'Thao tác thành công!');
        } catch (\Throwable $th) {
            Log::error(__CLASS__ . '@' . __FUNCTION__, [$th->getMessage()]);
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
    public function update(ProductRequest $request, string $id)
    {
        try {
            $result = $this->productService->updateProductWithVariantsAndGalleries($id, $request->validated(), $request);
            
            return redirect()
                ->route('products.index')
                ->with('success', 'Thao tác thành công!');
        } catch (\Throwable $th) {
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
            $product = $this->productService->toggleStatus($id);
            $status = $product->is_active ? 'kích hoạt' : 'vô hiệu hóa';
            
            return redirect()->route('products.index')->with('success', "Đã {$status} sản phẩm thành công!");
        } catch (\Throwable $th) {
            return redirect()->route('products.index')->with('error', 'Thao tác không thành công!');
        }
    }
}
