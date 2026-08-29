<?php

namespace App\Http\Controllers;

use App\Models\HeroSlide;
use App\Models\HomeCategoryImage;
use Illuminate\Http\JsonResponse;

class HomeContentController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'hero_slides' => HeroSlide::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get(),
            'category_images' => HomeCategoryImage::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get(),
        ]);
    }
}
