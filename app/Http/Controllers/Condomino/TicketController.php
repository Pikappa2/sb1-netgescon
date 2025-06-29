<?php

namespace App\Http\Controllers\Condomino;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\CategoriaTicket;
use App\Models\UnitaImmobiliare;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $tickets = Ticket::where('soggetto_richiedente_id', $user->soggetto->id_soggetto ?? null)
            ->with(['stabile', 'categoriaTicket', 'unitaImmobiliare'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('condomino.tickets.index', compact('tickets'));
    }

    public function create()
    {
        $user = Auth::user();
        
        // Unità immobiliari dell'utente
        $unitaImmobiliari = UnitaImmobiliare::whereHas('proprieta', function($q) use ($user) {
            $q->where('soggetto_id', $user->soggetto->id_soggetto ?? null);
        })->with('stabile')->get();

        $categorieTicket = CategoriaTicket::all();

        return view('condomino.tickets.create', compact('unitaImmobiliari', 'categorieTicket'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'unita_immobiliare_id' => 'required|exists:unita_immobiliari,id_unita',
            'categoria_ticket_id' => 'nullable|exists:categorie_ticket,id',
            'titolo' => 'required|string|max:255',
            'descrizione' => 'required|string',
            'luogo_intervento' => 'nullable|string|max:255',
            'priorita' => 'required|in:Bassa,Media,Alta,Urgente',
            'allegati.*' => 'nullable|file|max:10240', // 10MB per file
        ]);

        // Verifica che l'unità appartenga all'utente
        $unitaImmobiliare = UnitaImmobiliare::whereHas('proprieta', function($q) use ($user) {
            $q->where('soggetto_id', $user->soggetto->id_soggetto ?? null);
        })->findOrFail($request->unita_immobiliare_id);

        $ticket = Ticket::create([
            'stabile_id' => $unitaImmobiliare->stabile_id,
            'unita_immobiliare_id' => $request->unita_immobiliare_id,
            'soggetto_richiedente_id' => $user->soggetto->id_soggetto,
            'categoria_ticket_id' => $request->categoria_ticket_id,
            'aperto_da_user_id' => $user->id,
            'titolo' => $request->titolo,
            'descrizione' => $request->descrizione,
            'luogo_intervento' => $request->luogo_intervento,
            'data_apertura' => now(),
            'stato' => 'Aperto',
            'priorita' => $request->priorita,
        ]);

        // Gestione allegati
        if ($request->hasFile('allegati')) {
            foreach ($request->file('allegati') as $file) {
                $path = $file->store('ticket-allegati', 'public');
                
                $ticket->documenti()->create([
                    'nome_file' => $file->getClientOriginalName(),
                    'path_file' => $path,
                    'tipo_documento' => 'Allegato Ticket',
                    'mime_type' => $file->getMimeType(),
                    'dimensione_file' => $file->getSize(),
                ]);
            }
        }

        return redirect()->route('condomino.tickets.index')
                        ->with('success', 'Ticket creato con successo.');
    }

    public function show(Ticket $ticket)
    {
        $user = Auth::user();
        
        // Verifica che il ticket appartenga all'utente
        if ($ticket->soggetto_richiedente_id !== $user->soggetto->id_soggetto ?? null) {
            abort(403);
        }

        $ticket->load(['stabile', 'categoriaTicket', 'unitaImmobiliare', 'documenti']);

        return view('condomino.tickets.show', compact('ticket'));
    }
}