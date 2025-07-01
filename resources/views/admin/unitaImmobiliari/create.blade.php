<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Nuova Unità Immobiliare') }}
            @if($condominioSelezionato)
                {{ __(' per ') }} {{ $condominioSelezionato->nome }}
            @endif
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('admin.unitaImmobiliari.store') }}">
                         @include('admin.unitaImmobiliari._form', ['condomini' => $condomini, 'condominio_id' => $condominio_id, 'condominioSelezionato' => $condominioSelezionato])
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
