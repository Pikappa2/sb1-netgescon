<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Gestione Token API') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Crea Nuovo Token API</h3>

                    @if (session('status'))
                        <div class="mb-4 font-medium text-sm {{ Str::startsWith(session('status'), 'Token API creato') ? 'text-green-600 dark:text-green-400 bg-green-100 dark:bg-green-700' : 'text-red-600 dark:text-red-400 bg-red-100 dark:bg-red-700' }} p-3 rounded-lg">
                            @if(Str::startsWith(session('status'), 'Token API creato'))
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="font-bold">Token API creato con successo!</p>
                                        <p>Copia il token seguente e conservalo in un luogo sicuro. Non sarà più visualizzabile.</p>
                                        <code id="apiToken" class="block bg-gray-200 dark:bg-gray-900 p-2 rounded mt-1 break-all">{{ Str::after(session('status'), 'Copia il token: ') }}</code>
                                    </div>
                                    <button onclick="copyTokenToClipboard()" class="ml-4 px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white text-xs font-semibold rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75">
                                        Copia
                                    </button>
                                </div>
                            @else
                                {{ session('status') }}
                            @endif
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.api-tokens.store') }}" class="mb-6">
                        @csrf
                        <div>
                            <x-input-label for="token_name" :value="__('Nome del Token')" />
                            <x-text-input id="token_name" name="token_name" type="text" class="mt-1 block w-full md:w-1/2" :value="old('token_name')" required autofocus />
                            <x-input-error :messages="$errors->get('token_name')" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <x-primary-button>
                                {{ __('Crea Token') }}
                            </x-primary-button>
                        </div>
                    </form>

                    <hr class="my-6 dark:border-gray-600">

                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Token Esistenti</h3>
                    @if($tokens->isNotEmpty())
                        <ul class="space-y-3">
                            @foreach ($tokens as $token)
                                <li class="flex flex-col sm:flex-row justify-between items-start sm:items-center p-4 border dark:border-gray-700 rounded-lg">
                                    <div class="mb-2 sm:mb-0">
                                        <span class="font-semibold">{{ $token->name }}</span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400 block sm:inline sm:ml-2">(Creato: {{ $token->created_at->format('d/m/Y') }}, Ultimo utilizzo: {{ $token->last_used_at ? $token->last_used_at->diffForHumans() : 'Mai' }})</span>
                                    </div>
                                    <form method="POST" action="{{ route('admin.api-tokens.destroy', $token->id) }}" onsubmit="return confirm('Sei sicuro di voler revocare questo token? Questa azione è irreversibile.');">
                                        @csrf
                                        @method('DELETE')
                                        <x-danger-button type="submit">
                                            {{ __('Revoca') }}
                                        </x-danger-button>
                                    </form>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p>Nessun token API creato.</p>
                    @endif
                </div>
        </div>
    </div>
    <script>
        function copyTokenToClipboard() {
            const tokenElement = document.getElementById('apiToken');
            if (tokenElement) {
                const token = tokenElement.innerText;
                navigator.clipboard.writeText(token).then(function() {
                    alert('Token copiato negli appunti!');
                }, function(err) {
                    alert('Errore durante la copia del token: ' + err);
                });
            }
        }
    </script>
</x-app-layout>
