<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\options;
use Illuminate\Http\Request;

class PreventDelete
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        $options = options::whereExists(
            function($query) {
            $query->select("option_type.id")
                  ->from('option_type')
                  ->where('option_type.id','=','options.option_id');
        })->exists();

        if($options === true){

            return redirect()->back()->with('error');

        }
        else {
            return $next($request);
        }


    }
}
