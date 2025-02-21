<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomerRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => [
                'required', 'email', 'max:225',
                Rule::unique('users')->ignore($this->route('customer'))
            ],
            // 'email' => 'required|email|max:255|unique:customers,email,' . optional($this->route('customer'))->id,
            'dob' => 'required|date',
            'mobile' => 'required|digits_between:7,15',
            'address' => 'required|string|max:255',
            'state_id' => 'required|exists:states,id',
            'district_id' => 'required|exists:districts,id',
            'city_id' => 'required|exists:cities,id',
            'pincode_id' => 'required|exists:pincodes,id', 
            'password' => 'nullable|string|min:6',
            'active_status' => 'required|boolean',
        ];        
    }
}
