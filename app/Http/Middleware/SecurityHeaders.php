<?php

namespace App\Http\Middleware;

use Closure;

class SecurityHeaders
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
        $response = $next($request);

        if (property_exists($response, 'headers') && $response->headers) {
            // Force HTTPS connection via HSTS
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
            
            // Prevent Clickjacking attacks
            $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
            
            // Prevent MIME-type sniffing
            $response->headers->set('X-Content-Type-Options', 'nosniff');
            
            // Enable XSS protection filter in browsers
            $response->headers->set('X-XSS-Protection', '1; mode=block');
            
            // Control referrer information sent to third party sites
            $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
            
            // Restrict browser features and hardware APIs
            $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');
        }

        return $response;
    }
}
