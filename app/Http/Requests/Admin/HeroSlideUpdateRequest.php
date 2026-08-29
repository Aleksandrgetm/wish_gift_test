<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class HeroSlideUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'alt' => ['sometimes', 'nullable', 'string', 'max:180'],
            'is_active' => ['sometimes', 'boolean'],
            'sort_order' => ['sometimes', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'image.image' => 'Файл должен быть изображением.',
            'image.mimes' => 'Допустимые форматы: JPG, PNG или WebP.',
            'image.max' => 'Максимальный размер изображения: 5 MB.',
        ];
    }
}
