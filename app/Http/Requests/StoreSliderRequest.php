<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSliderRequest extends FormRequest
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
            "title_ar" => "required|unique:sliders",
            "title_en" => "required|unique:sliders",
            "image" => "required|image|dimensions:width=1292,height=800",
            "url" => "required"
        ];
    }
}
