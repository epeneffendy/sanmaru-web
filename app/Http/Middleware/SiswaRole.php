<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class SiswaRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Auth::guard('siswa')->user() || !Auth::guard('siswa')->user()->type == 'siswa') {
            return redirect(route('kantin.login'));
        }

        return $next($request);
    }
}
