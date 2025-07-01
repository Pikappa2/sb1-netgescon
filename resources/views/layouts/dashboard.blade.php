<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Dashboard') }}</h2></x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium">Benvenuto, {{ Auth::user()->name }}!</h3>
                    <p class="mt-1 text-sm text-gray-600">Seleziona uno dei pannelli a cui hai accesso.</p>
                    <div class="mt-6 border-t border-gray-200 pt-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @can('access-super-admin-panel')
                            <a href="{{ route('superadmin.users.index') }}" class="block p-6 bg-gray-50 rounded-lg shadow hover:bg-gray-100 transition">
                                <h4 class="text-lg font-semibold text-gray-900">Pannello Super-Admin</h4>
                                <p class="mt-2 text-sm text-gray-600">Gestisci gli utenti e i loro ruoli.</p>
                            </a>
                            @endcan
                             @can('access-admin-panel')
                            <a href="{{ route('admin.stabili.index') }}" class="block p-6 bg-gray-50 rounded-lg shadow hover:bg-gray-100 transition">
                                <h4 class="text-lg font-semibold text-gray-900">Pannello Amministrazione</h4>
                                <p class="mt-2 text-sm text-gray-600">Gestisci i tuoi condomini e le unità.</p>
                            </a>
                            @endcan
                            @can('access-condomino-panel')
                            <a href="{{ route('condomino.dashboard') }}" class="block p-6 bg-gray-50 rounded-lg shadow hover:bg-gray-100 transition">
                                <h4 class="text-lg font-semibold text-gray-900">Il Mio Spazio</h4>
                                <p class="mt-2 text-sm text-gray-600">Visualizza le tue rate, i documenti e apri segnalazioni.</p>
                            </a>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
