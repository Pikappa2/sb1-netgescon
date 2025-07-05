@extends('superadmin.layouts.app')

@section('content')
<div class="bg-white rounded shadow p-6 mt-8">
    <h2 class="text-2xl font-bold mb-4 text-indigo-700">Diagnostica Layout e Menu</h2>
    <ul class="mb-4 text-sm text-gray-700">
        <li><b>Lingua attiva:</b> {{ app()->getLocale() }}</li>
        <li><b>Utente autenticato:</b> {{ auth()->check() ? auth()->user()->name : 'Nessuno' }}</li>
        <li><b>Ruoli utente:</b> {{ auth()->check() ? implode(', ', auth()->user()->getRoleNames()->toArray()) : '-' }}</li>
        <li><b>Route attuale:</b> {{ Route::currentRouteName() }}</li>
        <li><b>Sidebar presente:</b> <span id="sidebar-check" class="font-mono"></span></li>
    </ul>
    <div class="border-t pt-4 mt-4">
        <h3 class="font-semibold mb-2">HTML aside (sidebar):</h3>
        <pre class="bg-gray-100 p-2 rounded text-xs overflow-x-auto">{!! htmlentities(view('components.menu.sidebar')->render()) !!}</pre>
    </div>
    <div class="border-t pt-4 mt-4">
        <h3 class="font-semibold mb-2">Suggerimenti:</h3>
        <ul class="list-disc ml-6 text-gray-600">
            <li>Se la sidebar è visibile con sfondo giallo, la struttura è corretta.</li>
            <li>Se la lingua è ancora inglese, verifica che <code>.env</code> abbia <b>APP_LOCALE=it</b> e che le cache siano svuotate.</li>
            <li>Se la sidebar non appare, controlla la console del browser per errori JS/CSS.</li>
        </ul>
    </div>
</div>
<script>
    // Verifica presenza sidebar nel DOM
    document.addEventListener('DOMContentLoaded', function() {
        var aside = document.querySelector('aside');
        document.getElementById('sidebar-check').textContent = aside ? 'PRESENTE' : 'ASSENTE';
    });
</script>
@endsection
