<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stabile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StabileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stabili = Stabile::where('amministratore_id', Auth::user()->amministratore->id_amministratore ?? null)
                         ->paginate(10);
        
        return view('admin.stabili.index', compact('stabili'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.stabili.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'denominazione' => 'required|string|max:255',
            'codice_fiscale' => 'nullable|string|max:20|unique:stabili,codice_fiscale',
            'cod_fisc_amministratore' => 'nullable|string|max:20',
            'indirizzo' => 'required|string|max:255',
            'citta' => 'required|string|max:255',
            'cap' => 'required|string|max:10',
            'provincia' => 'nullable|string|max:2',
            'stato' => 'required|in:attivo,inattivo',
            'note' => 'nullable|string',
            'old_id' => 'nullable|integer|unique:stabili,old_id',
        ]);

        $stabile = Stabile::create([
            'amministratore_id' => Auth::user()->amministratore->id_amministratore ?? null,
            'denominazione' => $request->denominazione,
            'codice_fiscale' => $request->codice_fiscale,
            'cod_fisc_amministratore' => $request->cod_fisc_amministratore,
            'indirizzo' => $request->indirizzo,
            'citta' => $request->citta,
            'cap' => $request->cap,
            'provincia' => $request->provincia,
            'stato' => $request->stato,
            'note' => $request->note,
            'old_id' => $request->old_id,
        ]);

        return redirect()->route('admin.stabili.index')
                        ->with('success', 'Stabile creato con successo.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Stabile $stabile)
    {
        // Verifica che l'utente possa accedere a questo stabile
        if ($stabile->amministratore_id !== Auth::user()->amministratore->id_amministratore ?? null) {
            abort(403);
        }

        return view('admin.stabili.show', compact('stabile'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Stabile $stabile)
    {
        // Verifica che l'utente possa modificare questo stabile
        if ($stabile->amministratore_id !== Auth::user()->amministratore->id_amministratore ?? null) {
            abort(403);
        }

        return view('admin.stabili.edit', compact('stabile'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Stabile $stabile)
    {
        // Verifica che l'utente possa modificare questo stabile
        if ($stabile->amministratore_id !== Auth::user()->amministratore->id_amministratore ?? null) {
            abort(403);
        }

        $request->validate([
            'denominazione' => 'required|string|max:255',
            'codice_fiscale' => 'nullable|string|max:20|unique:stabili,codice_fiscale,' . $stabile->id_stabile . ',id_stabile',
            'cod_fisc_amministratore' => 'nullable|string|max:20',
            'indirizzo' => 'required|string|max:255',
            'citta' => 'required|string|max:255',
            'cap' => 'required|string|max:10',
            'provincia' => 'nullable|string|max:2',
            'stato' => 'required|in:attivo,inattivo',
            'note' => 'nullable|string',
            'old_id' => 'nullable|integer|unique:stabili,old_id,' . $stabile->id_stabile . ',id_stabile',
        ]);

        $stabile->update($request->all());

        return redirect()->route('admin.stabili.index')
                        ->with('success', 'Stabile aggiornato con successo.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Stabile $stabile)
    {
        // Verifica che l'utente possa eliminare questo stabile
        if ($stabile->amministratore_id !== Auth::user()->amministratore->id_amministratore ?? null) {
            abort(403);
        }

        $stabile->delete();

        return redirect()->route('admin.stabili.index')
                        ->with('success', 'Stabile eliminato con successo.');
    }
}