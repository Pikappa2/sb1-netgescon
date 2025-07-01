<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}

        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{-- Questa dashboard funge da reindirizzamento centrale in base al ruolo --}}
                    @auth
                        @if(Auth::user()->hasRole('super-admin'))
                            <script>window.location = "{{ route('superadmin.dashboard') }}";</script>
                        @elseif(Auth::user()->hasRole(['admin', 'amministratore']))
                            <script>window.location = "{{ route('admin.dashboard') }}";</script>
                        @elseif(Auth::user()->hasRole('condomino'))
                            <script>window.location = "{{ route('condomino.dashboard') }}";</script>
                        @else
                            <p>{{ __("You're logged in!") }}</p>
                            <p>Nessun pannello specifico per il tuo ruolo.</p>
                        @endif
                    @else
                        <p>{{ __("You are not logged in.") }}</p>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

