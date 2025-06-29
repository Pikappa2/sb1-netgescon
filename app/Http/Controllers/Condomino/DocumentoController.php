<?php

namespace App\Http\Controllers\Condomino;

use App\Http\Controllers\Controller;
use App\Models\Documento;
use App\Models\UnitaImmobiliare;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentoController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Trova gli stabili delle unità dell'utente
        $stabiliIds = UnitaImmobiliare::whereHas('proprieta', function($q) use ($user) {
            $q->where('soggetto_id', $user->soggetto->id_soggetto ?? null);
        })->pluck('stabile_id')->unique();

        $query = Documento::whereHasMorph('documentable', ['App\Models\Stabile'], function($q) use ($stabiliIds) {
            $q->whereIn('id_stabile', $stabiliIds);
        })->with('documentable');

        // Filtri
        if ($request->filled('tipo_documento')) {
            $query->where('tipo_documento', $request->tipo_documento);
        }
        
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nome_file', 'like', '%' . $request->search . '%')
                  ->orWhere('descrizione', 'like', '%' . $request->search . '%');
            });
        }

        $documenti = $query->orderBy('created_at', 'desc')->paginate(20);
        
        // Tipi documento per filtro
        $tipiDocumento = Documento::whereHasMorph('documentable', ['App\Models\Stabile'], function($q) use ($stabiliIds) {
            $q->whereIn('id_stabile', $stabiliIds);
        })->distinct()->pluck('tipo_documento')->filter();

        return view('condomino.documenti.index', compact('documenti', 'tipiDocumento'));
    }

    public function download(Documento $documento)
    {
        $user = Auth::user();
        
        // Verifica accesso
        $stabiliIds = UnitaImmobiliare::whereHas('proprieta', function($q) use ($user) {
            $q->where('soggetto_id', $user->soggetto->id_soggetto ?? null);
        })->pluck('stabile_id')->unique();

        if ($documento->documentable_type === 'App\Models\Stabile') {
            if (!$stabiliIds->contains($documento->documentable_id)) {
                abort(403);
            }
        }

        if (!Storage::disk('public')->exists($documento->path_file)) {
            abort(404, 'File non trovato');
        }

        return Storage::disk('public')->download($documento->path_file, $documento->nome_file);
    }
}