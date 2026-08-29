<?php

namespace App\Services;

use App\Models\CatalogCategory;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CatalogService
{
    public function __construct(private readonly HomepageImageService $images) {}

    public function categoryGroups(bool $admin = false): Collection
    {
        return CatalogCategory::query()
            ->withCount('products')
            ->when(! $admin, fn (Builder $query) => $query->where('is_active', true))
            ->orderByRaw("case type when 'occasion' then 0 when 'category' then 1 when 'material' then 2 else 3 end")
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->groupBy('type');
    }

    public function publicPayload(array $filters = []): array
    {
        $locale = $filters['locale'] ?? 'lv';
        $products = $this->productQuery($filters)
            ->where('is_active', true)
            ->get()
            ->map(fn (Product $product) => $product->localizedPayload($locale))
            ->values();

        return [
            'categories' => $this->formattedCategories($this->categoryGroups(), $locale),
            'products' => $products,
        ];
    }

    public function adminProducts(array $filters): LengthAwarePaginator
    {
        return $this->productQuery($filters)
            ->paginate((int) ($filters['per_page'] ?? 12))
            ->through(fn (Product $product) => $product->localizedPayload('lv'));
    }

    public function productQuery(array $filters = []): Builder
    {
        $query = Product::query()
            ->with(['categories', 'productImages'])
            ->orderBy('sort_order')
            ->orderBy('id');

        if (! empty($filters['search'])) {
            $search = mb_strtolower($filters['search']);
            $query->where(function (Builder $query) use ($search): void {
                foreach (['name_lv', 'name_ru', 'name_en', 'description_lv', 'description_ru', 'description_en', 'slug'] as $column) {
                    $query->orWhereRaw("lower({$column}) like ?", ["%{$search}%"]);
                }
            });
        }

        if (! empty($filters['category_slug'])) {
            $slug = $filters['category_slug'];
            $query->whereHas('categories', fn (Builder $query) => $query->where('slug', $slug));
        }

        if (! empty($filters['category_id'])) {
            $query->whereHas('categories', fn (Builder $query) => $query->whereKey($filters['category_id']));
        }

        foreach (['occasion' => 'occasions', 'category' => 'categories', 'material' => 'materials'] as $type => $key) {
            $values = $filters[$key] ?? [];
            $values = is_array($values) ? array_filter($values) : array_filter(explode(',', (string) $values));

            if ($values) {
                $query->whereHas('categories', fn (Builder $query) => $query
                    ->where('type', $type)
                    ->whereIn('name_lv', $values));
            }
        }

        if (isset($filters['max_price'])) {
            $query->where('price', '<=', (float) $filters['max_price']);
        }

        if (! empty($filters['available'])) {
            $query->where('is_active', true);
        }

        return $query;
    }

    public function formattedCategories(Collection $groups, string $locale = 'lv'): array
    {
        return $groups->map(fn (Collection $items, string $type) => [
            'type' => $type,
            'filter_key' => match ($type) {
                'occasion' => 'occasions',
                'category' => 'categories',
                'material' => 'materials',
                default => $type,
            },
            'items' => $items->map(fn (CatalogCategory $category) => [
                'id' => $category->id,
                'type' => $category->type,
                'name' => $category->localizedName($locale),
                'name_lv' => $category->name_lv,
                'name_ru' => $category->name_ru,
                'name_en' => $category->name_en,
                'slug' => $category->slug,
                'image_url' => $category->image_url,
                'image_path' => $category->image_path,
                'disk' => $category->disk,
                'icon' => $category->icon,
                'tone' => $category->tone,
                'is_active' => $category->is_active,
                'sort_order' => $category->sort_order,
                'products_count' => $category->products_count,
            ])->values(),
        ])->values()->all();
    }

    public function storeCategory(array $data, ?UploadedFile $image = null): CatalogCategory
    {
        if ($image) {
            $data['image_path'] = $this->images->storeCatalogCategoryImage($image);
            $data['disk'] = 'public';
        }

        return CatalogCategory::query()->create($data);
    }

    public function updateCategory(CatalogCategory $category, array $data, ?UploadedFile $image = null, bool $deleteImage = false): CatalogCategory
    {
        $oldPath = $category->image_path;
        $oldDisk = $category->disk;

        if ($deleteImage) {
            $data['image_path'] = null;
            $data['disk'] = null;
        }

        if ($image) {
            $data['image_path'] = $this->images->storeCatalogCategoryImage($image);
            $data['disk'] = 'public';
        }

        $category->update($data);

        if (($image || $deleteImage) && $oldPath !== $category->image_path) {
            $this->images->deleteManaged($oldPath, $oldDisk);
        }

        return $category->refresh()->loadCount('products');
    }

    public function deleteCategory(CatalogCategory $category): void
    {
        $path = $category->image_path;
        $disk = $category->disk;

        DB::transaction(fn () => $category->delete());
        $this->images->deleteManaged($path, $disk);
    }

    public function storeProduct(array $data, array $categoryIds, array $files = []): Product
    {
        return DB::transaction(function () use ($data, $categoryIds, $files): Product {
            $product = Product::query()->create($data);
            $product->categories()->sync($categoryIds);
            $this->syncNewProductImages($product, $files);

            return $product->refresh()->load(['categories', 'productImages']);
        });
    }

    public function uniqueProductSlug(string $name, ?Product $product = null): string
    {
        $base = Str::slug($name) ?: 'product';
        $slug = $base;
        $counter = 2;

        while (Product::query()
            ->where('slug', $slug)
            ->when($product, fn (Builder $query) => $query->whereKeyNot($product->id))
            ->exists()) {
            $slug = "{$base}-{$counter}";
            $counter++;
        }

        return $slug;
    }

    public function updateProduct(Product $product, array $data, array $categoryIds, array $files = [], array $deleteImageIds = []): Product
    {
        return DB::transaction(function () use ($product, $data, $categoryIds, $files, $deleteImageIds): Product {
            $product->update($data);
            $product->categories()->sync($categoryIds);

            foreach ($product->productImages()->whereIn('id', $deleteImageIds)->get() as $image) {
                $path = $image->image_path;
                $disk = $image->disk;
                $image->delete();
                $this->images->deleteManaged($path, $disk);
            }

            $this->syncNewProductImages($product, $files);
            $this->normalizePrimaryImage($product);

            return $product->refresh()->load(['categories', 'productImages']);
        });
    }

    public function deleteProduct(Product $product): void
    {
        $images = $product->productImages()->get(['image_path', 'disk']);
        DB::transaction(fn () => $product->delete());

        foreach ($images as $image) {
            $this->images->deleteManaged($image->image_path, $image->disk);
        }
    }

    public function syncProductImageOrder(Product $product, array $items): Product
    {
        foreach ($items as $item) {
            $product->productImages()->whereKey($item['id'])->update([
                'sort_order' => $item['sort_order'],
                'is_primary' => (bool) ($item['is_primary'] ?? false),
            ]);
        }

        $this->normalizePrimaryImage($product);

        return $product->refresh()->load(['categories', 'productImages']);
    }

    private function syncNewProductImages(Product $product, array $files): void
    {
        foreach ($files as $index => $file) {
            ProductImage::query()->create([
                'product_id' => $product->id,
                'image_path' => $this->images->storeProductImage($file),
                'disk' => 'public',
                'alt' => $product->name_lv,
                'is_primary' => ! $product->productImages()->exists() && $index === 0,
                'sort_order' => (int) $product->productImages()->max('sort_order') + 1 + $index,
            ]);
        }

        $this->normalizePrimaryImage($product);
    }

    private function normalizePrimaryImage(Product $product): void
    {
        $images = $product->productImages()->orderBy('sort_order')->orderBy('id')->get();

        if ($images->isEmpty()) {
            return;
        }

        if (! $images->contains('is_primary', true)) {
            $images->first()->update(['is_primary' => true]);
        }
    }
}
