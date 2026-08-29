<?php

namespace App\Models;

use App\Services\HomepageImageService;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['category_key', 'image_path', 'disk', 'alt', 'sort_order', 'is_active'])]
class HomeCategoryImage extends Model
{
    protected $appends = ['image_url'];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function getImageUrlAttribute(): ?string
    {
        return app(HomepageImageService::class)->url($this->image_path, $this->disk);
    }
}
