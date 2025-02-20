<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProjectRequest extends FormRequest
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
            'project_id' => [
                'required', 'string', 'max:100',
                Rule::unique('projects')->ignore($this->route('project'))
            ],
            'name' => [
                'required', 'string', 'max:100',
                Rule::unique('projects')->ignore($this->route('project'))
            ],
            'location'=>'required|string|max:255',
            'type'=>'required|string|max:255',
            'units'=>'required|integer',
            'target_customers'=>'required|string|max:255',
            'range'=>'required|string|max:255',
            'is_active' => 'required|boolean'
        ];
    }
}
