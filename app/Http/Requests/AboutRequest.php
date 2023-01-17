<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AboutRequest extends FormRequest
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
            "title_ar" => "required",
            "title_en" => "required",
            "description_ar" => "required",
            "description_en" => "required",
            "image" => "required_without:id|image|dimensions:width=1292,height=800",
            'phone' => 'required|regex:/^01[0125][0-9]{8}$/',
            "features" => "required|array|min:1",
            "features.*.context_ar" => "required",
            "features.*.context_en" => "required"
        ];
    }
}
