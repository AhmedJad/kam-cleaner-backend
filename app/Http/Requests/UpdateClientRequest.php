<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClientRequest extends FormRequest
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
            "name_ar" => "required|unique:clients,name_ar," . request()->id,
            "name_en" => "required|unique:clients,name_en," . request()->id,
            "description_ar" => "required",
            "description_en" => "required",
            "icon" => "required"
        ];
    }
}
