<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdditionalInformationRequest extends FormRequest
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
            'region_id' => 'required',
            'phone_number' => 'required',
            'language' => 'required',
            'address1' => 'required',
            'country' => 'required',
            'city' => 'required',
            'postal_code' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'region.required' => 'Field Region is required!',
            'phone_number.required' => 'Field Phone is required!',
            'language.required' => 'Field Language is required!',
            'address1.required' => 'Field Address1 is required!',
            'country.required' => 'Field Country is required!',
            'city.required' => 'Field City is required!',
            'postal_code.required' => 'Field Postal code is required!',
        ];
    }
}
