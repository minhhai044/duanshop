<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CapacityRequest;
use App\Services\CapacityService;

class CapacityController extends Controller
{
    protected $capacityService;
    public function __construct(CapacityService $capacityService)
    {
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
    public function store(CapacityRequest $request)
    {
        try {
            $this->capacityService->createCapacity($request->validated());
            return redirect()->route('capacities.index')->with('success', 'Thao tác thành công !!!');
        } catch (\Throwable $th) {
            return back()->with('error', 'Thêm mới không thành công !!!');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = $this->capacityService->findIdCapacity($id);
        return view('admin.capacities.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CapacityRequest $request, string $id)
    {
        try {
            $this->capacityService->updateCapacity($id, $request->validated());
            return redirect()->route('capacities.index')->with('success', 'Thao tác thành công !!!');
        } catch (\Throwable $th) {
            return back()->with('error', 'Thao tác không thành công !!!');
        }
    }
}
