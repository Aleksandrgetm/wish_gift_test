<?php

namespace App\Models;

use App\Services\HomepageImageService;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['type', 'name_lv', 'name_ru', 'name_en', 'slug', 'image_path', 'disk', 'icon', 'tone', 'is_active', 'sort_order'])]
class CatalogCategory extends Model
{
    protected $appends = ['image_url'];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)->withTimestamps();
    }

    public function getImageUrlAttribute(): ?string
    {
        return app(HomepageImageService::class)->url($this->image_path, $this->disk);
    }

    public function localizedName(string $locale): string
    {
        return $this->{"name_{$locale}"} ?: $this->name_lv;
    }
}
