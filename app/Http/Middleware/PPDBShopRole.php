<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class PPDBShopRole
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
        if (!in_array(Auth::user()->type, ['admin_ppdb', 'shop', 'admin', 'super_admin', 'ksp'])) {
            return redirect(route('admin.dashboard.index'));
        }

        return $next($request);
    }
}
