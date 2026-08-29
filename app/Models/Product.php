<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'slug',
    'name_lv',
    'name_ru',
    'name_en',
    'description_lv',
    'description_ru',
    'description_en',
    'detail_line_lv',
    'detail_line_ru',
    'detail_line_en',
    'collection',
    'price',
    'old_price',
    'price_label',
    'capacity',
    'color',
    'palette',
    'sizes',
    'is_active',
    'is_new',
    'sort_order',
])]
class Product extends Model
{
    protected $appends = ['image', 'images'];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'old_price' => 'decimal:2',
            'palette' => 'array',
            'sizes' => 'array',
            'is_active' => 'boolean',
            'is_new' => 'boolean',
            'sort_order' => 'integer',
            'capacity' => 'integer',
        ];
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(CatalogCategory::class)->withTimestamps();
    }

    public function productImages(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order')->orderBy('id');
    }

    public function getImageAttribute(): ?string
    {
        return $this->productImages->first()?->image_url;
    }

    public function getImagesAttribute(): array
    {
        return $this->productImages->map(fn (ProductImage $image) => [
            'id' => $image->id,
            'image_url' => $image->image_url,
            'image_path' => $image->image_path,
            'disk' => $image->disk,
            'alt' => $image->alt,
            'is_primary' => $image->is_primary,
            'sort_order' => $image->sort_order,
        ])->values()->all();
    }

    public function localizedPayload(?string $locale = null): array
    {
        $locale = in_array($locale, ['lv', 'ru', 'en'], true) ? $locale : 'lv';
        $occasion = $this->categories->firstWhere('type', 'occasion');
        $category = $this->categories->firstWhere('type', 'category');
        $material = $this->categories->firstWhere('type', 'material');

        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->{"name_{$locale}"} ?: $this->name_lv,
            'name_lv' => $this->name_lv,
            'name_ru' => $this->name_ru,
            'name_en' => $this->name_en,
            'description' => $this->{"description_{$locale}"} ?: $this->description_lv,
            'description_lv' => $this->description_lv,
            'description_ru' => $this->description_ru,
            'description_en' => $this->description_en,
            'detailLine' => $this->{"detail_line_{$locale}"} ?: $this->detail_line_lv,
            'detail_line_lv' => $this->detail_line_lv,
            'detail_line_ru' => $this->detail_line_ru,
            'detail_line_en' => $this->detail_line_en,
            'collection' => $this->collection,
            'price' => (float) $this->price,
            'oldPrice' => $this->old_price ? (float) $this->old_price : null,
            'old_price' => $this->old_price ? (float) $this->old_price : null,
            'priceLabel' => $this->price_label ?: sprintf('%s €', (float) $this->price),
            'available' => $this->is_active,
            'isNew' => $this->is_new,
            'is_active' => $this->is_active,
            'is_new' => $this->is_new,
            'capacity' => $this->capacity,
            'color' => $this->color,
            'palette' => $this->palette ?: ['#67349B', '#B89146'],
            'sizes' => $this->sizes ?: [],
            'image' => $this->image,
            'images' => $this->images,
            'occasion' => $occasion?->name_lv,
            'category' => $category?->name_lv,
            'material' => $material?->name_lv,
            'categories' => $this->categories->map(fn (CatalogCategory $category) => [
                'id' => $category->id,
                'type' => $category->type,
                'slug' => $category->slug,
                'name' => $category->localizedName($locale),
                'name_lv' => $category->name_lv,
            ])->values()->all(),
            'sort_order' => $this->sort_order,
        ];
    }
}
