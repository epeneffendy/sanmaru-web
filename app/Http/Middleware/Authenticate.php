<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    protected $guards = [];
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$guards
     * @return mixed
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, Closure $next, ...$guards)
    {
        $this->guards = $guards;

        $this->authenticate($request, $guards);

        return $next($request);
    }


    /**
     * Determine if the user is logged in to student guards.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return bool
     */
    protected function isSiswa() : bool
    {
        return $this->guards && is_numeric(array_search('siswa', $this->guards));
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            if ($this->isSiswa()) {
                return route('login');
            }

            return route('admin.login');
        }
    }
}
