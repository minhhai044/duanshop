<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAddCartRequest;
use App\Models\Capacity;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class GeneralController extends Controller
{
    public function index()
    {
        $products = Product::query()->where('pro_featured', 1)->latest('id')->limit(3)->get();
        return view('client.index', compact('products'));
    }
    public function shop()
    {
        $products = Product::query()->latest('id')->paginate(8);
        return view('client.shop', compact('products'));
    }

    public function detail(string $id)
    {
        $dataDetails = Product::with(
            'category',
            'galleries',
            'tags',
            'product_variant'
        )->find($id);
        $colors = Color::query()->get();
        $Capacities = Capacity::query()->get();
        $variants = ProductVariant::with('capacity', 'color')->where('product_id', $id)->get();
        // dd($variants->toArray());
        return view('client.detail', compact(
            'dataDetails',
            'variants',
            'colors',
            'Capacities'
        ));
    }
    public function addcart(StoreAddCartRequest $request) {
        dd($request);
    }



















    public function about()
    {
        return view('client.about');
    }
    public function blog()
    {
        return view('client.blog');
    }
    public function cart()
    {
        return view('client.cart');
    }
    public function checkout()
    {
        return view('client.checkout');
    }
    public function contact()
    {
        return view('client.contact');
    }
    public function services()
    {
        return view('client.services');
    }
    public function thankyou()
    {
        return view('client.thankyou');
    }
}
