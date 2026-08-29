<?php

namespace App\Models;

use App\Services\HomepageImageService;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['product_id', 'image_path', 'disk', 'alt', 'is_primary', 'sort_order'])]
class ProductImage extends Model
{
    protected $appends = ['image_url'];

    protected function casts(): array
    {
        return [
            'is_primary' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getImageUrlAttribute(): ?string
    {
        return app(HomepageImageService::class)->url($this->image_path, $this->disk);
    }
}
