<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;


class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
       
        if (Auth::check()) {
            // Verifica si el id_rol del usuario es 1
            if (Auth::user()->id_rol == 1) {
                return $next($request); // Permite el acceso si es administrador
            }
        }

        return redirect('/'); // Redirige si no es administrador
    }
}
