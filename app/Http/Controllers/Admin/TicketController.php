<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Stabile;
use App\Models\CategoriaTicket;
use App\Models\User;
use App\Models\Fornitore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tickets = Ticket::with(['stabile', 'categoriaTicket', 'apertoUser', 'assegnatoUser', 'assegnatoFornitore'])
                        ->whereHas('stabile', function($query) {
                            $query->where('amministratore_id', Auth::user()->amministratore->id_amministratore ?? null);
                        })
                        ->orderBy('created_at', 'desc')
                        ->paginate(10);

        return view('admin.tickets.index', compact('tickets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $stabili = Stabile::where('amministratore_id', Auth::user()->amministratore->id_amministratore ?? null)
                         ->attivi()
                         ->get();
        
        $categorieTicket = CategoriaTicket::all();
        $users = User::role(['admin', 'amministratore'])->get();
        $fornitori = Fornitore::where('amministratore_id', Auth::user()->amministratore->id_amministratore ?? null)->get();

        return view('admin.tickets.create', compact('stabili', 'categorieTicket', 'users', 'fornitori'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'stabile_id' => 'required|exists:stabili,id_stabile',
            'categoria_ticket_id' => 'nullable|exists:categorie_ticket,id',
            'titolo' => 'required|string|max:255',
            'descrizione' => 'nullable|string',
            'luogo_intervento' => 'nullable|string|max:255',
            'stato' => 'required|in:Aperto,Preso in Carico,In Lavorazione,In Attesa Approvazione,In Attesa Ricambi,Risolto,Chiuso,Annullato',
            'priorita' => 'required|in:Bassa,Media,Alta,Urgente',
            'assegnato_a_user_id' => 'nullable|exists:users,id',
            'assegnato_a_fornitore_id' => 'nullable|exists:fornitori,id_fornitore',
            'data_scadenza_prevista' => 'nullable|date',
            'data_risoluzione_effettiva' => 'nullable|date',
            'data_chiusura_effettiva' => 'nullable|date',
        ]);

        $ticket = Ticket::create([
            'stabile_id' => $request->stabile_id,
            'categoria_ticket_id' => $request->categoria_ticket_id,
            'aperto_da_user_id' => Auth::id(),
            'assegnato_a_user_id' => $request->assegnato_a_user_id,
            'assegnato_a_fornitore_id' => $request->assegnato_a_fornitore_id,
            'titolo' => $request->titolo,
            'descrizione' => $request->descrizione,
            'luogo_intervento' => $request->luogo_intervento,
            'data_apertura' => now(),
            'data_scadenza_prevista' => $request->data_scadenza_prevista,
            'data_risoluzione_effettiva' => $request->data_risoluzione_effettiva,
            'data_chiusura_effettiva' => $request->data_chiusura_effettiva,
            'stato' => $request->stato,
            'priorita' => $request->priorita,
        ]);

        return redirect()->route('admin.tickets.index')
                        ->with('success', 'Ticket creato con successo.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
        // Verifica che l'utente possa accedere a questo ticket
        if ($ticket->stabile->amministratore_id !== Auth::user()->amministratore->id_amministratore ?? null) {
            abort(403);
        }

        $ticket->load(['stabile', 'categoriaTicket', 'apertoUser', 'assegnatoUser', 'assegnatoFornitore']);

        return view('admin.tickets.show', compact('ticket'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ticket $ticket)
    {
        // Verifica che l'utente possa modificare questo ticket
        if ($ticket->stabile->amministratore_id !== Auth::user()->amministratore->id_amministratore ?? null) {
            abort(403);
        }

        $stabili = Stabile::where('amministratore_id', Auth::user()->amministratore->id_amministratore ?? null)
                         ->attivi()
                         ->get();
        
        $categorieTicket = CategoriaTicket::all();
        $users = User::role(['admin', 'amministratore'])->get();
        $fornitori = Fornitore::where('amministratore_id', Auth::user()->amministratore->id_amministratore ?? null)->get();

        return view('admin.tickets.edit', compact('ticket', 'stabili', 'categorieTicket', 'users', 'fornitori'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ticket $ticket)
    {
        // Verifica che l'utente possa modificare questo ticket
        if ($ticket->stabile->amministratore_id !== Auth::user()->amministratore->id_amministratore ?? null) {
            abort(403);
        }

        $request->validate([
            'stabile_id' => 'required|exists:stabili,id_stabile',
            'categoria_ticket_id' => 'nullable|exists:categorie_ticket,id',
            'titolo' => 'required|string|max:255',
            'descrizione' => 'nullable|string',
            'luogo_intervento' => 'nullable|string|max:255',
            'stato' => 'required|in:Aperto,Preso in Carico,In Lavorazione,In Attesa Approvazione,In Attesa Ricambi,Risolto,Chiuso,Annullato',
            'priorita' => 'required|in:Bassa,Media,Alta,Urgente',
            'assegnato_a_user_id' => 'nullable|exists:users,id',
            'assegnato_a_fornitore_id' => 'nullable|exists:fornitori,id_fornitore',
            'data_scadenza_prevista' => 'nullable|date',
            'data_risoluzione_effettiva' => 'nullable|date',
            'data_chiusura_effettiva' => 'nullable|date',
        ]);

        $ticket->update($request->all());

        return redirect()->route('admin.tickets.index')
                        ->with('success', 'Ticket aggiornato con successo.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        // Verifica che l'utente possa eliminare questo ticket
        if ($ticket->stabile->amministratore_id !== Auth::user()->amministratore->id_amministratore ?? null) {
            abort(403);
        }

        $ticket->delete();

        return redirect()->route('admin.tickets.index')
                        ->with('success', 'Ticket eliminato con successo.');
    }
}