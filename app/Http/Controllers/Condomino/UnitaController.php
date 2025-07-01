<?php

namespace App\Http\Controllers\Condomino;

use App\Http\Controllers\Controller;
use App\Models\UnitaImmobiliare;
use App\Models\RichiestaModifica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UnitaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $unitaImmobiliari = UnitaImmobiliare::whereHas('proprieta', function($q) use ($user) {
            $q->where('soggetto_id', $user->soggetto->id_soggetto ?? null);
        })->with(['stabile', 'proprieta.soggetto'])->get();

        return view('condomino.unita.index', compact('unitaImmobiliari'));
    }

    public function show(UnitaImmobiliare $unitaImmobiliare)
    {
        $user = Auth::user();
        
        // Verifica accesso
        $hasAccess = $unitaImmobiliare->proprieta()
            ->where('soggetto_id', $user->soggetto->id_soggetto ?? null)
            ->exists();
            
        if (!$hasAccess) {
            abort(403);
        }

        $unitaImmobiliare->load(['stabile', 'proprieta.soggetto']);

        return view('condomino.unita.show', compact('unitaImmobiliare'));
    }

    public function richiestaModifica(Request $request, UnitaImmobiliare $unitaImmobiliare)
    {
        $user = Auth::user();
        
        // Verifica accesso
        $hasAccess = $unitaImmobiliare->proprieta()
            ->where('soggetto_id', $user->soggetto->id_soggetto ?? null)
            ->exists();
            
        if (!$hasAccess) {
            abort(403);
        }

        $request->validate([
            'tipo_modifica' => 'required|in:anagrafica,catastale,proprieta',
            'descrizione' => 'required|string',
            'dati_proposti' => 'required|array',
        ]);

        RichiestaModifica::create([
            'unita_immobiliare_id' => $unitaImmobiliare->id_unita,
            'soggetto_richiedente_id' => $user->soggetto->id_soggetto,
            'tipo_modifica' => $request->tipo_modifica,
            'descrizione' => $request->descrizione,
            'dati_attuali' => $unitaImmobiliare->toArray(),
            'dati_proposti' => $request->dati_proposti,
            'stato' => 'in_attesa',
        ]);

        return back()->with('success', 'Richiesta di modifica inviata con successo.');
    }
}