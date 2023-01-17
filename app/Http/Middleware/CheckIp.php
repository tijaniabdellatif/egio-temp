<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckIp
{


      private $ipadresses = ['169.61.124.250','127.0.0.1'];


    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        if ($request->is('api/*')) {


        }



    return $next($request);

    }
}
