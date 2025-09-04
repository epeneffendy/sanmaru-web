<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class ShopRole
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
        if (!in_array(Auth::user()->type, ['shop', 'admin', 'super_admin', 'ksp'])) {
            return redirect(route('admin.dashboard.index'));
        }

        return $next($request);
    }
}
