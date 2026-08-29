<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CatalogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request, CatalogService $catalog): JsonResponse
    {
        return response()->json($catalog->publicPayload($request->all()));
    }

    public function show(Product $product, Request $request): JsonResponse
    {
        return response()->json([
            'product' => $product->load(['categories', 'productImages'])
                ->localizedPayload($request->string('locale')->value() ?: 'lv'),
        ]);
    }
}
