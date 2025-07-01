<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fornitore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FornitoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fornitori = Fornitore::where('amministratore_id', Auth::user()->amministratore->id_amministratore ?? null)
                             ->paginate(10);
        
        return view('admin.fornitori.index', compact('fornitori'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.fornitori.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'ragione_sociale' => 'required|string|max:255',
            'partita_iva' => 'nullable|string|max:20|unique:fornitori,partita_iva',
            'codice_fiscale' => 'nullable|string|max:20|unique:fornitori,codice_fiscale',
            'indirizzo' => 'nullable|string|max:255',
            'cap' => 'nullable|string|max:10',
            'citta' => 'nullable|string|max:60',
            'provincia' => 'nullable|string|max:2',
            'email' => 'nullable|email|max:255|unique:fornitori,email',
            'pec' => 'nullable|email|max:255',
            'telefono' => 'nullable|string|max:50',
            'old_id' => 'nullable|integer|unique:fornitori,old_id',
        ]);

        $fornitore = Fornitore::create([
            'amministratore_id' => Auth::user()->amministratore->id_amministratore ?? null,
            'ragione_sociale' => $request->ragione_sociale,
            'partita_iva' => $request->partita_iva,
            'codice_fiscale' => $request->codice_fiscale,
            'indirizzo' => $request->indirizzo,
            'cap' => $request->cap,
            'citta' => $request->citta,
            'provincia' => $request->provincia,
            'email' => $request->email,
            'pec' => $request->pec,
            'telefono' => $request->telefono,
            'old_id' => $request->old_id,
        ]);

        return redirect()->route('admin.fornitori.index')
                        ->with('success', 'Fornitore creato con successo.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Fornitore $fornitore)
    {
        // Verifica che l'utente possa accedere a questo fornitore
        if ($fornitore->amministratore_id !== Auth::user()->amministratore->id_amministratore ?? null) {
            abort(403);
        }

        return view('admin.fornitori.show', compact('fornitore'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Fornitore $fornitore)
    {
        // Verifica che l'utente possa modificare questo fornitore
        if ($fornitore->amministratore_id !== Auth::user()->amministratore->id_amministratore ?? null) {
            abort(403);
        }

        return view('admin.fornitori.edit', compact('fornitore'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Fornitore $fornitore)
    {
        // Verifica che l'utente possa modificare questo fornitore
        if ($fornitore->amministratore_id !== Auth::user()->amministratore->id_amministratore ?? null) {
            abort(403);
        }

        $request->validate([
            'ragione_sociale' => 'required|string|max:255',
            'partita_iva' => 'nullable|string|max:20|unique:fornitori,partita_iva,' . $fornitore->id_fornitore . ',id_fornitore',
            'codice_fiscale' => 'nullable|string|max:20|unique:fornitori,codice_fiscale,' . $fornitore->id_fornitore . ',id_fornitore',
            'indirizzo' => 'nullable|string|max:255',
            'cap' => 'nullable|string|max:10',
            'citta' => 'nullable|string|max:60',
            'provincia' => 'nullable|string|max:2',
            'email' => 'nullable|email|max:255|unique:fornitori,email,' . $fornitore->id_fornitore . ',id_fornitore',
            'pec' => 'nullable|email|max:255',
            'telefono' => 'nullable|string|max:50',
            'old_id' => 'nullable|integer|unique:fornitori,old_id,' . $fornitore->id_fornitore . ',id_fornitore',
        ]);

        $fornitore->update($request->all());

        return redirect()->route('admin.fornitori.index')
                        ->with('success', 'Fornitore aggiornato con successo.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Fornitore $fornitore)
    {
        // Verifica che l'utente possa eliminare questo fornitore
        if ($fornitore->amministratore_id !== Auth::user()->amministratore->id_amministratore ?? null) {
            abort(403);
        }

        $fornitore->delete();

        return redirect()->route('admin.fornitori.index')
                        ->with('success', 'Fornitore eliminato con successo.');
    }
}