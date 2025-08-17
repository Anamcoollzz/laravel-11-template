<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Repositories\SettingRepository;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends StislaController
{

    /**
     * active socialite providers
     *
     * @var array
     */
    private array $socialiteProviders = [
        'google',
        'facebook',
        'twitter',
        'github',
    ];

    /**
     * constructor method
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * showing register form page
     *
     * @return Response
     */
    public function registerForm()
    {
        if ($this->settingRepository->isActiveRegisterPage() === false)
            abort(404);

        // if (config('app.template') === 'stisla') {
        //     $template = \App\Models\Setting::firstOrCreate(['key' => 'login_template'], ['value' => 'default'])->value;
        //     if ($template === 'tampilan 2')
        //         return view('stisla.auth.login.index-stisla-2');
        //     else
        //         return view('stisla.auth.login.index-stisla');
        // }
        // return view('stisla.auth.login.index');
        $isGoogleCaptcha = $this->settingRepository->isGoogleCaptchaRegister();
        if (TEMPLATE === STISLA)
            return view('stisla.auth.register.index', [
                'isGoogleCaptcha' => $isGoogleCaptcha
            ]);
    }

    /**
     * process register
     *
     * @param RegisterRequest $request
     * @return Response
     */
    public function register(RegisterRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->only(
                [
                    'name',
                    'email',
                    'phone_number',
                    'birth_date',
                    'address',
                ]
            );
            $data = array_merge([
                'password' => bcrypt($request->password)
            ], $data);
            $user = $this->userRepository->create($data);
            $this->userRepository->assignRole($user, 'user');
            if ($this->settingRepository->loginMustVerified()) {
                $user->update(['email_token' => Str::random(150)]);
                $this->emailService->verifyAccount($user);
                logRegister($user);
                DB::commit();
                return redirect()->route('login')->with('successMessage', __('Cek inbox email anda untuk memverifikasi akun terlebih dahulu'));
            }
            logRegister($user);
            $this->userRepository->login($user);
            DB::commit();
            return redirect()->route('dashboard.index')->with('successMessage', __('Berhasil mendaftar dan masuk ke dalam sistem'));
        } catch (Exception $e) {
            DB::rollBack();
            if (Str::contains($e->getMessage(), 'SMTP')) {
                return back()->withInput()->with('errorMessage', __('Gagal mengirim email verifikasi, silahkan coba lagi nanti'));
            }
            return back()->with('errorMessage', __($e->getMessage()));
        }
    }

    /**
     * showing login form page
     *
     * @return Response
     */
    public function loginForm()
    {
        $isGoogleCaptcha = SettingRepository::isGoogleCaptchaLogin();
        if (TEMPLATE === STISLA) {
            $template = $this->settingRepository->stislaLoginTemplate();
            $data     = [
                'isGoogleCaptcha' => $isGoogleCaptcha,
            ];
            if ($template === 'tampilan 2' || Route::is('login2')) {
                return view('stisla.auth.login.index2', $data);
            } else {
                return view('stisla.auth.login.index', $data);
            }
        }
        return view('stisla.auth.login.index');
    }

    /**
     * process login
     *
     * @param LoginRequest $request
     * @return Response
     */
    public function login(LoginRequest $request)
    {
        $maxWrongLogin = 5;
        $user = $this->userRepository->findByEmail($request->email);
        if ($user->deleted_at !== null) {
            return Helper::backError(['email' => __('Akun anda sudah dihapus, silakan menggunakan akun lain ')]);
        }
        if ($user->is_active == 0) {
            return Helper::backError(['email' => __('Akun anda sudah diblokir dikarenakan ') . $user->blocked_reason]);
        }
        if (Hash::check($request->password, $user->password)) {
            if ($user->wrong_login >= $maxWrongLogin) {
                return Helper::backError(['email' => __('Akun anda sudah diblokir dikarenakan ') . $user->blocked_reason]);
            }
            $loginMustVerified = $this->settingRepository->loginMustVerified();

            if ($loginMustVerified && $user->email_verified_at === null) {
                return Helper::backError(['email' => __('Email belum diverifikasi')]);
            }
            $this->userRepository->update([
                'is_active'      => true,
                'wrong_login'    => 0,
                'blocked_reason' => null
            ], $user->id);
            $this->userRepository->login($user);
            return Helper::redirectSuccess(route('dashboard.index'), __('Berhasil masuk ke dalam sistem'));
        } else {
            $userNew = $this->userRepository->update(['wrong_login' => $user->wrong_login + 1], $user->id);
            if ($userNew->wrong_login >= $maxWrongLogin) {
                $blockedReason = 'Salah memasukkan kata sandi sebanyak 5 kali';
                $userNew->update([
                    'is_active' => false,
                    'blocked_reason' => $blockedReason
                ]);
                logExecute(__('Login'), UPDATE, $user, $userNew);
                return Helper::backError(['email' => __('Akun anda sudah diblokir dikarenakan ') . $blockedReason]);
            }
            logExecute(__('Login'), UPDATE, $user, $userNew);
            return Helper::backError(['password' => __('Password yang dimasukkan salah (tersisa ' . $maxWrongLogin - $userNew->wrong_login . ')')]);
        }
        return Helper::backError(['password' => __('Password yang dimasukkan salah')]);
    }

    /**
     * logout from system
     *
     * @return Response
     */
    public function logout()
    {
        logLogout();
        Auth::logout();
        Session::flush();
        return redirect()->route('login');
    }

    /**
     * showing forgot password page
     *
     * @return Response
     */
    public function forgotPasswordForm()
    {
        if ($this->settingRepository->isForgotPasswordSendToEmail() === false) {
            abort(404);
        }

        $isGoogleCaptcha = $this->settingRepository->isGoogleCaptchaForgotPassword();

        if (TEMPLATE === STISLA) {
            // $template = \App\Models\Setting::firstOrCreate(['key' => 'login_template'], ['value' => 'default'])->value;
            // if ($template === 'tampilan 2')
            // return view('stisla.auth.login.index-stisla-2');
            // else
            return view('stisla.auth.forgot-password.index2', [
                'isGoogleCaptcha' => $isGoogleCaptcha
            ]);
        }
        return view('stisla.auth.login.index');
    }

    /**
     * process forgot password
     *
     * @param ForgotPasswordRequest $request
     * @return Response
     */
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        if ($this->settingRepository->isForgotPasswordSendToEmail() === false) abort(404);
        DB::beginTransaction();
        try {
            $user = $this->userRepository->findByEmail($request->email);
            $userNew = $this->userRepository->update([
                'email_token' => Str::random(100),
                'verification_code' => rand(100000, 999999)
            ], $user->id);
            $this->emailService->forgotPassword($userNew);
            logForgotPassword($user, $userNew);
            DB::commit();
            return back()->withInput()->with('successMessage', __('Berhasil mengirim ke ' . $request->email));
        } catch (Exception $e) {
            DB::rollBack();
            // if (Str::contains($e->getMessage(), 'Connection could not be established')) {
            return back()->withInput()->with('errorMessage', __('Gagal mengirim email, server email sedang gangguan'));
            // }
            // return $e->getMessage();
        }
    }

    /**
     * showing reset password page
     *
     * @param mixed $token
     * @return Response
     */
    public function resetPasswordForm($token)
    {
        if ($this->settingRepository->isForgotPasswordSendToEmail() === false) {
            abort(404);
        }

        $user = $this->userRepository->findByEmailToken($token);
        if ($user === null) {
            abort(404);
        }

        $isGoogleCaptcha = $this->settingRepository->isGoogleCaptchaResetPassword();

        if (TEMPLATE === STISLA) {
            // $template = \App\Models\Setting::firstOrCreate(['key' => 'login_template'], ['value' => 'default'])->value;
            // if ($template === 'tampilan 2')
            // return view('stisla.auth.login.index-stisla-2');
            // else
            return view('stisla.auth.reset-password.index2', [
                'isGoogleCaptcha' => $isGoogleCaptcha,
            ]);
        }
        return view('stisla.auth.login.index');
    }

    /**
     * process reset password
     *
     * @param mixed $token
     * @param ResetPasswordRequest $request
     * @return Response
     */
    public function resetPassword($token, ResetPasswordRequest $request)
    {
        if ($this->settingRepository->isForgotPasswordSendToEmail() === false) {
            abort(404);
        }

        DB::beginTransaction();
        try {
            $user = $this->userRepository->findByEmailToken($token);
            if ($user === null) {
                return back()->withInput()->with('errorMessage', __('Gagal memperbarui kata sandi'));
            }

            $userNew = $this->userRepository->update(['password' => bcrypt($request->new_password), 'email_token' => null], $user->id);

            logExecute(__('Reset Kata Sandi'), UPDATE, $user->password, $userNew->password);
            DB::commit();
            return redirect()->route('login')->withInput()->with('successMessage', __('Berhasil memperbarui kata sandi'));
        } catch (Exception $e) {
            return back()->withInput()->with('errorMessage', __('Gagal memperbarui kata sandi'));
        }
    }

    /**
     * showing verification form page
     *
     * @return Response
     */
    public function verificationForm()
    {
        if ($this->settingRepository->loginMustVerified() === false) abort(404);
        // if (config('app.template') === 'stisla') {
        //     $template = \App\Models\Setting::firstOrCreate(['key' => 'login_template'], ['value' => 'default'])->value;
        //     if ($template === 'tampilan 2')
        //         return view('stisla.auth.login.index-stisla-2');
        //     else
        //         return view('stisla.auth.login.index-stisla');
        // }
        // return view('stisla.auth.login.index');
        if (TEMPLATE === STISLA)
            return view('stisla.auth.verification.index2');
    }

    /**
     * process forgot password
     *
     * @param ForgotPasswordRequest $request
     * @return Response
     */
    public function sendEmailVerification(ForgotPasswordRequest $request)
    {
        if ($this->settingRepository->loginMustVerified() === false) abort(404);
        DB::beginTransaction();
        try {
            $user = $this->userRepository->findByEmail($request->email);
            $userNew = $this->userRepository->update(['email_token' => Str::random(150)], $user->id);
            $this->emailService->verifyAccount($userNew);
            logExecute(__('Email Verifikasi'), UPDATE, null, null);
            DB::commit();
            return back()->withInput()->with('successMessage', __('Berhasil mengirim link verifikasi ke ' . $request->email));
        } catch (Exception $e) {
            DB::rollBack();
            // if (Str::contains($e->getMessage(), 'Connection could not be established')) {
            return back()->withInput()->with('errorMessage', __('Gagal mengirim email, server email sedang gangguan'));
            // }
            // return $e->getMessage();
        }
    }

    /**
     * process verify account
     *
     * @param mixed $token
     * @return Response
     */
    public function verify($token)
    {
        if ($this->settingRepository->loginMustVerified() === false) abort(404);
        $user = $this->userRepository->findByEmailToken($token);
        if ($user === null) abort(404);
        $userNew = $this->userRepository->update([
            'email_verified_at' => now(),
            'email_token'       => null,
            'verification_code' => null
        ], $user->id);
        logExecute(__('Verifikasi Akun'), UPDATE, $user, $userNew);
        return redirect()->route('login')->with('successMessage', __('Berhasil memverifikasi akun, silakan masuk menggunakan akun anda'));
    }

    /**
     * save temp session provider
     *
     * @return Response
     */
    public function socialLogin($provider)
    {
        if (!in_array($provider, $this->socialiteProviders)) {
            abort(404);
        }

        $isValid = false;
        if ($provider === 'facebook') {
            $isValid = $this->settingRepository->isLoginWithFacebook();
        } else if ($provider === 'google') {
            $isValid = $this->settingRepository->isLoginWithGoogle();
        } else if ($provider === 'twitter') {
            $isValid = $this->settingRepository->isLoginWithTwitter();
        } else if ($provider === 'github') {
            $isValid = $this->settingRepository->isLoginWithGithub();
        }

        if ($isValid) {
            session(['social_action' => 'login']);
            return Socialite::driver($provider)->redirect();
        }

        abort(404);
    }

    /**
     * callback social login and register callback
     * @param mixed $provider
     *
     * @return Response
     */
    public function socialCallback($provider)
    {
        try {
            if (!in_array($provider, $this->socialiteProviders)) {
                abort(404);
            }
            $user = Socialite::driver($provider)->user();
            $isRegister = session('social_action') === 'register';

            if ($user->getEmail() || $provider === 'twitter') {

                $successMsg = __('Berhasil masuk ke dalam sistem');

                if ($provider === 'twitter') {
                    $userModel = $this->userRepository->findByTwitterId($user->getId());
                } else {
                    $userModel = $this->userRepository->findByEmail($email = $user->getEmail());
                }

                if ($isRegister || ($isRegister && $provider === 'twitter')) {
                    session(['social_action' => null]);
                    if ($userModel) {
                        $msg = $provider === 'twitter' ? __('Akun anda sudah terdaftar, silakan menggunakan form login') : __('Akun ' . $email . ' sudah terdaftar');
                        return redirect()->route('register')->with('errorMessage', $msg);
                    }

                    $data = [
                        'name'                 => $user->getName(),
                        'email'                => $user->getEmail(),
                        'avatar'               => $user->getAvatar(),
                        'email_verified_at'    => date('Y-m-d H:i:s'),
                        'password'             => bcrypt(Str::random(10)),
                        'last_login'           => date('Y-m-d H:i:s'),
                        'last_password_change' => date('Y-m-d H:i:s'),
                        'twitter_id'           => $user->getId(),
                    ];
                    $userModel = $this->userRepository->create($data);
                    $userModel->syncRoles(['admin']);

                    $successMsg = __('Berhasil mendaftar dan masuk ke dalam sistem');
                }

                if ($userModel === null) {
                    $msg = $provider === 'twitter' ? __('Akun anda belum terdaftar') : __('Akun ' . $email . ' belum terdaftar');
                    return redirect()->route('login')->with('errorMessage', $msg);
                }

                $this->userRepository->login($userModel);
                return Helper::redirectSuccess(route('dashboard.index'), $successMsg);
            }
            return redirect()->route('login')->with('errorMessage', __('Akun tidak ditemukan'));
        } catch (Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }
            return redirect()->route('login')->with('errorMessage', __('Ada error'));
        }
    }

    /**
     * social register
     *
     * @return Response
     */
    public function socialRegister($provider)
    {
        if (!in_array($provider, $this->socialiteProviders)) {
            abort(404);
        }

        $isValid = false;
        if ($provider === 'facebook') {
            $isValid = $this->settingRepository->isRegisterWithFacebook();
        } else if ($provider === 'google') {
            $isValid = $this->settingRepository->isRegisterWithGoogle();
        } else if ($provider === 'twitter') {
            $isValid = $this->settingRepository->isRegisterWithTwitter();
        } else if ($provider === 'github') {
            $isValid = $this->settingRepository->isRegisterWithGithub();
        }

        if ($isValid) {
            session(['social_action' => 'register']);
            return Socialite::driver($provider)->redirect();
        }

        abort(404);
    }
}
