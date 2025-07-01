<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Soggetto;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RubricaController extends Controller
{
        public function __construct()
    {
        $this->middleware('permission:view-rubrica');
    }

    public function index(Request $request)
    {
        // Per la rubrica globale, l'amministratore dovrebbe vedere tutte le anagrafiche
        // a cui ha accesso tramite i suoi condomini.
        // Per semplicità iniziale, mostriamo tutte le anagrafiche.
        // In futuro, si potrebbe filtrare per anagrafiche associate a unità immobiliari
        // dei condomini gestiti dall'amministratore corrente.

        $query = Soggetto::query(); // La relazione con unità e stabili può essere caricata se necessario

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nome', 'like', "%{$search}%")
                  ->orWhere('cognome', 'like', "%{$search}%")
                  ->orWhere('ragione_sociale', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        $soggetti = $query->orderBy('ragione_sociale')->orderBy('cognome')->orderBy('nome')->paginate(20);
        return view('admin.rubrica.index', compact('soggetti'));
    }
}