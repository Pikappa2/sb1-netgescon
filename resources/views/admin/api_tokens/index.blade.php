<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('API Tokens') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <div class="mb-6">
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Gestione API Tokens</h3>
                        <p class="text-gray-600 dark:text-gray-400 mt-2">Crea e gestisci i token di accesso per le API</p>
                    </div>

                    <!-- Form Creazione Token -->
                    <div class="bg-blue-50 dark:bg-blue-900/20 p-6 rounded-lg mb-8">
                        <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Crea Nuovo Token</h4>
                        <form method="POST" action="{{ route('admin.api-tokens.store') }}">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Nome Token -->
                                <div>
                                    <x-input-label for="token_name" :value="__('Nome Token')" />
                                    <x-text-input id="token_name" name="token_name" type="text" class="mt-1 block w-full" 
                                                  :value="old('token_name')" required 
                                                  placeholder="Es. API Mobile App, Integrazione CRM..." />
                                    <x-input-error class="mt-2" :messages="$errors->get('token_name')" />
                                </div>

                                <!-- Abilità -->
                                <div>
                                    <x-input-label for="abilities" :value="__('Abilità')" />
                                    <select id="abilities" name="abilities[]" multiple 
                                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                        <option value="read">Lettura</option>
                                        <option value="write">Scrittura</option>
                                        <option value="delete">Eliminazione</option>
                                        <option value="admin">Amministrazione</option>
                                    </select>
                                    <x-input-error class="mt-2" :messages="$errors->get('abilities')" />
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Tieni premuto Ctrl/Cmd per selezionare più opzioni</p>
                                </div>
                            </div>

                            <div class="mt-4">
                                <x-primary-button>
                                    {{ __('Crea Token') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>

                    <!-- Lista Token Esistenti -->
                    <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                        <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Token Esistenti</h4>
                        
                        @if(isset($tokens) && $tokens->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th class="px-6 py-3 border-b border-gray-200 dark:border-gray-600 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Nome
                                            </th>
                                            <th class="px-6 py-3 border-b border-gray-200 dark:border-gray-600 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Abilità
                                            </th>
                                            <th class="px-6 py-3 border-b border-gray-200 dark:border-gray-600 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Ultimo Utilizzo
                                            </th>
                                            <th class="px-6 py-3 border-b border-gray-200 dark:border-gray-600 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Creato il
                                            </th>
                                            <th class="px-6 py-3 border-b border-gray-200 dark:border-gray-600 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Azioni
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($tokens as $token)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $token->name }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                    @if($token->abilities)
                                                        @foreach(json_decode($token->abilities, true) as $ability)
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100 mr-1">
                                                                {{ ucfirst($ability) }}
                                                            </span>
                                                        @endforeach
                                                    @else
                                                        <span class="text-gray-500 dark:text-gray-400">Tutte</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                    {{ $token->last_used_at ? $token->last_used_at->format('d/m/Y H:i') : 'Mai utilizzato' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                    {{ $token->created_at->format('d/m/Y H:i') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <form method="POST" action="{{ route('admin.api-tokens.destroy', $token->id) }}" 
                                                          class="inline" 
                                                          onsubmit="return confirm('Sei sicuro di voler eliminare questo token? Questa azione non può essere annullata.')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                            Elimina
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <p class="text-gray-500 dark:text-gray-400">Nessun token API creato</p>
                                <p class="text-sm text-gray-400 dark:text-gray-500 mt-2">Crea il tuo primo token utilizzando il form sopra</p>
                            </div>
                        @endif
                    </div>

                    <!-- Informazioni di Sicurezza -->
                    <div class="bg-yellow-50 dark:bg-yellow-900/20 p-4 rounded-lg mt-6">
                        <h5 class="text-sm font-medium text-yellow-800 dark:text-yellow-200 mb-2">⚠️ Informazioni di Sicurezza</h5>
                        <ul class="text-sm text-yellow-700 dark:text-yellow-300 space-y-1">
                            <li>• I token API forniscono accesso completo al tuo account</li>
                            <li>• Non condividere mai i tuoi token con terze parti non autorizzate</li>
                            <li>• Elimina immediatamente i token che non utilizzi più</li>
                            <li>• Monitora regolarmente l'utilizzo dei tuoi token</li>
                        </ul>
                    </div>

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