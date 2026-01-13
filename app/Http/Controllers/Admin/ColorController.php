<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreColorRequest;
use App\Http\Requests\Admin\UpdateColorRequest;
use App\Services\ColorService;

class ColorController extends Controller
{
    protected $colorService;
    public function __construct(ColorService $colorService)
    {
        $this->colorService = $colorService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->colorService->getColor();
        return view('admin.colors.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.colors.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreColorRequest $request)
    {
        try {
            $data = $request->validated();
            
            // Tự động tạo slug nếu không có
            if (empty($data['slug'])) {
                $data['slug'] = generateSlug($data['color_name']);
            }
            
            // Set default is_active
            $data['is_active'] = $data['is_active'] ?? true;
            
            $this->colorService->createColor($data);
            return redirect()->route('colors.index')->with('success', 'Thao tác thành công !!');
        } catch (\Throwable $th) {
            return back()->with('error', 'Thao tác không thành công !!');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = $this->colorService->findIdColor($id);
        return view('admin.colors.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateColorRequest $request, string $id)
    {
        try {
            $data = $request->validated();
            $color = $this->colorService->findIdColor($id);
            
            // Tự động tạo slug nếu không có
            if (empty($data['slug'])) {
                $data['slug'] = generateSlug($data['color_name']);
            }
            
            // Set default is_active
            $data['is_active'] = $data['is_active'] ?? $color->is_active;
            
            $this->colorService->updateColor($id, $data);
            return redirect()->route('colors.index')->with('success', 'Thao tác thành công !!');
        } catch (\Throwable $th) {
            return back()->with('error', 'Thao tác không thành công !!');
        }
    }
}
