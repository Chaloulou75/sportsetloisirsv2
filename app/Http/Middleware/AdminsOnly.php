<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminsOnly
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $allowedEmails = ['tonio20@hotmail.fr', 'c.jeandey@gmail.com'];

        if (!auth()->check() || !in_array(auth()->user()->email, $allowedEmails)) {
            return redirect(route('welcome'))->with('error', 'Requête non autorisée');
        }
        return $next($request);
    }
}
