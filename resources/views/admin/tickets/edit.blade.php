<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Modifica Ticket') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Modifica: {{ $ticket->titolo }}</h3>
                        <a href="{{ route('admin.tickets.index') }}" 
                           class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Torna alla Lista
                        </a>
                    </div>

                    <form method="POST" action="{{ route('admin.tickets.update', $ticket) }}">
                        @csrf
                        @method('PUT')
                        @include('admin.tickets._form', ['ticket' => $ticket])
                        
                        <div class="flex items-center justify-end space-x-4 mt-6">
                            <x-secondary-button type="button" onclick="window.location='{{ route('admin.tickets.index') }}'">
                                {{ __('Annulla') }}
                            </x-secondary-button>
                            <x-primary-button>
                                {{ __('Aggiorna Ticket') }}
                            </x-primary-button>
                        </div>
                    </form>

                    @if ($errors->any())
                        <div class="mt-4 text-red-600 dark:text-red-400">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>