<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CrudExampleRequest extends FormRequest
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
            'text'              => 'required',
            'email'             => 'required|email',
            "number"            => "required|numeric",
            "currency"          => "required",
            "currency_idr"      => "required",
            "select"            => "required",
            "select2"           => "required",
            "select2_multiple"  => "required|array",
            "textarea"          => "required",
            "checkbox"          => "required|array",
            "checkbox2"         => "required|array",
            "radio"             => "required",
            "file"              => $this->isMethod('put') ? 'nullable|file' : "required|file",
            "image"             => $this->isMethod('put') ? 'nullable|image' : "required|image",
            "date"              => "required|date",
            "time"              => "required",
            "color"             => "required",
            "summernote_simple" => "required",
            "summernote"        => "required",
            "barcode"           => "required",
            "qr_code"           => "required",
        ];
    }
}
