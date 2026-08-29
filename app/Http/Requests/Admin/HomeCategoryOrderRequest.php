<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class HomeCategoryOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'items' => ['required', 'array', 'min:1'],
            'items.*.category_key' => ['required', 'string', 'max:80'],
            'items.*.sort_order' => ['required', 'integer', 'min:0'],
        ];
    }
}
