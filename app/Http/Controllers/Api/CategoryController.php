<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CategoryService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

/**
 * @group Categories
 * 
 * APIs for managing product categories
 */
class CategoryController extends Controller
{
    use ApiResponseTrait;
    protected $categoryService;
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * Get Categories List
     *
     * Retrieve all product categories
     * 
     * @response 200 {
     *   "status": true,
     *   "message": "Thao tác thành công.",
     *   "data": [
     *     {
     *       "id": 1,
     *       "name": "Smartphones",
     *       "slug": "smartphones",
     *       "image": "category1.jpg",
     *       "description": "Mobile phones and accessories"
     *     },
     *     {
     *       "id": 2,
     *       "name": "Laptops",
     *       "slug": "laptops",
     *       "image": "category2.jpg",
     *       "description": "Computers and laptops"
     *     }
     *   ]
     * }
     */
    public function index()
    {
        $categories = $this->categoryService->getCategory();
        return $this->successResponse($categories, 'Thao tác thành công.');
    }

    /**
     * Get Category Details
     *
     * Get detailed information of a specific category with its products
     * 
     * @urlParam slug string required Category slug. Example: smartphones
     * 
     * @response 200 {
     *   "status": true,
     *   "message": "Thao tác thành công.",
     *   "data": {
     *     "id": 1,
     *     "name": "Smartphones",
     *     "slug": "smartphones",
     *     "image": "category1.jpg",
     *     "description": "Mobile phones and accessories",
     *     "products": {
     *       "current_page": 1,
     *       "data": [
     *         {
     *           "id": 1,
     *           "name": "iPhone 15 Pro",
     *           "slug": "iphone-15-pro",
     *           "price": 25000000,
     *           "image": "product1.jpg"
     *         }
     *       ],
     *       "total": 10,
     *       "per_page": 4
     *     }
     *   }
     * }
     * 
     * @response 404 {
     *   "status": false,
     *   "message": "Danh mục không tồn tại"
     * }
     */
    public function show(string $slug)
    {
        $category = $this->categoryService->findIdSlugCategory('', $slug, ['products'], 4);
        return $this->successResponse($category, 'Thao tác thành công.');
    }
}
