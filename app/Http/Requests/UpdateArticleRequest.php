<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateArticleRequest extends FormRequest
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
            "title_ar" => "required|unique:articles,title_ar," . request()->id,
            "title_en" => "required|unique:articles,title_en," . request()->id,
            "subject_ar" => "required",
            "subject_en" => "required",
            "description_ar" => "required",
            "description_en" => "required",
            "image" => "nullable|image|dimensions:width=770,height=420",
        ];
    }
}
