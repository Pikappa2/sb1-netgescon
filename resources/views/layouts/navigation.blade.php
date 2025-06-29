<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    @role('super-admin')
                        <!-- Super Admin Menu -->
                        <x-nav-link :href="route('superadmin.dashboard')" :active="request()->routeIs('superadmin.dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                        <x-nav-link :href="route('superadmin.users.index')" :active="request()->routeIs('superadmin.users.*')">
                            {{ __('Utenti') }}
                        </x-nav-link>
                        <x-nav-link :href="route('superadmin.amministratori.index')" :active="request()->routeIs('superadmin.amministratori.*')">
                            {{ __('Amministratori') }}
                        </x-nav-link>
                        <x-nav-link :href="route('superadmin.categorie-ticket.index')" :active="request()->routeIs('superadmin.categorie-ticket.*')">
                            {{ __('Categorie Ticket') }}
                        </x-nav-link>
                    @endrole

                    @role('admin|amministratore')
                        <!-- Admin Menu -->
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                        
                        <!-- Dropdown Stabili -->
                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            <x-dropdown align="left" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                        <div>{{ __('Stabili') }}</div>
                                        <div class="ml-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <x-dropdown-link :href="route('admin.stabili.index')">
                                        {{ __('Elenco Stabili') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('admin.stabili.create')">
                                        {{ __('Nuovo Stabile') }}
                                    </x-dropdown-link>
                                </x-slot>
                            </x-dropdown>
                        </div>

                        <x-nav-link :href="route('admin.soggetti.index')" :active="request()->routeIs('admin.soggetti.*')">
                            {{ __('Soggetti') }}
                        </x-nav-link>
                        
                        <!-- Dropdown Gestione -->
                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            <x-dropdown align="left" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                        <div>{{ __('Gestione') }}</div>
                                        <div class="ml-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <x-dropdown-link :href="route('admin.tickets.index')">
                                        {{ __('Ticket') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('admin.fornitori.index')">
                                        {{ __('Fornitori') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('admin.documenti.index')">
                                        {{ __('Documenti') }}
                                    </x-dropdown-link>
                                </x-slot>
                            </x-dropdown>
                        </div>

                        <!-- Dropdown Contabilità -->
                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            <x-dropdown align="left" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                        <div>{{ __('Contabilità') }}</div>
                                        <div class="ml-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <x-dropdown-link :href="route('admin.contabilita.index')">
                                        {{ __('Dashboard Contabilità') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('admin.contabilita.registrazione')">
                                        {{ __('Nuova Registrazione') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('admin.contabilita.movimenti')">
                                        {{ __('Movimenti') }}
                                    </x-dropdown-link>
                                </x-slot>
                            </x-dropdown>
                        </div>

                        <!-- Dropdown Impostazioni -->
                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            <x-dropdown align="left" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                        <div>{{ __('Strumenti') }}</div>
                                        <div class="ml-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <x-dropdown-link :href="route('admin.rubrica.index')">
                                        {{ __('Rubrica') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('admin.impostazioni.index')">
                                        {{ __('Impostazioni') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('admin.api-tokens.index')">
                                        {{ __('API Tokens') }}
                                    </x-dropdown-link>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    @endrole

                    @role('condomino')
                        <!-- Condomino Menu -->
                        <x-nav-link :href="route('condomino.dashboard')" :active="request()->routeIs('condomino.dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                        <x-nav-link :href="route('condomino.scadenze')" :active="request()->routeIs('condomino.scadenze')">
                            {{ __('Scadenze') }}
                        </x-nav-link>
                        <x-nav-link :href="route('condomino.documenti')" :active="request()->routeIs('condomino.documenti')">
                            {{ __('Documenti') }}
                        </x-nav-link>
                        <x-nav-link :href="route('condomino.guasti')" :active="request()->routeIs('condomino.guasti')">
                            {{ __('Segnalazioni') }}
                        </x-nav-link>
                    @endrole
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ml-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        @impersonating
                            <x-dropdown-link :href="route('impersonate.leave')">
                                {{ __('Torna al tuo account') }}
                            </x-dropdown-link>
                        @endImpersonating

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @role('super-admin')
                <x-responsive-nav-link :href="route('superadmin.dashboard')" :active="request()->routeIs('superadmin.dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('superadmin.users.index')" :active="request()->routeIs('superadmin.users.*')">
                    {{ __('Utenti') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('superadmin.amministratori.index')" :active="request()->routeIs('superadmin.amministratori.*')">
                    {{ __('Amministratori') }}
                </x-responsive-nav-link>
            @endrole

            @role('admin|amministratore')
                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.stabili.index')" :active="request()->routeIs('admin.stabili.*')">
                    {{ __('Stabili') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.soggetti.index')" :active="request()->routeIs('admin.soggetti.*')">
                    {{ __('Soggetti') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.tickets.index')" :active="request()->routeIs('admin.tickets.*')">
                    {{ __('Ticket') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.fornitori.index')" :active="request()->routeIs('admin.fornitori.*')">
                    {{ __('Fornitori') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.documenti.index')" :active="request()->routeIs('admin.documenti.*')">
                    {{ __('Documenti') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.contabilita.index')" :active="request()->routeIs('admin.contabilita.*')">
                    {{ __('Contabilità') }}
                </x-responsive-nav-link>
            @endrole

            @role('condomino')
                <x-responsive-nav-link :href="route('condomino.dashboard')" :active="request()->routeIs('condomino.dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('condomino.scadenze')" :active="request()->routeIs('condomino.scadenze')">
                    {{ __('Scadenze') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('condomino.documenti')" :active="request()->routeIs('condomino.documenti')">
                    {{ __('Documenti') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('condomino.guasti')" :active="request()->routeIs('condomino.guasti')">
                    {{ __('Segnalazioni') }}
                </x-responsive-nav-link>
            @endrole
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                @impersonating
                    <x-responsive-nav-link :href="route('impersonate.leave')">
                        {{ __('Torna al tuo account') }}
                    </x-responsive-nav-link>
                @endImpersonating

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>