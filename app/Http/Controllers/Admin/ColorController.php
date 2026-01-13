<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ColorRequest;
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
    public function store(ColorRequest $request)
    {
        try {
            $this->colorService->createColor($request->validated());
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
    public function update(ColorRequest $request, string $id)
    {
        try {
            $this->colorService->updateColor($id, $request->validated());
            return redirect()->route('colors.index')->with('success', 'Thao tác thành công !!');
        } catch (\Throwable $th) {
            return back()->with('error', 'Thao tác không thành công !!');
        }
    }
}
