<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Amministratore;
use App\Models\User;
use Spatie\Permission\Models\Role; // Aggiunto per Role
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth; // Aggiunto per Auth
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Gate; // Aggiunto per Gate

class AmministratoreController extends Controller
{
    public function __construct()
    {
        // Proteggi le rotte con i permessi di Spatie
        $this->middleware('permission:view-amministratori', ['only' => ['index']]); // Permesso per visualizzare la lista
        $this->middleware('permission:manage-amministratori', ['except' => ['index', 'show']]); // Permesso per tutte le altre azioni CRUD
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Gate::authorize('view-amministratori'); // Il middleware nel costruttore è sufficiente
        $amministratori = Amministratore::with('user')->paginate(10);
        return view('superadmin.amministratori.index', compact('amministratori'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Gate::authorize('manage-amministratori'); // Il middleware nel costruttore è sufficiente
        $usersWithoutAdminRole = User::doesntHave('amministratore')->get(); // Utenti non ancora associati a un amministratore
        return view('superadmin.amministratori.create', compact('usersWithoutAdminRole'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Gate::authorize('manage-amministratori'); // Il middleware nel costruttore è sufficiente
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'nome' => 'required|string|max:255',
            'cognome' => 'required|string|max:255',
            'denominazione_studio' => 'nullable|string|max:255',
            'partita_iva' => 'nullable|string|max:20|unique:amministratori,partita_iva',
            'codice_fiscale_studio' => 'nullable|string|max:20',
            'indirizzo_studio' => 'nullable|string|max:255',
            'cap_studio' => 'nullable|string|max:10',
            'citta_studio' => 'nullable|string|max:60',
            'provincia_studio' => 'nullable|string|max:2',
            'telefono_studio' => 'nullable|string|max:20',
            'email_studio' => 'nullable|string|email|max:255',
            'pec_studio' => 'nullable|string|email|max:255',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verified_at' => now(),
        ]);
        $user->assignRole('admin'); // Assegna il ruolo 'admin' al nuovo utente per coerenza con le rotte

        Amministratore::create([
            'user_id' => $user->id,
            'nome' => $request->nome,
            'cognome' => $request->cognome,
            'denominazione_studio' => $request->denominazione_studio,
            'partita_iva' => $request->partita_iva,
            'codice_fiscale_studio' => $request->codice_fiscale_studio,
            'indirizzo_studio' => $request->indirizzo_studio,
            'cap_studio' => $request->cap_studio,
            'citta_studio' => $request->citta_studio,
            'provincia_studio' => $request->provincia_studio,
            'telefono_studio' => $request->telefono_studio,
            'email_studio' => $request->email_studio,
            'pec_studio' => $request->pec_studio,
        ]);

        return redirect()->route('superadmin.amministratori.index')->with('success', 'Amministratore creato con successo.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Amministratore $amministratore) // Aggiunto metodo edit
    {
        // Gate::authorize('manage-amministratori'); // Il middleware nel costruttore è sufficiente
        // Recupera gli utenti che non sono ancora collegati a un record Amministratore
        $usersWithoutAdminRole = User::doesntHave('amministratore')->get();
        // Includi l'utente attualmente collegato a questo amministratore nella lista
        $usersWithoutAdminRole = $usersWithoutAdminRole->merge([$amministratore->user]);
        return view('superadmin.amministratori.edit', compact('amministratore', 'usersWithoutAdminRole'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Amministratore $amministratore)
    {
        // Gate::authorize('manage-amministratori'); // Il middleware nel costruttore è sufficiente
        $request->validate([
            'user_id' => 'required|exists:users,id|unique:amministratori,user_id,' . $amministratore->id_amministratore . ',id_amministratore',
            'nome' => 'required|string|max:255',
            'cognome' => 'required|string|max:255',
            'denominazione_studio' => 'nullable|string|max:255',
            'partita_iva' => ['nullable', 'string', 'max:20', Rule::unique('amministratori')->ignore($amministratore->id_amministratore, 'id_amministratore')], // Corretto id a id_amministratore
            'codice_fiscale_studio' => 'nullable|string|max:20',
            'indirizzo_studio' => 'nullable|string|max:255',
            'cap_studio' => 'nullable|string|max:10',
            'citta_studio' => 'nullable|string|max:255',
            'provincia_studio' => 'nullable|string|max:2',
            'telefono_studio' => 'nullable|string|max:20',
            'email_studio' => 'nullable|email|max:255',
            'pec_studio' => 'nullable|email|max:255',
        ]);

        // Aggiorna i dati dell'amministratore
        $amministratore->update($request->all());

        return redirect()->route('superadmin.amministratori.index')->with('success', 'Amministratore aggiornato con successo.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Amministratore $amministratore)
    {
        // Gate::authorize('manage-amministratori'); // Il middleware nel costruttore è sufficiente
        $amministratore->user->delete(); // Elimina anche l'utente associato
        $amministratore->delete();
        return redirect()->route('superadmin.amministratori.index')->with('success', 'Amministratore eliminato con successo.');
    }
}