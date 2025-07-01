<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiTokenController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage-api-tokens');
    }
   
    public function index(Request $request)
    {
        $user = Auth::user();
        // Laravel Sanctum non fornisce un modo diretto per listare i token senza Jetstream/Fortify UI.
        // Solitamente si mostra un form per creare un nuovo token e si visualizza il token *solo una volta* dopo la creazione.
        // L'utente deve copiarlo e salvarlo.
        // Si possono elencare i token esistenti (senza mostrare il valore plain-text) per permetterne la revoca.
        $tokens = $user->tokens; // Collection di PersonalAccessToken

        return view('admin.api-tokens.index', ['tokens' => $tokens]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'token_name' => 'required|string|max:255',
        ]);

        $user = Auth::user();
        $tokenName = $request->input('token_name');
        
        // Puoi definire delle 'abilities' (permessi) per il token se necessario
        // $newToken = $user->createToken($tokenName, ['import:data']);
        $newToken = $user->createToken($tokenName);

        // IMPORTANTE: Il plainTextToken è visibile solo qui, subito dopo la creazione.
        // Dovrai passarlo alla vista e informare l'utente di copiarlo immediatamente.
        return back()->with('status', 'Token API creato con successo! Copia il token: ' . $newToken->plainTextToken);
    }

    public function destroy(Request $request, $tokenId)
    {
        $user = Auth::user();
        $user->tokens()->where('id', $tokenId)->delete();
        return back()->with('status', 'Token API revocato con successo.');
    }
}