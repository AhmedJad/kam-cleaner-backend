<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactUsRequest extends FormRequest
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
            "email" => "required|email",
            'first_phone' => 'required|regex:/^01[0125][0-9]{8}$/',
            'second_phone' => 'required|regex:/^01[0125][0-9]{8}$/',
            'last_phone' => 'required|regex:/^01[0125][0-9]{8}$/',
            'address' => 'required',
            "working_days_ar"=>"required",
            "working_days_en"=>"required",
            "working_hours_ar"=>"required",
            "working_hours_en"=>"required",
            "facebook"=>"required",
            "twitter"=>"required",
            "instgram"=>"required",
            "linked_in"=>"required",
            "youtube"=>"required",
        ];
    }
}
