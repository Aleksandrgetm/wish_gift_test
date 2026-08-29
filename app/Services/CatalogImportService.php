<?php

namespace App\Services;

use App\Models\CatalogCategory;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CatalogImportService
{
    private const TYPE_BY_GROUP = [
        'occasions' => 'occasion',
        'categories' => 'category',
        'materials' => 'material',
    ];

    private const CATEGORY_TRANSLATIONS = [
        'Ziemassvētki' => ['en' => 'Christmas', 'ru' => 'Рождество'],
        'Jaunais gads' => ['en' => 'New Year', 'ru' => 'Новый год'],
        '14. februāris' => ['en' => 'February 14', 'ru' => '14 февраля'],
        '8. marts' => ['en' => 'March 8', 'ru' => '8 марта'],
        'Mātes diena' => ['en' => "Mother's Day", 'ru' => 'День матери'],
        'Skolotāju diena' => ['en' => "Teacher's Day", 'ru' => 'День учителя'],
        'Studentu diena' => ['en' => "Students' Day", 'ru' => 'День студента'],
        '1. septembris' => ['en' => 'September 1', 'ru' => '1 сентября'],
        'Tēva diena' => ['en' => "Father's Day", 'ru' => 'День отца'],
        'Dzimšanas diena' => ['en' => 'Birthday', 'ru' => 'День рождения'],
        'Citi' => ['en' => 'Other', 'ru' => 'Другие'],
        'Foto ar QR video' => ['en' => 'Photo with QR video', 'ru' => 'Фото с QR-видео'],
        'Digitāla QR dāvana' => ['en' => 'Digital QR gift', 'ru' => 'Цифровой QR-подарок'],
        'Dziesmas plāksne' => ['en' => 'Song plaque', 'ru' => 'Музыкальная пластинка'],
        'T-krekls' => ['en' => 'T-shirt', 'ru' => 'Футболка'],
        'Krūze' => ['en' => 'Mug', 'ru' => 'Кружка'],
        'Atslēgu piekariņš' => ['en' => 'Keychain', 'ru' => 'Брелок'],
        'Šokolāde' => ['en' => 'Chocolate', 'ru' => 'Шоколад'],
        'Kartīte' => ['en' => 'Card', 'ru' => 'Открытка'],
        'Saldumu komplekti' => ['en' => 'Sweet sets', 'ru' => 'Сладкие наборы'],
        'Druka + QR' => ['en' => 'Print + QR', 'ru' => 'Печать + QR'],
        'Digitāls QR' => ['en' => 'Digital QR', 'ru' => 'Цифровой QR'],
        'Druka + statīvs' => ['en' => 'Print + stand', 'ru' => 'Печать + подставка'],
        'Tekstils + QR' => ['en' => 'Textile + QR', 'ru' => 'Текстиль + QR'],
        'Keramika + QR' => ['en' => 'Ceramic + QR', 'ru' => 'Керамика + QR'],
        'Akrils + QR' => ['en' => 'Acrylic + QR', 'ru' => 'Акрил + QR'],
        'Šokolāde + iesaiņojums' => ['en' => 'Chocolate + packaging', 'ru' => 'Шоколад + упаковка'],
        'Kartons + QR' => ['en' => 'Cardstock + QR', 'ru' => 'Картон + QR'],
    ];

    public function import(): void
    {
        $path = database_path('seeders/catalog_seed.json');

        if (! is_file($path)) {
            return;
        }

        $payload = json_decode((string) file_get_contents($path), true, flags: JSON_THROW_ON_ERROR);

        DB::transaction(function () use ($payload): void {
            $categories = $this->importCategories($payload['catalogFilterGroups'] ?? []);
            $this->importProducts($payload['products'] ?? [], $categories);
        });
    }

    private function importCategories(array $groups): array
    {
        $categories = [];

        foreach ($groups as $group) {
            $type = self::TYPE_BY_GROUP[$group['id'] ?? ''] ?? null;

            if (! $type) {
                continue;
            }

            foreach (($group['items'] ?? []) as $index => $item) {
                $translations = self::CATEGORY_TRANSLATIONS[$item['value']] ?? [];
                $category = CatalogCategory::query()->updateOrCreate([
                    'type' => $type,
                    'slug' => $item['slug'],
                ], [
                    'name_lv' => $item['value'],
                    'name_ru' => $translations['ru'] ?? null,
                    'name_en' => $translations['en'] ?? null,
                    'image_path' => (string) Str::of($item['image'] ?? '')->ltrim('/') ?: null,
                    'disk' => ! empty($item['image']) ? 'asset' : null,
                    'icon' => $item['icon'] ?? null,
                    'tone' => $item['tone'] ?? null,
                    'is_active' => true,
                    'sort_order' => $index,
                ]);

                $categories[$type][$item['value']] = $category->id;
            }
        }

        return $categories;
    }

    private function importProducts(array $products, array $categories): void
    {
        foreach ($products as $index => $item) {
            $product = Product::query()->updateOrCreate([
                'slug' => $item['slug'],
            ], [
                'name_lv' => $item['name'],
                'name_ru' => null,
                'name_en' => null,
                'description_lv' => $item['description'] ?? null,
                'description_ru' => null,
                'description_en' => null,
                'detail_line_lv' => $item['detailLine'] ?? null,
                'detail_line_ru' => null,
                'detail_line_en' => null,
                'collection' => $item['collection'] ?? null,
                'price' => $item['price'] ?? 0,
                'old_price' => $item['oldPrice'] ?? null,
                'price_label' => $item['priceLabel'] ?? null,
                'capacity' => $item['capacity'] ?? 1,
                'color' => $item['color'] ?? null,
                'palette' => $item['palette'] ?? null,
                'sizes' => $item['sizes'] ?? null,
                'is_active' => (bool) ($item['available'] ?? true),
                'is_new' => (bool) ($item['isNew'] ?? false),
                'sort_order' => $index,
            ]);

            $categoryIds = collect([
                $categories['occasion'][$item['occasion'] ?? null] ?? null,
                $categories['category'][$item['category'] ?? null] ?? null,
                $categories['material'][$item['material'] ?? null] ?? null,
            ])->filter()->values()->all();

            $product->categories()->sync($categoryIds);

            if (! empty($item['image']) && ! $product->productImages()->exists()) {
                ProductImage::query()->create([
                    'product_id' => $product->id,
                    'image_path' => (string) Str::of($item['image'])->ltrim('/'),
                    'disk' => 'asset',
                    'alt' => $item['name'],
                    'is_primary' => true,
                    'sort_order' => 0,
                ]);
            }
        }
    }
}
