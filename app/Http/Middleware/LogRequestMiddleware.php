<?php

namespace App\Http\Middleware;

use App\Models\LogRequest;
use Closure;
use Illuminate\Http\Request;

class LogRequestMiddleware
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
        if ($request->path() !== 'serviceworker.js') {
            $roles = [];
            if (auth()->check()) {
                $roles = auth()->user()->roles->pluck('name')->toArray();
            } else if (config('jwt.secret') && auth('api')->check()) {
                $roles = auth('api')->user()->roles->pluck('name')->toArray();
            }
            $generalService = new \App\Services\GeneralService;
            LogRequest::create([
                'uri'          => $request->path(),
                'query_string' => $request->getQueryString(),
                'method'       => $request->method(),
                'request_data' => $request->all(),
                'ip'           => $request->ip(),
                'user_agent'   => $request->userAgent(),
                'user_id'      => auth()->id() ?? (config('jwt.secret') ? auth('api')->id() : null) ?? null,
                'roles'        => $roles,
                'browser'      => $generalService->getBrowser(),
                'platform'     => $generalService->getPlatform(),
                'device'       => $generalService->getDevice(),
            ]);
        }

        return $next($request);
    }
}
