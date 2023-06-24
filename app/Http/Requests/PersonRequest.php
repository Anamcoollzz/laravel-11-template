<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PersonRequest extends FormRequest
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
            'name'          => 'required',
            'nik'           => 'required',
            // "file"          => $this->isMethod('put') ? '' : "required|file",
            "province_code" => "required",
            "city_code"     => "required",
            "district_code" => "required",
            "village_code"  => "required",
        ];
    }
}
