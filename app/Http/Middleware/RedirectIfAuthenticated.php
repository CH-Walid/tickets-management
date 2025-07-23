<?php

namespace App\Http\Middleware;

use App\Enums\RolesEnum;
use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();
                // Redirection selon le rôle
                if ($user && $user->role === \App\Enums\RolesEnum::ADMIN->value) {
                    return redirect()->route('admin.dashboard');
                } elseif ($user && $user->role === \App\Enums\RolesEnum::USER_SIMPLE->value) {
                    return redirect()->route('user.dashboard');
                } elseif ($user && $user->role === \App\Enums\RolesEnum::CHEF_TECHNICIEN->value) {
                    return redirect()->route('chef.dashboard');
                } elseif ($user && $user->role === \App\Enums\RolesEnum::TECHNICIEN->value) {
                    return redirect()->route('tech.dashboard');
                } else {
                    return redirect(\App\Providers\RouteServiceProvider::HOME);
                }
            }
        }
        // Si aucun utilisateur authentifié, on laisse passer (login, etc)
        return $next($request);
    }
}
