<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            "name_ar" => "required|unique:products,name_ar," . request()->id,
            "name_en" => "required|unique:products,name_en," . request()->id,
            "icon" => "required",
            "price" => "required|numeric|min:1",
            "image" => "nullable|image|dimensions:width=770,height=452",
            "description_ar" => "required",
            "description_en" => "required",
            "features" => "required|array|min:1",
            "features.*.context_ar" => "required",
            "features.*.context_en" => "required"
        ];
    }
}
