<?php

namespace App\Http\Middleware;

use App\Repositories\SettingRepository;
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
        config(['captcha.sitekey' => SettingRepository::googleCaptchaSiteKey()]);
        config(['captcha.secret' => SettingRepository::googleCaptchaSecret()]);

        return $next($request);
    }
}
