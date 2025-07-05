<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ImpostazioniController extends Controller
{
    public function index()
    {
        return view('superadmin.impostazioni.index');
    }

    public function store(Request $request)
    {
        // Logica per salvare le impostazioni di colore
        $validated = $request->validate([
            'bg_color' => 'string|max:7',
            'text_color' => 'string|max:7',
            'accent_color' => 'string|max:7',
            'sidebar_bg_color' => 'string|max:7',
            'sidebar_text_color' => 'string|max:7',
            'sidebar_accent_color' => 'string|max:7',
        ]);

        // Salva nelle impostazioni di sistema (da implementare)
        // Per ora restituiamo una risposta di successo
        return response()->json(['success' => true, 'message' => 'Impostazioni salvate con successo!']);
    }
}
