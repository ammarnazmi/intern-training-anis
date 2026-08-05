<?php

namespace App\Http\Requests;

use App\Models\Admin;
use Onpay\Core\Http\FormRequest;

class AdminRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:admins,email,' . $this->conditions->id . ',id'],
            'locale' => ['sometimes', 'required', 'in:en,ms'],
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $data = [];

        if ($this->conditions->user instanceof Admin) {
            $this->conditions->id = $this->conditions->user->id;
        }

        $this->merge($data);
    }
}
