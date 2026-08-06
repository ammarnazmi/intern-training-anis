<?php

namespace App\Http\Requests;

use Onpay\Core\Http\FormRequest;

class SubproductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0', 'max:999999.99'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('The subproduct name is required.'),
            'price.required' => __('The subproduct price is required.'),
            'price.numeric' => __('The subproduct price must be a valid number.'),
        ];
    }
}
