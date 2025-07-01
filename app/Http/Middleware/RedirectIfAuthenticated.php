<?php

namespace App\Http\Middleware;


use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;


class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) { // Se l'utente è autenticato...
                $user = Auth::user(); // ...recupera l'utente

                // Reindirizza l'utente alla dashboard specifica del suo ruolo
                if ($user->hasRole('super-admin')) { return redirect()->route('superadmin.dashboard'); }
                if ($user->hasRole(['admin', 'amministratore'])) { return redirect()->route('admin.dashboard'); }
                if ($user->hasRole('condomino')) { return redirect()->route('condomino.dashboard'); }

                // Fallback per utenti autenticati senza un ruolo specifico o con un ruolo non gestito
                return redirect()->route('dashboard'); 
            }
        }
        // Se l'utente non è autenticato, continua con la richiesta
        // Questo permette di accedere alle rotte pubbliche come login, registrazione, ecc
        
        return $next($request); 
    }
}
