<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PerPage
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
        $perPage = $request->input('per_page');
        if(empty($perPage)){
            $perPage = 10;
        }
        if(!is_numeric($perPage)){
            return redirect('error');
        }
        if($perPage < 10){
            $perPage = 10;
        }
        else if($perPage > 10 && $perPage < 30){
            $perPage = 30;
        }
        else if($perPage > 30 && $perPage < 50){
            $perPage  = 50;
        }
        else if($perPage > 50){
            $perPage = 50;
        }
        $request->merge([ 'per_page' => $perPage]);
        return $next($request);
    }
}
