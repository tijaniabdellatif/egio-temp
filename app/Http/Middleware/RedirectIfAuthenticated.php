<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;

class RedirectIfAuthenticated
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            return route('v2.home');
        }
    }

    public function handle($request, Closure $next, ...$guards)
    {

        // check if authenticated
        if (auth()->check()) {
            return $this->authenticated($request, $guards);
        }

        return $next($request);
    }

    protected function authenticated($request, array $guards)
    {
        throw new AuthenticationException(
            'Authenticated.',
            $guards,
            $this->redirectTo($request)
        );
    }

}
