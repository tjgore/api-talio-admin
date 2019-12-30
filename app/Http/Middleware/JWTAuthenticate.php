<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Http\Middleware\Authenticate;

class JWTAuthenticate extends Authenticate
{
  
  /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param ?String $guard
     * 
     * @throws \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if($guard == 'admin') {
            auth()->shouldUse('admin');
            auth()->authenticate($request);
        } else {
            $this->authenticate($request);
        }

        return $next($request);
    }
}
