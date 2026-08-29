<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProductImageOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->is_admin === true;
    }

    public function rules(): array
    {
        return [
            'items' => ['required', 'array'],
            'items.*.id' => ['required', 'integer', 'exists:product_images,id'],
            'items.*.sort_order' => ['required', 'integer', 'min:0'],
            'items.*.is_primary' => ['nullable', 'boolean'],
        ];
    }
}
