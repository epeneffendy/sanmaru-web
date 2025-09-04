<?php

namespace App\Http\Middleware;

use Closure;

class EmployeeRole
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
        if (!in_array(Auth::user()->type, ['admin', 'super_admin', 'pegawai'])) {
            return redirect(route('admin.dashboard'));
        }

        return $next($request);
    }
}
