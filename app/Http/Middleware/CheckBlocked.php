<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckBlocked
{
    public function handle(Request $request, Closure $next)
    {
        // Если авторизован и заблокирован
        if (Auth::check() && Auth::user()->is_blocked) {
            
            // Если пользователь запрашивает маршрут выхода (Logout), пускаем его! 
            // Иначе он навсегда застрянет на красном окне без возможности выйти из сессии.
            if ($request->routeIs('logout')) {
                return $next($request);
            }

            // Всем остальным заблокированным показываем красное окно
            return response()->view('errors.blocked', [], 403);
        }

        return $next($request);
    }
}