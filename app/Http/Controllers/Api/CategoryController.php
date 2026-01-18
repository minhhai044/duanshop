<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CategoryService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use ApiResponseTrait;
    protected $categoryService;
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        $categories = $this->categoryService->getCategory();
        return $this->successResponse($categories, 'Thao tác thành công.');
    }

    public function show(string $slug)
    {
        $category = $this->categoryService->findIdSlugCategory('', $slug, ['products'], 8);
        return $this->successResponse($category, 'Thao tác thành công.');
    }
}
