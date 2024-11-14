<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class SearchProductController extends Controller
{
    public function search(Request $request)
    {
        try {
            $key = $request->query('search');
            if (!$key) {
                return response()->json([
                    'messenge' => 'Vui lòng nhập thông tin !!!',
                    'data' => []
                ], Response::HTTP_BAD_REQUEST);
            }
            $data = Product::query()->whereAny(['pro_name', 'pro_sku'], 'LIKE', "%$key%")->get();
            return response()->json([
                'data' => $data
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error(__CLASS__ . '@' . __FUNCTION__, [$th->getMessage()]);
            return response()->json([
                'messenge' => 'Lỗi hệ thống !!!'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
