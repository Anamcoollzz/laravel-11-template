<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use App\Repositories\SettingRepository;
use Barryvdh\Debugbar\Facades\Debugbar;
use Closure;
use Illuminate\Http\Request;

class OverrideConfig
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // config(['app.debug' => false]);
        // config(['debugbar.enabled' => null]);
        config(['app.is_demo' => Setting::firstOrCreate(['key' => 'app_is_demo'], ['value' => false])->value === '1']);
        $debug = Setting::firstOrCreate(['key' => 'debugbar'], ['value' => true])->value === '1';
        if ($debug) {
            Debugbar::enable();
        } else {
            Debugbar::disable();
        }
        config(['captcha.sitekey' => SettingRepository::googleCaptchaSiteKey()]);
        config(['captcha.secret' => SettingRepository::googleCaptchaSecret()]);

        return $next($request);
    }
}
