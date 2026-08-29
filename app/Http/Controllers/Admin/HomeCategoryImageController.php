<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\HomeCategoryBatchRequest;
use App\Http\Requests\Admin\HomeCategoryImageRequest;
use App\Http\Requests\Admin\HomeCategoryOrderRequest;
use App\Http\Requests\Admin\HomeCategorySettingsRequest;
use App\Models\HomeCategoryImage;
use App\Services\HomepageImageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class HomeCategoryImageController extends Controller
{
    public function update(HomeCategorySettingsRequest $request): JsonResponse
    {
        $data = $request->validated();
        $category = HomeCategoryImage::query()->firstOrNew([
            'category_key' => $data['category_key'],
        ]);

        $category->fill(collect($data)->except('category_key')->all());
        $category->save();

        return response()->json(['category_image' => $category->refresh()]);
    }

    public function upload(string $categoryKey, HomeCategoryImageRequest $request, HomepageImageService $images): JsonResponse
    {
        $category = HomeCategoryImage::query()->firstOrNew(['category_key' => $categoryKey]);
        $oldPath = $category->image_path;
        $oldDisk = $category->disk;

        $category->fill([
            'image_path' => $images->storeCategoryImage($request->file('image')),
            'disk' => 'public',
            'alt' => $request->string('alt')->trim()->value() ?: null,
            'is_active' => true,
        ])->save();

        $images->deleteManaged($oldPath, $oldDisk);

        return response()->json(['category_image' => $category->refresh()]);
    }

    public function order(HomeCategoryOrderRequest $request): JsonResponse
    {
        foreach ($request->validated('items') as $item) {
            HomeCategoryImage::query()->updateOrCreate(
                ['category_key' => $item['category_key']],
                ['sort_order' => $item['sort_order']],
            );
        }

        return response()->json([
            'category_images' => HomeCategoryImage::query()->orderBy('sort_order')->orderBy('id')->get(),
        ]);
    }

    public function batch(HomeCategoryBatchRequest $request, HomepageImageService $images): JsonResponse
    {
        $categories = collect(json_decode($request->string('categories')->value(), true) ?: []);
        $files = $request->file('files', []);

        DB::transaction(function () use ($categories, $files, $images): void {
            foreach ($categories as $index => $categoryData) {
                $categoryKey = $categoryData['category_key'] ?? null;

                if (! $categoryKey) {
                    continue;
                }

                $category = HomeCategoryImage::query()->firstOrNew(['category_key' => $categoryKey]);
                $payload = [
                    'alt' => $categoryData['alt'] ?? null,
                    'sort_order' => (int) ($categoryData['sort_order'] ?? $index),
                    'is_active' => (bool) ($categoryData['is_active'] ?? true),
                ];
                $oldPath = $category->image_path;
                $oldDisk = $category->disk;

                if (! empty($categoryData['delete_image'])) {
                    $payload['image_path'] = null;
                    $payload['disk'] = null;
                }

                $fileKey = $categoryData['file_key'] ?? null;

                if ($fileKey && isset($files[$fileKey])) {
                    $payload['image_path'] = $images->storeCategoryImage($files[$fileKey]);
                    $payload['disk'] = 'public';
                }

                $category->fill($payload)->save();

                if (! empty($payload['image_path']) && $payload['image_path'] === $oldPath) {
                    continue;
                }

                if ((! empty($categoryData['delete_image']) || $fileKey) && $oldPath) {
                    $images->deleteManaged($oldPath, $oldDisk);
                }
            }
        });

        return response()->json([
            'category_images' => HomeCategoryImage::query()->orderBy('sort_order')->orderBy('id')->get(),
        ]);
    }

    public function destroy(string $categoryKey, HomepageImageService $images): JsonResponse
    {
        $category = HomeCategoryImage::query()->where('category_key', $categoryKey)->firstOrFail();
        $oldPath = $category->image_path;
        $oldDisk = $category->disk;

        $category->update(['image_path' => null, 'disk' => null]);
        $images->deleteManaged($oldPath, $oldDisk);

        return response()->json(['category_image' => $category->refresh()]);
    }
}
