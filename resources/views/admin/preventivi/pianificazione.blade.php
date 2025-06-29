<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Pianificazione Spese e Entrate') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Dashboard Cashflow -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Previsione Cashflow (Prossimi 6 Mesi)</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 border-b border-gray-200 dark:border-gray-600 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Mese
                                    </th>
                                    <th class="px-6 py-3 border-b border-gray-200 dark:border-gray-600 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Entrate Previste
                                    </th>
                                    <th class="px-6 py-3 border-b border-gray-200 dark:border-gray-600 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Uscite Previste
                                    </th>
                                    <th class="px-6 py-3 border-b border-gray-200 dark:border-gray-600 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Saldo Previsto
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($cashflow as $mese)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $mese['mese'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 dark:text-green-400">
                                            +€ {{ number_format($mese['entrate'], 2, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 dark:text-red-400">
                                            -€ {{ number_format($mese['uscite'], 2, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium {{ $mese['saldo'] >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                            {{ $mese['saldo'] >= 0 ? '+' : '' }}€ {{ number_format($mese['saldo'], 2, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Spese in Scadenza -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Spese in Scadenza (Prossimi 30 giorni)</h3>
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Nuova Spesa Pianificata
                        </button>
                    </div>
                    
                    <div class="space-y-4">
                        @forelse($speseInScadenza as $spesa)
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $spesa->descrizione }}
                                        </h4>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            {{ $spesa->stabile->denominazione }}
                                        </p>
                                        <div class="mt-2 flex items-center space-x-4">
                                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                € {{ number_format($spesa->importo_previsto, 2, ',', '.') }}
                                            </span>
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                                @switch($spesa->tipo)
                                                    @case('ricorrente') bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100 @break
                                                    @case('straordinaria') bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100 @break
                                                    @case('manutenzione') bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100 @break
                                                @endswitch">
                                                {{ ucfirst($spesa->tipo) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex flex-col items-end space-y-2">
                                        <span class="text-sm text-gray-500 dark:text-gray-400">
                                            Scadenza: {{ $spesa->data_scadenza_prevista->format('d/m/Y') }}
                                        </span>
                                        <div class="flex space-x-2">
                                            <button class="text-green-600 hover:text-green-900 text-sm">Conferma</button>
                                            <button class="text-blue-600 hover:text-blue-900 text-sm">Modifica</button>
                                            <button class="text-red-600 hover:text-red-900 text-sm">Annulla</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <p class="text-gray-500 dark:text-gray-400">Nessuna spesa in scadenza nei prossimi 30 giorni</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Grafici Cashflow -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Andamento Cashflow</h3>
                    <div class="h-64">
                        <canvas id="cashflowChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script per grafici -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('cashflowChart').getContext('2d');
        const cashflowData = @json($cashflow);
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: cashflowData.map(item => item.mese),
                datasets: [
                    {
                        label: 'Entrate',
                        data: cashflowData.map(item => item.entrate),
                        borderColor: 'rgb(34, 197, 94)',
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        tension: 0.1
                    },
                    {
                        label: 'Uscite',
                        data: cashflowData.map(item => item.uscite),
                        borderColor: 'rgb(239, 68, 68)',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        tension: 0.1
                    },
                    {
                        label: 'Saldo',
                        data: cashflowData.map(item => item.saldo),
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '€ ' + value.toLocaleString();
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': € ' + context.parsed.y.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    </script>
</x-app-layout>