<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoteCapacityRequest;
use App\Http\Requests\Admin\UpdateCapacityRequest;
use App\Models\Capacity;
use App\Services\CapacityService;
use Illuminate\Http\Request;

class CapacityController extends Controller
{
    protected $capacityService;
    public function __construct(
        CapacityService $capacityService
    ) {
        $this->capacityService = $capacityService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->capacityService->getCapacity();
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
            $this->capacityService->createCapacity($data);
            return redirect()->route('capacities.index')->with('success', 'Thao tác thành công !!!');
        } catch (\Throwable $th) {
            return back()->with('error', 'Thêm mới không thành công !!!');
        }
    }


    public function edit(string $id)
    {
        $data = $this->capacityService->findIdCapacity($id);
        return view('admin.capacities.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCapacityRequest $request, string $id)
    {
        try {
            $data = $request->all();
            $this->capacityService->updateCapacity($id, $data);
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
            $this->capacityService->deleteCapacity($id);
            return redirect()->route('capacities.index')->with('success', 'Thao tác thành công !!!');
        } catch (\Throwable $th) {
            return back()->with('error', 'Thao tác không thành công !!!');
        }
    }
}
