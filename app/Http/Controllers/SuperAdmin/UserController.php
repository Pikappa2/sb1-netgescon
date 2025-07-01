<?php
namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    public function __construct()
    {   // Proteggi le rotte con permessi specifici per il Super Admin
        $this->middleware('permission:view-users', ['only' => ['index']]);
        $this->middleware('permission:create-users', ['only' => ['create', 'store']]);
        $this->middleware('permission:manage-users', ['only' => ['create', 'store', 'edit', 'update', 'destroy', 'updateRole']]);
        $this->middleware('permission:impersonate-users', ['only' => ['impersonate']]);
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $users = User::with('roles')->paginate(10);
        $roles = Role::all(); // Per la selezione dei ruoli nella vista
        return view('superadmin.users.index', compact('users', 'roles'));

    }

    public function create()
    {  // <-- QUESTA PARENTESI MANCAVA!
        $roles = Role::all(); // Definisci $roles qui
        return view('superadmin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255', // Aggiunto 'name' alla validazione
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|exists:roles,name',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($request->role);

        return redirect()->route('superadmin.users.index')->with('success', 'Utente creato con successo.');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        // Verifica che l'utente corrente abbia i permessi per modificare gli utenti
        return view('superadmin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {   
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user)],
            'role' => 'required|exists:roles,name',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        $user->syncRoles([$request->role]); // Assegna il nuovo ruolo all'utente

        return redirect()->route('superadmin.users.index')->with('success', 'Utente aggiornato con successo.');
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|exists:roles,name',
        ]);

        if ($user->id === auth()->id()) {
            return redirect()->route('superadmin.users.index')->with('error', 'Non puoi modificare il tuo stesso ruolo.');
        }

        $user->syncRoles([$request->role]); // Assegna il nuovo ruolo all'utente

        return redirect()->route('superadmin.users.index')->with('status', 'Ruolo utente aggiornato!');
    }

    public function destroy(User $user)
    {
        // Impedisci al Super Admin di eliminare il proprio account
        if ($user->id === Auth::id()) {
            return redirect()->route('superadmin.users.index')->with('error', 'Non puoi eliminare il tuo stesso account.');
        }
        $user->delete();

        return redirect()->route('superadmin.users.index')->with('success', 'Utente eliminato con successo.');
    }

    public function impersonate(User $user)
    {
        $impersonator = Auth::user();

        // Verifica che l'utente corrente possa impersonare e che l'utente target possa essere impersonato
        if (!Gate::allows('impersonate', $impersonator)) {
            return back()->with('error', 'Non hai i permessi per impersonare utenti.');
        }
        
        // Verifica se l'utente target può essere impersonato
        if (!Gate::allows('canBeImpersonated', $user)) {
            return back()->with('error', 'Questo utente non può essere impersonato.');
        }

        $impersonator->impersonate($user);
        return redirect('/dashboard')->with('status', 'Ora stai impersonando ' . $user->name);
    }
}
