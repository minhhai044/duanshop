<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreColorRequest;
use App\Http\Requests\Admin\UpdateColorRequest;
use App\Models\Color;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Return_;

class ColorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Color::query()->get();
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
            Color::query()->create($data);
            return redirect()->route('colors.index')->with('success', 'Thao tác thành công !!');
        } catch (\Throwable $th) {
            return back()->with('error', 'Thao tác không thành công !!');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

        $data = Color::query()->findOrFail($id);
        return view('admin.colors.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateColorRequest $request, string $id)
    {
        // dd($request);
        try {
            $color = Color::query()->findOrFail($id);
            $data = $request->all();
            $color->update($data);
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
            $color = Color::query()->findOrFail($id);

            $color->delete();
            return back()->with('success', 'Thao tác thành công !!');
        } catch (\Throwable $th) {
            return back()->with('error', 'Thao tác không thành công !!');
        }
    }
}
