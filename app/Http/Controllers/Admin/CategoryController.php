<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
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
    public function store(CategoryRequest $request)
    {
        try {
            $this->CategoryService->createCategoryWithImage($request->validated(), $request);
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
        $data = $this->CategoryService->findIdSlugCategory($id);
        return view('admin.categories.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, string $id)
    {
        try {
            $this->CategoryService->updateCategoryWithImage($id, $request->validated(), $request);
            return redirect()->route('categories.index')->with('success', 'Thao tác thành công !!!');
        } catch (\Throwable $th) {
            return back()->with('error', 'Thao tác không thành công !!!');
        }
    }
}
