<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\HeroSlideBatchRequest;
use App\Http\Requests\Admin\HeroSlideOrderRequest;
use App\Http\Requests\Admin\HeroSlideStoreRequest;
use App\Http\Requests\Admin\HeroSlideUpdateRequest;
use App\Models\HeroSlide;
use App\Services\HomepageImageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class HeroSlideController extends Controller
{
    public function store(HeroSlideStoreRequest $request, HomepageImageService $images): JsonResponse
    {
        $nextOrder = (int) HeroSlide::query()->max('sort_order') + 1;
        $slides = collect($request->file('images'))->map(function ($image, int $index) use ($images, $request, $nextOrder) {
            return HeroSlide::query()->create([
                'image_path' => $images->storeHeroImage($image),
                'disk' => 'public',
                'alt' => $request->string('alt')->trim()->value() ?: 'Wish Gift hero image',
                'sort_order' => $nextOrder + $index,
                'is_active' => true,
            ]);
        });

        return response()->json(['hero_slides' => $slides], 201);
    }

    public function update(HeroSlideUpdateRequest $request, HeroSlide $heroSlide, HomepageImageService $images): JsonResponse
    {
        $data = $request->validated();
        $oldPath = null;

        if ($request->hasFile('image')) {
            $oldPath = $heroSlide->image_path;
            $oldDisk = $heroSlide->disk;
            $data['image_path'] = $images->storeHeroImage($request->file('image'));
            $data['disk'] = 'public';
        }

        $heroSlide->update($data);
        $images->deleteManaged($oldPath, $oldDisk ?? null);

        return response()->json(['hero_slide' => $heroSlide->refresh()]);
    }

    public function batch(HeroSlideBatchRequest $request, HomepageImageService $images): JsonResponse
    {
        $slides = collect(json_decode($request->string('slides')->value(), true) ?: []);
        $files = $request->file('files', []);

        DB::transaction(function () use ($slides, $files, $images): void {
            foreach ($slides as $index => $slideData) {
                $sortOrder = (int) ($slideData['sort_order'] ?? $index);

                if (! empty($slideData['id'])) {
                    $slide = HeroSlide::query()->findOrFail($slideData['id']);

                    if (! empty($slideData['delete'])) {
                        $oldPath = $slide->image_path;
                        $oldDisk = $slide->disk;
                        $slide->delete();
                        $images->deleteManaged($oldPath, $oldDisk);

                        continue;
                    }

                    $payload = [
                        'alt' => $slideData['alt'] ?? null,
                        'sort_order' => $sortOrder,
                        'is_active' => (bool) ($slideData['is_active'] ?? false),
                    ];
                    $fileKey = $slideData['file_key'] ?? null;

                    if ($fileKey && isset($files[$fileKey])) {
                        $oldPath = $slide->image_path;
                        $oldDisk = $slide->disk;
                        $payload['image_path'] = $images->storeHeroImage($files[$fileKey]);
                        $payload['disk'] = 'public';
                        $images->deleteManaged($oldPath, $oldDisk);
                    }

                    $slide->update($payload);

                    continue;
                }

                $fileKey = $slideData['file_key'] ?? null;

                if (! $fileKey || ! isset($files[$fileKey])) {
                    continue;
                }

                HeroSlide::query()->create([
                    'image_path' => $images->storeHeroImage($files[$fileKey]),
                    'disk' => 'public',
                    'alt' => $slideData['alt'] ?? 'Wish Gift hero image',
                    'sort_order' => $sortOrder,
                    'is_active' => (bool) ($slideData['is_active'] ?? true),
                ]);
            }
        });

        return response()->json([
            'hero_slides' => HeroSlide::query()->orderBy('sort_order')->orderBy('id')->get(),
        ]);
    }

    public function order(HeroSlideOrderRequest $request): JsonResponse
    {
        foreach ($request->validated('items') as $item) {
            HeroSlide::query()
                ->whereKey($item['id'])
                ->update(['sort_order' => $item['sort_order']]);
        }

        return response()->json([
            'hero_slides' => HeroSlide::query()->orderBy('sort_order')->orderBy('id')->get(),
        ]);
    }

    public function destroy(HeroSlide $heroSlide, HomepageImageService $images): JsonResponse
    {
        $path = $heroSlide->image_path;
        $disk = $heroSlide->disk;
        $heroSlide->delete();
        $images->deleteManaged($path, $disk);

        return response()->json(['message' => 'Hero image deleted.']);
    }
}
