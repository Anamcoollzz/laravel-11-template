<?php

namespace App\Http\Middleware;

use App\Repositories\MenuRepository;
use App\Repositories\SettingRepository;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ViewShare
{

    /**
     * menuRepository
     *
     * @var MenuRepository
     */
    private MenuRepository $menuRepository;

    /**
     * constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->menuRepository = new MenuRepository;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->isMethod('GET')) {
            $user = Auth::user();
            $totalDay = \Carbon\Carbon::parse($user->last_password_change)->diffInDays(now());
            view()->share('_total_day_password', $totalDay);
            view()->share('_user', $user);

            // default value
            view()->share('_logo_url', asset('assets/images/logo.png'));
            view()->share('_company_name', "Nama Perusahaan");
            view()->share('_is_forgot_password_send_to_email', false);
            view()->share('_is_login_must_verified', false);
            view()->share('_is_active_register_page', false);
            view()->share('_is_login_with_google', false);
            view()->share('_is_login_with_facebook', false);
            view()->share('_is_login_with_twitter', false);
            view()->share('_is_login_with_github', false);
            view()->share('_meta_description', 'Meta Description');
            view()->share('_meta_keywords', "stisla, laravel 8 template, bootstrap 4");
            view()->share('_meta_author', "Hairul Anam");
            view()->share('_skin', "style");
            view()->share('_stisla_bg_login', asset('stisla/assets/img/unsplash/eberhard-grossgasteiger-1207565-unsplash.jpg'));
            view()->share('_city', "Jember");
            view()->share('_country', "Indonesia");
            view()->share('_stisla_bg_home', asset('stisla/assets/img/unsplash/andre-benz-1214056-unsplash.jpg'));
            view()->share('_app_description', "Ini hanyalah sistem biasa");
            view()->share('_stisla_sidebar_mini', "0");

            view()->share('isYajra', false);
            view()->share('isAjax', false);
            view()->share('isAjaxYajra', false);


            $settings = SettingRepository::settings();

            session($settings);
            foreach ($settings as $key => $value) {
                view()->share($key, $value);
            }
            $menus = $this->menuRepository->getMenus();
            view()->share('_sidebar_menus', $menus);

            view()->share('_my_notifications', (new \App\Repositories\NotificationRepository)->myUnReadNotif(20));
        }
        return $next($request);
    }
}
