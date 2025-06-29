<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Documento;
use App\Models\Stabile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $amministratore_id = Auth::user()->amministratore->id_amministratore ?? null;
        
        $query = Documento::with('documentable')
            ->whereHasMorph('documentable', [Stabile::class], function($q) use ($amministratore_id) {
                $q->where('amministratore_id', $amministratore_id);
            });

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
        $tipiDocumento = Documento::whereHasMorph('documentable', [Stabile::class], function($q) use ($amministratore_id) {
            $q->where('amministratore_id', $amministratore_id);
        })->distinct()->pluck('tipo_documento')->filter();

        return view('admin.documenti.index', compact('documenti', 'tipiDocumento'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $amministratore_id = Auth::user()->amministratore->id_amministratore ?? null;
        $stabili = Stabile::where('amministratore_id', $amministratore_id)->attivi()->get();
        
        return view('admin.documenti.create', compact('stabili'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'documentable_type' => 'required|string',
            'documentable_id' => 'required|integer',
            'file' => 'required|file|max:10240', // 10MB max
            'tipo_documento' => 'required|string|max:100',
            'descrizione' => 'nullable|string',
        ]);

        $file = $request->file('file');
        $path = $file->store('documenti', 'public');
        
        Documento::create([
            'documentable_type' => $request->documentable_type,
            'documentable_id' => $request->documentable_id,
            'nome_file' => $file->getClientOriginalName(),
            'path_file' => $path,
            'tipo_documento' => $request->tipo_documento,
            'descrizione' => $request->descrizione,
            'mime_type' => $file->getMimeType(),
            'dimensione_file' => $file->getSize(),
            'hash_file' => hash_file('sha256', $file->path()),
        ]);

        return redirect()->route('admin.documenti.index')
                        ->with('success', 'Documento caricato con successo.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Documento $documento)
    {
        // Verifica accesso
        if ($documento->documentable_type === Stabile::class) {
            $stabile = $documento->documentable;
            if ($stabile->amministratore_id !== Auth::user()->amministratore->id_amministratore ?? null) {
                abort(403);
            }
        }

        return view('admin.documenti.show', compact('documento'));
    }

    /**
     * Download del documento
     */
    public function download(Documento $documento)
    {
        // Verifica accesso
        if ($documento->documentable_type === Stabile::class) {
            $stabile = $documento->documentable;
            if ($stabile->amministratore_id !== Auth::user()->amministratore->id_amministratore ?? null) {
                abort(403);
            }
        }

        if (!Storage::disk('public')->exists($documento->path_file)) {
            abort(404, 'File non trovato');
        }

        return Storage::disk('public')->download($documento->path_file, $documento->nome_file);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Documento $documento)
    {
        // Verifica accesso
        if ($documento->documentable_type === Stabile::class) {
            $stabile = $documento->documentable;
            if ($stabile->amministratore_id !== Auth::user()->amministratore->id_amministratore ?? null) {
                abort(403);
            }
        }

        // Elimina il file fisico
        if (Storage::disk('public')->exists($documento->path_file)) {
            Storage::disk('public')->delete($documento->path_file);
        }

        $documento->delete();

        return redirect()->route('admin.documenti.index')
                        ->with('success', 'Documento eliminato con successo.');
    }
}