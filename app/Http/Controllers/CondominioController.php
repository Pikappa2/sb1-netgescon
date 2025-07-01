<?php

namespace App\Http\Controllers;

use App\Models\Condominio;
use App\Models\Amministratore;
use Illuminate\Http\Request;

class CondominioController extends Controller
{
    public function __construct()
    {
        // Proteggi le rotte con i permessi di Spatie
        $this->middleware('permission:view-condomini', ['only' => ['index', 'show']]);
        $this->middleware('permission:create-condomini', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-condomini', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-condomini', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $condomini = Condominio::with('amministratore.user')->paginate(10);
        return view('condomini.index', compact('condomini'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $amministratori = Amministratore::all(); // Per la dropdown
        return view('condomini.create', compact('amministratori'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) // Qui useremo un FormRequest in futuro
    {
        $request->validate([
            'denominazione' => 'required|string|max:255',
            'id_amministratore' => 'required|exists:amministratori,id_amministratore',
            'indirizzo' => 'required|string|max:255',
            'cap' => 'required|string|max:5',
            'citta' => 'required|string|max:255',
            'provincia' => 'required|string|max:2',
            'codice_fiscale' => 'nullable|string|max:16|unique:condomini,codice_fiscale',
        ]);

        Condominio::create($request->all());

        return redirect()->route('condomini.index')->with('success', 'Condominio creato con successo.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Condominio $condominio)
    {
        return view('condomini.show', compact('condominio'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Condominio $condominio)
    {
        $amministratori = Amministratore::all();
        return view('condomini.edit', compact('condominio', 'amministratori'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Condominio $condominio) // Qui useremo un FormRequest in futuro
    {
        $request->validate([
            'denominazione' => 'required|string|max:255',
            'id_amministratore' => 'required|exists:amministratori,id_amministratore',
            'indirizzo' => 'required|string|max:255',
            'cap' => 'required|string|max:5',
            'citta' => 'required|string|max:255',
            'provincia' => 'required|string|max:2',
            'codice_fiscale' => 'nullable|string|max:16|unique:condomini,codice_fiscale,' . $condominio->id_condominio . ',id_condominio',
        ]);

        $condominio->update($request->all());

        return redirect()->route('condomini.index')->with('success', 'Condominio aggiornato con successo.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Condominio $condominio)
    {
        $condominio->delete();
        return redirect()->route('condomini.index')->with('success', 'Condominio eliminato con successo.');
    }
}