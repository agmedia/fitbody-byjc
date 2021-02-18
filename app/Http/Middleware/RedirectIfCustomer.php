<?php

namespace App\Http\Middleware;

use Closure;
use Bouncer;

class RedirectIfCustomer
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
        if (Bouncer::is(auth()->user())->an('customer')) {
            return redirect()->route('index');
        }

        return $next($request);
    }
}
