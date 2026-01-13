<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Http\Requests\Admin\UpdateCategoryRequest;
use App\Services\CategoryService;

class CategoryController extends Controller
{
    protected $CategoryService;
    public function __construct(CategoryService $categoryService)
    {
        $this->CategoryService = $categoryService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->CategoryService->getCategory();
        return view('admin.categories.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        try {
            $data = $request->validated();
            
            // Tự động tạo slug nếu không có
            if (empty($data['slug'])) {
                $data['slug'] = generateSlug($data['cate_name']);
            }
            
            // Xử lý upload image
            if ($request->hasFile('cate_image')) {
                $data['cate_image'] = createImageStorage('categories', $request->file('cate_image'));
            }
            
            // Set default is_active
            $data['is_active'] = $data['is_active'] ?? true;
            
            $this->CategoryService->createCategory($data);
            return redirect()->route('categories.index')->with('success', 'Thao tác thành công !!!');
        } catch (\Throwable $th) {
            return back()->with('error', 'Thêm mới không thành công !!!');
        }
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = $this->CategoryService->findIdCategory($id);
        return view('admin.categories.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, string $id)
    {
        try {
            $data = $request->validated();
            $category = $this->CategoryService->findIdCategory($id);
            
            // Tự động tạo slug nếu không có
            if (empty($data['slug'])) {
                $data['slug'] = generateSlug($data['cate_name']);
            }
            
            // Xử lý upload image
            if ($request->hasFile('cate_image')) {
                // Xóa ảnh cũ nếu có
                if ($category->cate_image) {
                    deleteImageStorage($category->cate_image);
                }
                $data['cate_image'] = createImageStorage('categories', $request->file('cate_image'));
            }
            
            // Set default is_active
            $data['is_active'] = $data['is_active'] ?? $category->is_active;
            
            $this->CategoryService->updateCategory($id, $data);
            return redirect()->route('categories.index')->with('success', 'Thao tác thành công !!!');
        } catch (\Throwable $th) {
            return back()->with('error', 'Thao tác không thành công !!!');
        }
    }
}
