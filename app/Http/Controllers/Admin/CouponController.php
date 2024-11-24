<?php

namespace App\Http\Controllers\Admin;

use App\Events\CouponEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCouponRequest;
use App\Http\Requests\UpdateCouponRequest;
use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Coupons = Coupon::query()->latest('id')->get();
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
                //Sự kiện
                broadcast(new CouponEvent($Coupon));
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
    public function show(string $id)
    {
        try {
            $data = Coupon::with('products')->findOrFail($id);
            return view('admin.coupons.show', compact('data'));
        } catch (\Throwable $th) {
            return back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $data = Coupon::query()->findOrFail($id);
            $products = Product::query()->pluck('pro_name', 'id')->all();
            $inProduct = $data->products()->pluck('id')->all();
            return view('admin.coupons.edit', compact('data', 'products', 'inProduct'));
        } catch (\Throwable $th) {
            return back();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCouponRequest $request, string $id)
    {
        try {
            $Coupon = Coupon::query()->findOrFail($id);
            $dataCoupon = $request->except('product_id');
            $products = $request->product_id;
            DB::transaction(function () use ($Coupon, $dataCoupon, $products) {
                $Coupon->update($dataCoupon);
                $Coupon->products()->sync($products);
            });
            return redirect()->route('coupons.index')->with('success', 'Update thành công !!!');
        } catch (\Throwable $th) {
            Log::debug(__CLASS__ . '@' . __FUNCTION__, [$th->getMessage()]);
            return back()->with('error', 'Update không thành công !!!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $Coupon = Coupon::query()->findOrFail($id);

            DB::transaction(function () use ($Coupon) {

                $Coupon->products()->sync([]);
                $Coupon->delete();
            });
            return redirect()->route('coupons.index')->with('success', 'Delete thành công !!!');
        } catch (\Throwable $th) {
            Log::debug(__CLASS__ . '@' . __FUNCTION__, [$th->getMessage()]);
            return back()->with('error', 'Delete không thành công !!!');
        }
    }
}
