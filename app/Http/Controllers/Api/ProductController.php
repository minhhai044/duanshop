<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ProductService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use ApiResponseTrait;
    protected $productService;
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }
    public function index(Request $request)
    {
        $products = $this->productService->getAllProduct(4, $request->all(), [], true);
        return $this->successResponse($products, 'Lấy danh sách sản phẩm thành công', 200);
    }

    public function productSearch(Request $request)
    {
        $products = $this->productService->searchProducts($request->keyword ?? '');
        return $this->successResponse($products, 'Tìm kiếm sản phẩm thành công', 200);
    }

    public function show(string $slug){
        $product = $this->productService->findProduct(null, $slug, ['category', 'productVariants.color', 'productVariants.capacity', 'galleries']);
        return $this->successResponse($product, 'Lấy chi tiết sản phẩm thành công', 200);
    }
}
