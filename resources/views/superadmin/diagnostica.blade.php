<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Pannello di Diagnostica
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-4">

                    <h3 class="text-lg font-bold border-b pb-2">Diagnostica Utente Autenticato</h3>
                    @if(Auth::check())
                        <div>
                            <span class="font-semibold">Nome Utente:</span>
                            <span>{{ Auth::user()->name }}</span>
                        </div>
                        <div>
                            <span class="font-semibold">Ruolo nel Database:</span>
                            <span class="font-mono bg-gray-100 px-2 py-1 rounded">{{ Auth::user()->role }}</span>
                        </div>

                        <h3 class="text-lg font-bold border-b pb-2 mt-6">Diagnostica Permessi (Gate)</h3>

                        <div>
                            <span class="font-semibold">Può accedere al pannello Super-Admin?</span>
                            @can('access-super-admin-panel')
                                <span class="font-bold text-green-600">SÌ</span>
                            @else
                                <span class="font-bold text-red-600">NO</span>
                            @endcan
                        </div>
                        <div>
                            <span class="font-semibold">Può accedere al pannello Admin?</span>
                             @can('access-admin-panel')
                                <span class="font-bold text-green-600">SÌ</span>
                            @else
                                <span class="font-bold text-red-600">NO</span>
                            @endcan
                        </div>
                    @else
                        <p class="text-red-600">Nessun utente autenticato.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
