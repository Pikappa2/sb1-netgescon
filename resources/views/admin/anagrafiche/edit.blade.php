<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Modifica Anagrafica: ') }} {{ $anagrafica->ragione_sociale ?: $anagrafica->cognome . ' ' . $anagrafica->nome }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('admin.anagrafiche.update', $anagrafica) }}">
                        @method('PUT')
                        @include('admin.anagrafiche._form', ['anagrafica' => $anagrafica, 'tipi_anagrafica' => $tipi_anagrafica, 'unita_disponibili' => $unita_disponibili])
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Modifica Anagrafica: ') }} {{ $anagrafica->ragione_sociale ?: $anagrafica->cognome . ' ' . $anagrafica->nome }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('admin.anagrafiche.update', $anagrafica) }}">
                        @method('PUT')
                        @include('admin.anagrafiche._form', ['anagrafica' => $anagrafica, 'tipi_anagrafica' => $tipi_anagrafica, 'unita_disponibili' => $unita_disponibili])
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
