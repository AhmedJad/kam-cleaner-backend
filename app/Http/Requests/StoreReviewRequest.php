<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
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
            "name" => "required",
            "job_ar" => "required",
            "job_en" => "required",
            "image" => "required|image|dimensions:width=166,height=166",
            "review_ar" => "required",
            "review_en" => "required"
        ];
    }
}
