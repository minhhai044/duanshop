<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCouponRequest;
use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Coupons = Coupon::query()->latest('id')->paginate(10);
        return view('admin.coupons.index', compact('Coupons'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::query()->pluck('pro_name', 'id')->all();
        return view('admin.coupons.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCouponRequest $request)
    {
        try {
            $dataCoupon = $request->except('product_id');
            $products = $request->product_id;
            DB::transaction(function () use ($dataCoupon, $products) {
                $Coupon = Coupon::query()->create($dataCoupon);
                $Coupon->products()->attach($products);
            });
            return redirect()->route('coupons.index')->with('success', 'Thêm mới thành công !!!');
        } catch (\Throwable $th) {
            Log::debug(__CLASS__ . '@' . __FUNCTION__, [$th->getMessage()]);
            return back()->with('error', 'Thêm mới không thành công !!!');
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
