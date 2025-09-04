<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class SuperAdminRole
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
        if (!in_array(Auth::user()->type, ['super_admin'])) {
            return redirect(route('admin.dashboard.index'));
        }

        return $next($request);
    }
}
