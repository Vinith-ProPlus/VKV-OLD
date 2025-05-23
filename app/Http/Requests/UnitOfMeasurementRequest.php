<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UnitOfMeasurementRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => [
                'required', 'string', 'max:100',
                Rule::unique('unit_of_measurements')->ignore($this->route('unit'))
            ],
            'code' => [
                'required', 'string', 'max:50',
                Rule::unique('unit_of_measurements')->ignore($this->route('unit'))
            ],
            'is_active' => 'required|boolean',
        ];
    }
}
