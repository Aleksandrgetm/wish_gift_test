<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductImageOrderRequest;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Product;
use App\Services\CatalogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request, CatalogService $catalog): JsonResponse
    {
        return response()->json($catalog->adminProducts($request->all()));
    }

    public function store(ProductRequest $request, CatalogService $catalog): JsonResponse
    {
        [$data, $categoryIds] = $this->validatedPayload($request, $catalog);
        $product = $catalog->storeProduct($data, $categoryIds, $request->file('images', []));

        return response()->json(['product' => $product->localizedPayload('lv')], 201);
    }

    public function show(Product $product): JsonResponse
    {
        return response()->json([
            'product' => $product->load(['categories', 'productImages'])->localizedPayload('lv'),
        ]);
    }

    public function update(ProductRequest $request, Product $product, CatalogService $catalog): JsonResponse
    {
        [$data, $categoryIds] = $this->validatedPayload($request, $catalog, $product);
        $product = $catalog->updateProduct(
            $product,
            $data,
            $categoryIds,
            $request->file('images', []),
            $request->validated('delete_image_ids', []),
        );

        return response()->json(['product' => $product->localizedPayload('lv')]);
    }

    public function destroy(Product $product, CatalogService $catalog): JsonResponse
    {
        $catalog->deleteProduct($product);

        return response()->json(['message' => 'Product deleted.']);
    }

    public function imageOrder(ProductImageOrderRequest $request, Product $product, CatalogService $catalog): JsonResponse
    {
        return response()->json([
            'product' => $catalog->syncProductImageOrder($product, $request->validated('items'))->localizedPayload('lv'),
        ]);
    }

    private function validatedPayload(ProductRequest $request, CatalogService $catalog, ?Product $product = null): array
    {
        $validated = $request->validated();
        $categoryIds = $validated['category_ids'];
        $data = collect($validated)->except(['category_ids', 'images', 'delete_image_ids'])->all();
        $data['slug'] = $catalog->uniqueProductSlug($data['name_lv'], $product);

        return [$data, $categoryIds];
    }
}
