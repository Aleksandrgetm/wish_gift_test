<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class HomeCategoryBatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'categories' => ['required', 'json'],
            'files' => ['nullable', 'array'],
            'files.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'categories.required' => 'Нет данных для сохранения категорий.',
            'categories.json' => 'Некорректный формат данных категорий.',
            'files.*.image' => 'Файл должен быть изображением.',
            'files.*.mimes' => 'Допустимые форматы: JPG, PNG или WebP.',
            'files.*.max' => 'Максимальный размер изображения: 5 MB.',
        ];
    }
}
