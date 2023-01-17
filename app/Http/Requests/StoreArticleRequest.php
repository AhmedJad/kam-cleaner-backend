<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreArticleRequest extends FormRequest
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
            "title_ar" => "required|unique:articles",
            "title_en" => "required|unique:articles",
            "subject_ar" => "required",
            "subject_en" => "required",
            "description_ar" => "required",
            "description_en" => "required",
            "image" => "required|image|dimensions:width=770,height=420",
        ];
    }
}
