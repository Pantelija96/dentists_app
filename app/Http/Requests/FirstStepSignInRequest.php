<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FirstStepSignInRequest extends FormRequest
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
            'type' => ['required'],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'confirmed'],
            'password_confirmation' => ['required'],
            'firm_name'  => ['nullable', 'required_if:type,1'],
            'first_name' => ['required_if:type,2'],
            'last_name'  => ['required_if:type,2'],
            'terms' => ['required']
        ];
    }

    public function messages()
    {
        return [
            'password.confirmed' => 'Passwords do not match.',
            'email.unique' => 'This email is already taken.',
            'first_name.required_if' => 'First name is required.',
            'last_name.required_if'  => 'Last name is required.',
            'firmName.required_if' => 'Firm name is required.',
            'terms.required' => 'Please accept our terms of service!'
        ];
    }
}
