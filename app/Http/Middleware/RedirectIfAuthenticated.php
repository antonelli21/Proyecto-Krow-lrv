<?php
 
namespace App\Http\Middleware;
 
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
 
class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()) {
            $rol = $request->user()->rol ?? 'estudiante';
 
            return match($rol) {
                'empresa'    => redirect()->route('empresa.home'),
                'admin'      => redirect()->route('admin.home'),
                default      => redirect()->route('estudiante.home'),
            };
        }
 
        return $next($request);
    }
}
 