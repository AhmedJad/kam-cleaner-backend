<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateServiceRequest extends FormRequest
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
            "title_ar" => "required|unique:services,title_ar," . request()->id,
            "title_en" => "required|unique:services,title_en," . request()->id,
            "description_ar" => "required",
            "description_en" => "required",
            "image" => "nullable|image",
            "url" => "required"
        ];
    }
}
