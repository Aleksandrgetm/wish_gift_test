<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class HeroSlideStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'images' => ['required', 'array', 'min:1', 'max:12'],
            'images.*' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'alt' => ['nullable', 'string', 'max:180'],
        ];
    }

    public function messages(): array
    {
        return [
            'images.required' => 'Загрузите хотя бы одно изображение.',
            'images.*.image' => 'Файл должен быть изображением.',
            'images.*.mimes' => 'Допустимые форматы: JPG, PNG или WebP.',
            'images.*.max' => 'Максимальный размер изображения: 5 MB.',
        ];
    }
}
