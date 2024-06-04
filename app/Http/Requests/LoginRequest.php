<?php

namespace App\Http\Requests;

use App\Repositories\SettingRepository;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

class LoginRequest extends FormRequest
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
        if (Route::is('api.login')) {
            return [
                'email'    => 'required|exists:users,email',
                'password' => 'required|min:4',
            ];
        }

        $isGoogleCaptcha = SettingRepository::isGoogleCaptchaLogin();
        return [
            'email'                => 'required|exists:users,email',
            'password'             => 'required|min:4',
            'g-recaptcha-response' => $isGoogleCaptcha ? 'required|captcha' : 'nullable'
        ];
    }
}
