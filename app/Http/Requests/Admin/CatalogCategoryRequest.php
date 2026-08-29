<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CatalogCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->is_admin === true;
    }

    public function rules(): array
    {
        $category = $this->route('catalogCategory');
        $id = $category?->id;

        return [
            'type' => ['required', Rule::in(['occasion', 'category', 'material'])],
            'name_lv' => ['required', 'string', 'max:255'],
            'name_ru' => ['nullable', 'string', 'max:255'],
            'name_en' => ['nullable', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                'alpha_dash:ascii',
                Rule::unique('catalog_categories')->where('type', $this->input('type'))->ignore($id),
            ],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'delete_image' => ['nullable', 'boolean'],
            'icon' => ['nullable', 'string', 'max:80'],
            'tone' => ['nullable', 'string', 'max:40'],
            'is_active' => ['required', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
