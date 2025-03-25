<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MobileUserAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => 'required|in:check_in,check_out',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'device_id' => 'nullable|string|max:255',
            'device_name' => 'nullable|string|max:255',
        ];
    }
}
