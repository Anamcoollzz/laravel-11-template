<?php

namespace App\Http\Requests;

use App\Repositories\UserRepository;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

class ProfileRequest extends FormRequest
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
        if (Route::is('profile.update-password')) {
            return [
                'new_password'              => 'required|min:6|confirmed',
                'new_password_confirmation' => 'required|min:6',
                'old_password'              => ['required', 'min:6', function ($attribute, $value, $fail) {
                    $user = auth_user();
                    if (!Hash::check($value, $user->password)) {
                        $fail('Password lama tidak sesuai');
                    }
                }],
            ];
        }
        if (Route::is('profile.update-email')) {
            return [
                'email' => 'required|email|unique:users,email'
            ];
        }
        if (Route::is('api.profiles.update-password')) {
            return [
                'current_password'          => 'required|min:6',
                'new_password'              => 'required|min:6|confirmed',
                'new_password_confirmation' => 'required|min:6',
            ];
        }

        $userId = (new UserRepository)->getUserIdLogin();
        if (Route::is('api.profiles.update')) {
            return [
                'name'   => 'required',
                'avatar' => 'nullable|image',
                'email'  => 'required|email|unique:users,email,' . $userId . ',id'
            ];
        }
        return [
            'name'   => 'required',
            'avatar' => 'nullable|image',
            // 'email'  => 'required|email|unique:users,email,' . $userId . ',id'
        ];
    }
}
