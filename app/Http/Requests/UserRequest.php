<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Validation\Rules\Password;
use Onpay\Core\Http\FormRequest;

class UserRequest extends FormRequest
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
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $this->conditions->id . ',id'],
            'password' => [($this->conditions->isStoreAction ? 'required' : 'nullable'), Password::defaults()],
            'password_confirmation' => ['sometimes', 'required', 'string', 'min:8', 'same:password'],
            'locale' => ['sometimes', 'required', 'in:en,ms'],
        ];

        if ($this->conditions->user === null) {
            $rules['password'][] = 'confirmed';

            if (config('services.hcaptcha.sitekey')) {
                $rules['captcha'] = ['required', 'hcaptcha'];
            }
        }

        return $rules;
    }

    /**
     * Get the validated data from the request.
     *
     * @param  array|int|string|null  $key
     * @param  mixed  $default
     * @return mixed
     */
    public function validated($key = null, $default = null)
    {
        $validated = $this->validator->validated();

        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        return data_get($validated, $key, $default);
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $data = [];

        if ($this->conditions->user instanceof User) {
            $this->conditions->id = $this->conditions->user->id;
        }

        $this->merge($data);
    }
}
