<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class PetaniMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
   public function handle(Request $request, Closure $next)
    {
            if (Auth::check() && Auth::user()->role == 'petani') {
                return $next($request);
            }

            Auth::logout();
            return redirect('/')->with('error', 'Anda tidak memiliki akses petani');
    }
}
