<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CatalogCategoryRequest;
use App\Models\CatalogCategory;
use App\Services\CatalogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CatalogCategoryController extends Controller
{
    public function index(Request $request, CatalogService $catalog): JsonResponse
    {
        return response()->json([
            'category_groups' => $catalog->formattedCategories(
                $catalog->categoryGroups(admin: true),
                $request->string('locale')->value() ?: 'lv',
            ),
        ]);
    }

    public function store(CatalogCategoryRequest $request, CatalogService $catalog): JsonResponse
    {
        $data = collect($request->validated())->except(['image', 'delete_image'])->all();
        $category = $catalog->storeCategory($data, $request->file('image'));

        return response()->json(['category' => $category->loadCount('products')], 201);
    }

    public function update(CatalogCategoryRequest $request, CatalogCategory $catalogCategory, CatalogService $catalog): JsonResponse
    {
        $data = collect($request->validated())->except(['image', 'delete_image'])->all();
        $category = $catalog->updateCategory(
            $catalogCategory,
            $data,
            $request->file('image'),
            $request->boolean('delete_image'),
        );

        return response()->json(['category' => $category]);
    }

    public function destroy(CatalogCategory $catalogCategory, CatalogService $catalog): JsonResponse
    {
        $catalog->deleteCategory($catalogCategory);

        return response()->json(['message' => 'Category deleted.']);
    }
}
