<?php

use App\Models\HeroSlide;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    private const SLIDES = [
        [
            'image_path' => 'images/Hero/hero.png',
            'alt' => 'Personalizēta Wish Gift dāvana ar QR video',
            'sort_order' => 0,
        ],
        [
            'image_path' => 'images/Hero/hero1.png',
            'alt' => 'Wish Gift hero kolekcijas foto',
            'sort_order' => 1,
        ],
        [
            'image_path' => 'images/Hero/hero2.png',
            'alt' => 'Wish Gift personalizētas dāvanas foto',
            'sort_order' => 2,
        ],
    ];

    public function up(): void
    {
        foreach (self::SLIDES as $slide) {
            if (! file_exists(public_path($slide['image_path']))) {
                continue;
            }

            HeroSlide::query()->firstOrCreate([
                'image_path' => $slide['image_path'],
                'disk' => 'asset',
            ], [
                'alt' => $slide['alt'],
                'sort_order' => $slide['sort_order'],
                'is_active' => true,
            ]);
        }
    }

    public function down(): void
    {
        HeroSlide::query()
            ->where('disk', 'asset')
            ->whereIn('image_path', array_column(self::SLIDES, 'image_path'))
            ->delete();
    }
};
