<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PincodeRequest extends FormRequest
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
            'pincode' => [
                'required', 'string', 'max:100',
                Rule::unique('pincodes')->ignore($this->route('pincode'))
            ],
            'district_id' => [
                'required', 'integer', 'exists:districts,id',
            ],
            'is_active' => 'required|boolean',
        ];
    }
}
