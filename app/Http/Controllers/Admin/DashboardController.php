<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stabile;
use App\Models\Ticket;
use App\Models\Rata;
use App\Models\Documento;
use App\Models\MovimentoContabile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $amministratore_id = Auth::user()->amministratore->id_amministratore ?? null;
        
        // Statistiche principali
        $stats = [
            'stabili_gestiti' => Stabile::where('amministratore_id', $amministratore_id)->count(),
            'stabili_attivi' => Stabile::where('amministratore_id', $amministratore_id)->where('stato', 'attivo')->count(),
            'ticket_aperti' => Ticket::whereHas('stabile', function($q) use ($amministratore_id) {
                $q->where('amministratore_id', $amministratore_id);
            })->whereIn('stato', ['Aperto', 'Preso in Carico', 'In Lavorazione'])->count(),
            'ticket_urgenti' => Ticket::whereHas('stabile', function($q) use ($amministratore_id) {
                $q->where('amministratore_id', $amministratore_id);
            })->where('priorita', 'Urgente')->whereIn('stato', ['Aperto', 'Preso in Carico'])->count(),
        ];

        // Ticket aperti da lavorare
        $ticketsAperti = Ticket::with(['stabile', 'categoriaTicket'])
            ->whereHas('stabile', function($q) use ($amministratore_id) {
                $q->where('amministratore_id', $amministratore_id);
            })
            ->whereIn('stato', ['Aperto', 'Preso in Carico', 'In Lavorazione'])
            ->orderBy('priorita', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Scadenze imminenti (prossimi 30 giorni)
        $scadenzeImminenti = collect(); // Placeholder per quando implementeremo le rate

        // Ultimi documenti caricati
        $ultimiDocumenti = Documento::with('documentable')
            ->whereHasMorph('documentable', [Stabile::class], function($q) use ($amministratore_id) {
                $q->where('amministratore_id', $amministratore_id);
            })
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Movimenti contabili recenti
        $ultimiMovimenti = MovimentoContabile::with(['stabile', 'fornitore'])
            ->whereHas('stabile', function($q) use ($amministratore_id) {
                $q->where('amministratore_id', $amministratore_id);
            })
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats', 
            'ticketsAperti', 
            'scadenzeImminenti', 
            'ultimiDocumenti',
            'ultimiMovimenti'
        ));
    }
}