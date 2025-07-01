<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Soggetto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate; // Aggiunto per Gate
use Illuminate\Validation\Rule;

class SoggettoController extends Controller
{
    public function __construct()
    {
        // Proteggi le rotte con permessi specifici per l'Admin
        $this->middleware('permission:view-soggetti', ['only' => ['index', 'show']]);
        $this->middleware('permission:manage-soggetti', ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('view-soggetti'); // Verifica permesso specifico
        $soggetti = Soggetto::paginate(10);
        return view('admin.soggetti.index', compact('soggetti'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('manage-soggetti'); // Verifica permesso specifico
        $tipi_anagrafica = ['proprietario', 'inquilino', 'usufruttuario', 'altro'];
        return view('admin.soggetti.create', compact('tipi_anagrafica'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('manage-soggetti'); // Verifica permesso specifico
        $request->validate([
            'nome' => 'nullable|string|max:255',
            'cognome' => 'nullable|string|max:255',
            'ragione_sociale' => 'nullable|string|max:255',
            'codice_fiscale' => 'nullable|string|max:16|unique:soggetti,codice_fiscale',
            'partita_iva' => 'nullable|string|max:11|unique:soggetti,partita_iva',
            'email' => 'nullable|email|max:255|unique:soggetti,email',
            'telefono' => 'nullable|string|max:20',
            'indirizzo' => 'nullable|string|max:255',
            'cap' => 'nullable|string|max:10',
            'citta' => 'nullable|string|max:60',
            'provincia' => 'nullable|string|max:2',
            'tipo' => 'required|in:proprietario,inquilino,usufruttuario,altro',
        ]);

        Soggetto::create($request->all());

        return redirect()->route('admin.soggetti.index')->with('success', 'Soggetto creato con successo.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Soggetto $soggetto)
    {
        Gate::authorize('view-soggetti'); // Verifica permesso specifico
        return view('admin.soggetti.show', compact('soggetto'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Soggetto $soggetto)
    {
        Gate::authorize('manage-soggetti'); // Verifica permesso specifico
         $tipi_anagrafica = ['proprietario', 'inquilino', 'usufruttuario', 'altro'];
        return view('admin.soggetti.edit', compact('soggetto', 'tipi_anagrafica'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Soggetto $soggetto)
    {
        Gate::authorize('manage-soggetti'); // Verifica permesso specifico
        $request->validate([
            'nome' => 'nullable|string|max:255',
            'cognome' => 'nullable|string|max:255',
            'ragione_sociale' => 'nullable|string|max:255',
            'codice_fiscale' => ['nullable', 'string', 'max:16', Rule::unique('soggetti', 'codice_fiscale')->ignore($soggetto->id_soggetto, 'id_soggetto')],
            'partita_iva' => ['nullable', 'string', 'max:11', Rule::unique('soggetti', 'partita_iva')->ignore($soggetto->id_soggetto, 'id_soggetto')],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('soggetti', 'email')->ignore($soggetto->id_soggetto, 'id_soggetto')],
            'telefono' => 'nullable|string|max:20',
            'indirizzo' => 'nullable|string|max:255',
            'cap' => 'nullable|string|max:10',
            'citta' => 'nullable|string|max:60',
            'provincia' => 'nullable|string|max:2',
            'tipo' => 'required|in:proprietario,inquilino,usufruttuario,altro',
        ]);

        $soggetto->update($request->all());

        return redirect()->route('admin.soggetti.index')->with('success', 'Soggetto aggiornato con successo.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Soggetto $soggetto)
    {
        Gate::authorize('manage-soggetti'); // Verifica permesso specifico
        $soggetto->delete();
        return redirect()->route('admin.soggetti.index')->with('success', 'Soggetto eliminato con successo.');
    }
}
