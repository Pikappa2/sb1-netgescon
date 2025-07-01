<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CondominioController;

/*
|--------------------------------------------------------------------------
Unchanged linesRoute::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Aggiungi qui altre rotte protette
    Route::resource('condomini', CondominioController::class);
});

