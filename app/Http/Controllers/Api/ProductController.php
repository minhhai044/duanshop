<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ProductService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

/**
 * @group Products
 * 
 * APIs for managing products
 */
class ProductController extends Controller
{
    use ApiResponseTrait;
    protected $productService;
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Get Products List
     *
     * Retrieve paginated list of products
     * 
     * @queryParam page integer Page number for pagination. Example: 1
     * @queryParam per_page integer Number of items per page. Example: 10
     * @queryParam category_id integer Filter by category ID. Example: 1
     * @queryParam min_price number Filter by minimum price. Example: 100000
     * @queryParam max_price number Filter by maximum price. Example: 500000
     * 
     * @response 200 {
     *   "status": true,
     *   "message": "Lấy danh sách sản phẩm thành công",
     *   "data": {
     *     "current_page": 1,
     *     "data": [
     *       {
     *         "id": 1,
     *         "name": "iPhone 15 Pro",
     *         "slug": "iphone-15-pro",
     *         "price": 25000000,
     *         "image": "product1.jpg"
     *       }
     *     ],
     *     "total": 50,
     *     "per_page": 4
     *   }
     * }
     */
    public function index(Request $request)
    {
        $products = $this->productService->getAllProduct(4, $request->all(), [], true);
        return $this->successResponse($products, 'Lấy danh sách sản phẩm thành công', 200);
    }

    /**
     * Search Products
     *
     * Search products by keyword
     * 
     * @queryParam keyword string Search keyword. Example: iPhone
     * 
     * @response 200 {
     *   "status": true,
     *   "message": "Tìm kiếm sản phẩm thành công",
     *   "data": [
     *     {
     *       "id": 1,
     *       "name": "iPhone 15 Pro",
     *       "slug": "iphone-15-pro",
     *       "price": 25000000,
     *       "image": "product1.jpg"
     *     }
     *   ]
     * }
     */
    public function productSearch(Request $request)
    {
        $products = $this->productService->searchProducts($request->keyword ?? '');
        return $this->successResponse($products, 'Tìm kiếm sản phẩm thành công', 200);
    }

    /**
     * Get Product Details
     *
     * Get detailed information of a specific product
     * 
     * @urlParam slug string required Product slug. Example: iphone-15-pro
     * 
     * @response 200 {
     *   "status": true,
     *   "message": "Lấy chi tiết sản phẩm thành công",
     *   "data": {
     *     "id": 1,
     *     "name": "iPhone 15 Pro",
     *     "slug": "iphone-15-pro",
     *     "description": "Latest iPhone model",
     *     "price": 25000000,
     *     "category": {
     *       "id": 1,
     *       "name": "Smartphones"
     *     },
     *     "productVariants": [
     *       {
     *         "id": 1,
     *         "price": 25000000,
     *         "color": {"name": "Black"},
     *         "capacity": {"name": "128GB"}
     *       }
     *     ],
     *     "galleries": [
     *       {"image": "product1.jpg"}
     *     ]
     *   }
     * }
     * 
     * @response 404 {
     *   "status": false,
     *   "message": "Sản phẩm không tồn tại"
     * }
     */
    public function show(string $slug)
    {
        $product = $this->productService->findProduct(null, $slug, ['category', 'productVariants.color', 'productVariants.capacity', 'galleries']);
        return $this->successResponse($product, 'Lấy chi tiết sản phẩm thành công', 200);
    }
}
