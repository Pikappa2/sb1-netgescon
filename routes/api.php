<?php
use App\Http\Controllers\Api\ImportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Laravel per default fornisce una rotta di test, la lasciamo.
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// --- IL NOSTRO ENDPOINT DI IMPORTAZIONE ---
// Proteggiamo la rotta con 'auth:sanctum', che richiede un token API per l'accesso.
Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::post('/import/condominio', [ImportController::class, 'importCondominio'])->name('api.import.condominio');
    // In futuro aggiungeremo qui le rotte per importare le unità, i fornitori, etc.
    Route::post('/import/fornitore', [ImportController::class, 'importFornitore'])->name('api.import.fornitore');
    Route::post('/import/anagrafica', [ImportController::class, 'importAnagrafica'])->name('api.import.anagrafica');
});
