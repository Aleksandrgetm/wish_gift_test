<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class HomepageImageService
{
    public function storeHeroImage(UploadedFile $image): string
    {
        return $image->store('homepage/hero', 'public');
    }

    public function storeCategoryImage(UploadedFile $image): string
    {
        return $image->store('homepage/categories', 'public');
    }

    public function storeCatalogCategoryImage(UploadedFile $image): string
    {
        return $image->store('catalog/categories', 'public');
    }

    public function storeProductImage(UploadedFile $image): string
    {
        return $image->store('catalog/products', 'public');
    }

    public function delete(?string $path): void
    {
        if (! $path) {
            return;
        }

        Storage::disk('public')->delete($path);
    }

    public function deleteManaged(?string $path, ?string $disk): void
    {
        if ($disk !== 'public') {
            return;
        }

        $this->delete($path);
    }

    public function url(?string $path, ?string $disk): ?string
    {
        if (! $path) {
            return null;
        }

        if ($disk === 'asset') {
            return '/'.Str::of($path)->ltrim('/');
        }

        if ($disk === 'public') {
            return '/storage/'.Str::of($path)->ltrim('/');
        }

        return Storage::disk($disk ?: 'public')->url($path);
    }
}
