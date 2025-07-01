<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\CategoriaTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate; // Aggiunto per Gate

class CategoriaTicketController extends Controller
{
    public function __construct()
    {
        // Proteggi le rotte con permessi specifici per il Super Admin
        $this->middleware('permission:view-categorie-ticket', ['only' => ['index', 'show']]);
        $this->middleware('permission:manage-categorie-ticket', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categorieTicket = CategoriaTicket::paginate(10); // Variabile correttamente definita
        return view('superadmin.categorie_ticket.index', compact('categorieTicket')); // Passa la variabile con il nome corretto
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('superadmin.categorie_ticket.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255|unique:categorie_ticket,nome',
            'descrizione' => 'nullable|string|max:500',
        ]);

        CategoriaTicket::create($request->all());

        return redirect()->route('superadmin.categorie-ticket.index')->with('success', 'Categoria Ticket creata con successo.');
    }

    /**
     * Display the specified resource.
     */
    public function show(CategoriaTicket $categoriaTicket)
    {
        // Non useremo una vista show separata per le categorie ticket,
        // ma il metodo è richiesto dalle rotte resource.
        // Potresti reindirizzare o mostrare un messaggio di errore.
        return redirect()->route('superadmin.categorie-ticket.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CategoriaTicket $categoriaTicket)
    {
        return view('superadmin.categorie_ticket.edit', compact('categoriaTicket'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CategoriaTicket $categoriaTicket)
    {
        $request->validate([
            'nome' => 'required|string|max:255|unique:categorie_ticket,nome,' . $categoriaTicket->id,
            'descrizione' => 'nullable|string|max:500',
        ]);

        $categoriaTicket->update($request->all());

        return redirect()->route('superadmin.categorie-ticket.index')->with('success', 'Categoria Ticket aggiornata con successo.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CategoriaTicket $categoriaTicket)
    {
        // Prima di eliminare, considera se ci sono ticket associati a questa categoria.
        // Se sì, potresti voler impedire l'eliminazione o riassegnare i ticket.
        // Per ora, l'eliminazione è diretta.
        $categoriaTicket->delete();

        return redirect()->route('superadmin.categorie-ticket.index')->with('success', 'Categoria Ticket eliminata con successo.');
    }
}
