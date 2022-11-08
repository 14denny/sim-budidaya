<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ManagerOnly
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
        return in_array($request->session()->get('roleid'), [1, 2]) ? $next($request) : redirect(route('beranda.index'));
    }
}
