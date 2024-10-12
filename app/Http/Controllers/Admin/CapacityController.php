<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoteCapacityRequest;
use App\Http\Requests\Admin\UpdateCapacityRequest;
use App\Models\Capacity;
use Illuminate\Http\Request;

class CapacityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Capacity::query()->latest('id')->get();
        return view('admin.capacities.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.capacities.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoteCapacityRequest $request)
    {
        try {
            $data = $request->all();
            Capacity::query()->create($data);
            return redirect()->route('capacities.index')->with('success', 'Thao tác thành công !!!');
        } catch (\Throwable $th) {
            return back()->with('error', 'Thêm mới không thành công !!!');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = Capacity::query()->find($id);
       return view('admin.capacities.edit',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCapacityRequest $request, string $id)
    {
        try {
            $data = $request->all();
        $Capacity = Capacity::query()->find($id);

        $Capacity->update($data);
            return redirect()->route('capacities.index')->with('success', 'Thao tác thành công !!!');
        } catch (\Throwable $th) {
            return back()->with('error', 'Thao tác không thành công !!!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
        $Capacity = Capacity::query()->find($id);

        $Capacity->delete();
            return redirect()->route('capacities.index')->with('success', 'Thao tác thành công !!!');
        } catch (\Throwable $th) {
            return back()->with('error', 'Thao tác không thành công !!!');
        }
    }
}
