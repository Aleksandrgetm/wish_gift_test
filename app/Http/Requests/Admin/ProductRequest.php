<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->is_admin === true;
    }

    public function rules(): array
    {
        $product = $this->route('product');

        return [
            'slug' => ['nullable', 'string', 'max:255', 'alpha_dash:ascii', Rule::unique('products')->ignore($product?->id)],
            'name_lv' => ['required', 'string', 'max:255'],
            'name_ru' => ['nullable', 'string', 'max:255'],
            'name_en' => ['nullable', 'string', 'max:255'],
            'description_lv' => ['required', 'string'],
            'description_ru' => ['nullable', 'string'],
            'description_en' => ['nullable', 'string'],
            'detail_line_lv' => ['nullable', 'string', 'max:255'],
            'detail_line_ru' => ['nullable', 'string', 'max:255'],
            'detail_line_en' => ['nullable', 'string', 'max:255'],
            'collection' => ['nullable', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'old_price' => ['nullable', 'numeric', 'min:0'],
            'price_label' => ['nullable', 'string', 'max:255'],
            'capacity' => ['nullable', 'integer', 'min:1', 'max:10'],
            'color' => ['nullable', 'string', 'max:80'],
            'palette' => ['nullable', 'array'],
            'palette.*' => ['string', 'max:32'],
            'sizes' => ['nullable', 'array'],
            'sizes.*' => ['string', 'max:80'],
            'is_active' => ['required', 'boolean'],
            'is_new' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'category_ids' => ['required', 'array', 'min:1'],
            'category_ids.*' => ['integer', 'exists:catalog_categories,id'],
            'images' => [Rule::requiredIf(! $product), 'array', 'min:1'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'delete_image_ids' => ['nullable', 'array'],
            'delete_image_ids.*' => ['integer', 'exists:product_images,id'],
        ];
    }

    public function after(): array
    {
        return [
            function ($validator): void {
                $product = $this->route('product');

                if (! $product) {
                    return;
                }

                $deleteIds = collect($this->input('delete_image_ids', []))->map(fn ($id) => (int) $id);
                $remainingImages = $product->productImages()
                    ->whereNotIn('id', $deleteIds->all())
                    ->exists();

                if (! $remainingImages && ! $this->hasFile('images')) {
                    $validator->errors()->add('images', 'У товара должно остаться хотя бы одно изображение.');
                }
            },
        ];
    }
}
