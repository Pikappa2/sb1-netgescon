<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\SuperAdmin\UserController as SuperAdminUserController;
use App\Http\Controllers\SuperAdmin\CategoriaTicketController;
use App\Http\Controllers\SuperAdmin\AmministratoreController as SuperAdminAmministratoreController;
use App\Http\Controllers\Admin\StabileController;
use App\Http\Controllers\Admin\SoggettoController;
use App\Http\Controllers\Admin\UnitaImmobiliareController;
use App\Http\Controllers\Admin\FornitoreController;
use App\Http\Controllers\Admin\TicketController;
use App\Http\Controllers\Admin\ContabilitaController;
use App\Http\Controllers\Admin\DocumentoController;
use App\Http\Controllers\Admin\PreventivoController;
use App\Http\Controllers\Admin\BilancioController;
use App\Http\Controllers\Condomino\DashboardController as CondominoDashboardController;
use App\Http\Controllers\Condomino\TicketController as CondominoTicketController;
use App\Http\Controllers\Condomino\DocumentoController as CondominoDocumentoController;
use App\Http\Controllers\Condomino\UnitaController as CondominoUnitaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\ImpostazioniController;
use App\Http\Controllers\Admin\ApiTokenController;
use App\Http\Controllers\Admin\RubricaController;

// --- Public Routes ---
Route::get('/', function () { return view('welcome'); });

// --- Authenticated Routes ---
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Generic Dashboard (redirects to the correct panel based on role)
    Route::get('/dashboard', function () { return view('dashboard'); })->name('dashboard');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- SUPER-ADMIN PANEL ---
    Route::middleware(['role:super-admin'])->prefix('superadmin')->name('superadmin.')->group(function () {
        Route::get('/', function() {
            return view('superadmin.dashboard');
        })->name('dashboard');

        // Gestione utenti
        Route::resource('users', SuperAdminUserController::class)->except(['show']);
        Route::patch('users/{user}/update-role', [SuperAdminUserController::class, 'updateRole'])->name('users.updateRole');
        Route::get('users/{user}/impersonate', [SuperAdminUserController::class, 'impersonate'])->name('users.impersonate');
        
        // Gestione Amministratori
        Route::resource('amministratori', SuperAdminAmministratoreController::class)
            ->except(['show'])
            ->parameters(['amministratori' => 'amministratore']);
        
        // Gestione Categorie Ticket
        Route::resource('categorie-ticket', CategoriaTicketController::class)->except(['show']);
        
        // Diagnostica
        Route::get('/diagnostica', function() { return view('superadmin.diagnostica'); })->name('diagnostica');
    });

    // --- ADMIN / AMMINISTRATORE PANEL ---
    Route::middleware(['role:admin|amministratore'])->prefix('admin')->name('admin.')->group(function () {
        // Dashboard dell'amministratore
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Rotte CRUD principali
        Route::resource('stabili', StabileController::class);
        Route::resource('stabili.unitaImmobiliari', UnitaImmobiliareController::class)->shallow();
        Route::resource('unitaImmobiliari', UnitaImmobiliareController::class)->only(['edit', 'update', 'destroy']);
        Route::resource('soggetti', SoggettoController::class);
        Route::resource('fornitori', FornitoreController::class);
        Route::resource('tickets', TicketController::class);

        // Gestione Documenti
        Route::resource('documenti', DocumentoController::class)->except(['edit', 'update']);
        Route::get('documenti/{documento}/download', [DocumentoController::class, 'download'])->name('documenti.download');

        // Gestione Preventivi
        Route::prefix('preventivi')->name('preventivi.')->group(function () {
            Route::get('/', [PreventivoController::class, 'index'])->name('index');
            Route::get('/create', [PreventivoController::class, 'create'])->name('create');
            Route::post('/', [PreventivoController::class, 'store'])->name('store');
            Route::get('/{preventivo}', [PreventivoController::class, 'show'])->name('show');
            Route::get('/{preventivo}/edit', [PreventivoController::class, 'edit'])->name('edit');
            Route::put('/{preventivo}', [PreventivoController::class, 'update'])->name('update');
            Route::post('/{preventivo}/approva', [PreventivoController::class, 'approva'])->name('approva');
            Route::post('/{preventivo}/genera-rate', [PreventivoController::class, 'generaRate'])->name('genera-rate');
            Route::get('/{preventivo}/storico', [PreventivoController::class, 'storicoModifiche'])->name('storico');
            Route::get('/pianificazione/dashboard', [PreventivoController::class, 'pianificazione'])->name('pianificazione');
        });

        // Gestione Bilanci e Consuntivi
        Route::prefix('bilanci')->name('bilanci.')->group(function () {
            Route::get('/', [BilancioController::class, 'index'])->name('index');
            Route::get('/create', [BilancioController::class, 'create'])->name('create');
            Route::post('/', [BilancioController::class, 'store'])->name('store');
            Route::get('/{bilancio}', [BilancioController::class, 'show'])->name('show');
            Route::get('/{bilancio}/edit', [BilancioController::class, 'edit'])->name('edit');
            Route::put('/{bilancio}', [BilancioController::class, 'update'])->name('update');
            Route::post('/{bilancio}/calcola-conguagli', [BilancioController::class, 'calcolaConguagli'])->name('calcola-conguagli');
            Route::post('/{bilancio}/genera-rate-conguaglio', [BilancioController::class, 'generaRateConguaglio'])->name('genera-rate-conguaglio');
            Route::post('/{bilancio}/quadratura', [BilancioController::class, 'quadratura'])->name('quadratura');
            Route::post('/{bilancio}/chiusura-esercizio', [BilancioController::class, 'chiusuraEsercizio'])->name('chiusura-esercizio');
            Route::get('/{bilancio}/storico', [BilancioController::class, 'storicoModifiche'])->name('storico');
            Route::get('/quadrature/dashboard', [BilancioController::class, 'quadratureDashboard'])->name('quadrature');
            Route::get('/conguagli/dashboard', [BilancioController::class, 'conguagliDashboard'])->name('conguagli');
            Route::get('/rimborsi/dashboard', [BilancioController::class, 'rimborsiDashboard'])->name('rimborsi');
            Route::get('/automazioni/dashboard', [BilancioController::class, 'automazioniDashboard'])->name('automazioni');
        });

        // Contabilità
        Route::prefix('contabilita')->name('contabilita.')->group(function () {
            Route::get('/', [ContabilitaController::class, 'index'])->name('index');
            Route::get('/movimenti', [ContabilitaController::class, 'movimenti'])->name('movimenti');
            Route::get('/registrazione', [ContabilitaController::class, 'registrazione'])->name('registrazione');
            Route::post('/registrazione', [ContabilitaController::class, 'storeRegistrazione'])->name('store-registrazione');
            Route::get('/import-xml', [ContabilitaController::class, 'importXml'])->name('import-xml');
            Route::post('/import-xml', [ContabilitaController::class, 'importXml'])->name('import-xml.store');
        });

        // Impostazioni e API Tokens
        Route::get('impostazioni', [ImpostazioniController::class, 'index'])->name('impostazioni.index');
        Route::post('impostazioni', [ImpostazioniController::class, 'store'])->name('impostazioni.store');
        Route::get('api-tokens', [ApiTokenController::class, 'index'])->name('api-tokens.index');
        Route::post('api-tokens', [ApiTokenController::class, 'store'])->name('api-tokens.store');
        Route::delete('api-tokens/{token_id}', [ApiTokenController::class, 'destroy'])->name('api-tokens.destroy');
        
        // Rubrica
        Route::get('rubrica', [RubricaController::class, 'index'])->name('rubrica.index');
    });

    // --- CONDOMINO PANEL ---
    Route::middleware(['role:condomino'])->prefix('condomino')->name('condomino.')->group(function () {
        // Dashboard
        Route::get('/', [CondominoDashboardController::class, 'index'])->name('dashboard');
        
        // Tickets
        Route::resource('tickets', CondominoTicketController::class)->only(['index', 'create', 'store', 'show']);
        
        // Documenti
        Route::get('/documenti', [CondominoDocumentoController::class, 'index'])->name('documenti.index');
<<<<<<< HEAD
        Route::get('/documenti/{documento}/download', [CondominoDocumentoController::class, 'download'])->name('documenti.download');
=======
>>>>>>> e913d05 (Primo commit dal server Linux: progetto funzionante e aggiornato)
        
        // Unità Immobiliari
        Route::get('/unita', [CondominoUnitaController::class, 'index'])->name('unita.index');
        Route::get('/unita/{unitaImmobiliare}', [CondominoUnitaController::class, 'show'])->name('unita.show');
        Route::post('/unita/{unitaImmobiliare}/richiesta-modifica', [CondominoUnitaController::class, 'richiestaModifica'])->name('unita.richiesta-modifica');
        
        // Pagamenti (placeholder)
        Route::view('/pagamenti', 'condomino.pagamenti.index')->name('pagamenti.index');
        
        // Altre viste placeholder
        Route::view('/scadenze', 'condomino.scadenze')->name('scadenze');
        Route::view('/comunicazioni', 'condomino.comunicazioni')->name('comunicazioni');
        Route::view('/avvisi', 'condomino.avvisi')->name('avvisi');
        Route::view('/guasti', 'condomino.guasti')->name('guasti');
        Route::view('/contabilita', 'condomino.contabilita')->name('contabilita');
        Route::view('/fornitori', 'condomino.fornitori')->name('fornitori');
        Route::view('/bacheca', 'condomino.bacheca')->name('bacheca');
        Route::view('/sondaggi', 'condomino.sondaggi')->name('sondaggi');
    });

    // --- DEBUG ROUTE FOR PERMISSIONS ---
    Route::get('/test-permissions', function() {
        $user = Auth::user();
        echo "<h1>Diagnostica Permessi per: " . $user->name . "</h1>";
        echo "<h2>Ruoli Assegnati:</h2>";
        echo "<ul>";
        foreach ($user->getRoleNames() as $role) {
            echo "<li>" . $role . "</li>";
        }
        echo "</ul>";
    });
});

// --- PUBLIC ROUTE TO LEAVE IMPERSONATION ---
Route::get('impersonate/leave', [\Lab404\Impersonate\Controllers\ImpersonateController::class, 'leave'])->name('impersonate.leave');

// --- AUTHENTICATION ROUTES ---
<<<<<<< HEAD
require __DIR__.'/auth.php';
=======
require __DIR__.'/auth.php';

Route::middleware(['auth'])->group(function () {
    Route::view('/contabilita/registrazione-test', 'contabilita.registrazione-test')
        ->name('contabilita.registrazione-test');
});
Route::get('/contabilita/registrazione-test', \App\Livewire\Contabilita\RegistrazioneTest::class)
    ->middleware(['auth'])
    ->name('contabilita.registrazione-test');
>>>>>>> e913d05 (Primo commit dal server Linux: progetto funzionante e aggiornato)
