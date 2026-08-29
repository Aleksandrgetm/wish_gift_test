<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSlide;
use App\Models\HomeCategoryImage;
use Illuminate\Http\JsonResponse;

class HomepageController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'hero_slides' => HeroSlide::query()
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get(),
            'category_images' => HomeCategoryImage::query()
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get(),
        ]);
    }
}
