<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UnitaImmobiliare;
use App\Models\Stabile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UnitaImmobiliareController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Stabile $stabile)
    {
        // Verifica che l'utente possa accedere a questo stabile
        if ($stabile->amministratore_id !== Auth::user()->amministratore->id_amministratore ?? null) {
            abort(403);
        }

        return view('admin.unita_immobiliari.create', compact('stabile'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Stabile $stabile)
    {
        // Verifica che l'utente possa accedere a questo stabile
        if ($stabile->amministratore_id !== Auth::user()->amministratore->id_amministratore ?? null) {
            abort(403);
        }

        $request->validate([
            'stabile_id' => 'required|exists:stabili,id_stabile',
            'interno' => 'nullable|string|max:255',
            'scala' => 'nullable|string|max:255',
            'piano' => 'nullable|string|max:255',
            'fabbricato' => 'nullable|string|max:255',
            'millesimi_proprieta' => 'nullable|numeric|min:0|max:9999.9999',
            'categoria_catastale' => 'nullable|string|max:255',
            'superficie' => 'nullable|numeric|min:0|max:99999999.99',
            'vani' => 'nullable|numeric|min:0|max:99.99',
            'indirizzo' => 'nullable|string|max:255',
            'note' => 'nullable|string',
        ]);

        $unitaImmobiliare = UnitaImmobiliare::create($request->all());

        return redirect()->route('admin.stabili.show', $stabile)
                        ->with('success', 'Unità immobiliare creata con successo.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UnitaImmobiliare $unitaImmobiliare)
    {
        // Verifica che l'utente possa modificare questa unità
        if ($unitaImmobiliare->stabile->amministratore_id !== Auth::user()->amministratore->id_amministratore ?? null) {
            abort(403);
        }

        return view('admin.unita_immobiliari.edit', compact('unitaImmobiliare'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UnitaImmobiliare $unitaImmobiliare)
    {
        // Verifica che l'utente possa modificare questa unità
        if ($unitaImmobiliare->stabile->amministratore_id !== Auth::user()->amministratore->id_amministratore ?? null) {
            abort(403);
        }

        $request->validate([
            'stabile_id' => 'required|exists:stabili,id_stabile',
            'interno' => 'nullable|string|max:255',
            'scala' => 'nullable|string|max:255',
            'piano' => 'nullable|string|max:255',
            'fabbricato' => 'nullable|string|max:255',
            'millesimi_proprieta' => 'nullable|numeric|min:0|max:9999.9999',
            'categoria_catastale' => 'nullable|string|max:255',
            'superficie' => 'nullable|numeric|min:0|max:99999999.99',
            'vani' => 'nullable|numeric|min:0|max:99.99',
            'indirizzo' => 'nullable|string|max:255',
            'note' => 'nullable|string',
        ]);

        $unitaImmobiliare->update($request->all());

        return redirect()->route('admin.stabili.show', $unitaImmobiliare->stabile)
                        ->with('success', 'Unità immobiliare aggiornata con successo.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UnitaImmobiliare $unitaImmobiliare)
    {
        // Verifica che l'utente possa eliminare questa unità
        if ($unitaImmobiliare->stabile->amministratore_id !== Auth::user()->amministratore->id_amministratore ?? null) {
            abort(403);
        }

        $stabile = $unitaImmobiliare->stabile;
        $unitaImmobiliare->delete();

        return redirect()->route('admin.stabili.show', $stabile)
                        ->with('success', 'Unità immobiliare eliminata con successo.');
    }
}