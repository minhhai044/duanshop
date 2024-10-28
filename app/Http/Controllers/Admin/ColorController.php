<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreColorRequest;
use App\Http\Requests\Admin\UpdateColorRequest;
use App\Models\Color;
use App\Services\ColorService;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Return_;

class ColorController extends Controller
{
    protected $colorService;
    public function __construct(
        ColorService $colorService
    ) {
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
            $data = $request->all();
            $this->colorService->createColor($data);
            return redirect()->route('colors.index')->with('success', 'Thao tác thành công !!');
        } catch (\Throwable $th) {
            return back()->with('error', 'Thao tác không thành công !!');
        }
    }

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
            $data = $request->all();
            $this->colorService->updateColor($id, $data);
            return redirect()->route('colors.index')->with('success', 'Thao tác thành công !!');
        } catch (\Throwable $th) {
            return back()->with('error', 'Thao tác không thành công !!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->colorService->deleteColor($id);
            return back()->with('success', 'Thao tác thành công !!');
        } catch (\Throwable $th) {
            return back()->with('error', 'Thao tác không thành công !!');
        }
    }
}
