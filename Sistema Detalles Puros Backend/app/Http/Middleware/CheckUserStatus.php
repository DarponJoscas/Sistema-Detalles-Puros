<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserStatus
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && !Auth::user()->estado_usuario) {
            Auth::logout();
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'message' => 'Tu cuenta está desactivada.'
                ], 403);
            }

            return redirect()->route('login')->with('error', 'Tu cuenta está desactivada. Por favor contacta al administrador.');
        }

        return $next($request);
    }
}