<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Modifica Unità Immobiliare: Scala ') }} {{ $unitaImmobiliare->scala ?? '-' }} {{ __(' Int. ') }} {{ $unitaImmobiliare->interno ?? '-' }}
            {{ __(' del Condominio ') }} {{ $unitaImmobiliare->condominio->nome }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('admin.unitaImmobiliari.update', $unitaImmobiliare) }}">
                        @method('PUT')
                        @include('admin.unitaImmobiliari._form', ['unitaImmobiliare' => $unitaImmobiliare, 'condomini' => $condomini, 'condominioSelezionato' => $condominioSelezionato])
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
