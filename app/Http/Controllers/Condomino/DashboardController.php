<?php

namespace App\Http\Controllers\Condomino;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Rata;
use App\Models\Documento;
use App\Models\UnitaImmobiliare;
use App\Models\Proprieta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Trova le unità immobiliari associate all'utente
        $unitaImmobiliari = UnitaImmobiliare::whereHas('proprieta', function($q) use ($user) {
            $q->where('soggetto_id', $user->soggetto->id_soggetto ?? null);
        })->with(['stabile', 'proprieta.soggetto'])->get();

        // Statistiche principali
        $stats = [
            'unita_possedute' => $unitaImmobiliari->count(),
            'ticket_aperti' => Ticket::where('soggetto_richiedente_id', $user->soggetto->id_soggetto ?? null)
                ->whereIn('stato', ['Aperto', 'Preso in Carico', 'In Lavorazione'])->count(),
            'rate_scadute' => 0, // Implementeremo quando avremo le rate
            'documenti_disponibili' => Documento::whereHasMorph('documentable', ['App\Models\Stabile'], function($q) use ($unitaImmobiliari) {
                $q->whereIn('id_stabile', $unitaImmobiliari->pluck('stabile_id'));
            })->count(),
        ];

        // Ticket recenti
        $ticketRecenti = Ticket::where('soggetto_richiedente_id', $user->soggetto->id_soggetto ?? null)
            ->with(['stabile', 'categoriaTicket'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Rate in scadenza (placeholder)
        $rateInScadenza = collect();

        // Ultimi documenti
        $ultimiDocumenti = Documento::whereHasMorph('documentable', ['App\Models\Stabile'], function($q) use ($unitaImmobiliari) {
            $q->whereIn('id_stabile', $unitaImmobiliari->pluck('stabile_id'));
        })->orderBy('created_at', 'desc')->take(5)->get();

        return view('condomino.dashboard', compact(
            'stats', 
            'unitaImmobiliari', 
            'ticketRecenti', 
            'rateInScadenza', 
            'ultimiDocumenti'
        ));
    }
}