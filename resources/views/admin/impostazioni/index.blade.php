<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Impostazioni') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <div class="mb-6">
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Configurazione Sistema</h3>
                        <p class="text-gray-600 dark:text-gray-400 mt-2">Gestisci le impostazioni generali dell'applicazione</p>
                    </div>

                    <form method="POST" action="{{ route('admin.impostazioni.store') }}" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Sezione Applicazione -->
                        <div class="bg-blue-50 dark:bg-blue-900/20 p-6 rounded-lg mb-6">
                            <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Impostazioni Applicazione</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Nome Applicazione -->
                                <div>
                                    <x-input-label for="app_name" :value="__('Nome Applicazione')" />
                                    <x-text-input id="app_name" name="app_name" type="text" class="mt-1 block w-full" 
                                                  :value="old('app_name', config('app.name'))" />
                                    <x-input-error class="mt-2" :messages="$errors->get('app_name')" />
                                </div>

                                <!-- URL Applicazione -->
                                <div>
                                    <x-input-label for="app_url" :value="__('URL Applicazione')" />
                                    <x-text-input id="app_url" name="app_url" type="url" class="mt-1 block w-full" 
                                                  :value="old('app_url', config('app.url'))" />
                                    <x-input-error class="mt-2" :messages="$errors->get('app_url')" />
                                </div>
                            </div>
                        </div>

                        <!-- Sezione Branding -->
                        <div class="bg-green-50 dark:bg-green-900/20 p-6 rounded-lg mb-6">
                            <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Branding</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Logo Applicazione -->
                                <div>
                                    <x-input-label for="app_logo" :value="__('Logo Applicazione')" />
                                    <input type="file" id="app_logo" name="app_logo" accept="image/*" 
                                           class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" />
                                    <x-input-error class="mt-2" :messages="$errors->get('app_logo')" />
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Formati supportati: JPG, PNG, SVG (max 2MB)</p>
                                </div>

                                <!-- Logo Dashboard -->
                                <div>
                                    <x-input-label for="dashboard_logo" :value="__('Logo Dashboard')" />
                                    <input type="file" id="dashboard_logo" name="dashboard_logo" accept="image/*" 
                                           class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" />
                                    <x-input-error class="mt-2" :messages="$errors->get('dashboard_logo')" />
                                </div>

                                <!-- Favicon -->
                                <div>
                                    <x-input-label for="favicon" :value="__('Favicon')" />
                                    <input type="file" id="favicon" name="favicon" accept="image/*" 
                                           class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" />
                                    <x-input-error class="mt-2" :messages="$errors->get('favicon')" />
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Formato ICO o PNG 32x32px</p>
                                </div>
                            </div>
                        </div>

                        <!-- Sezione Email -->
                        <div class="bg-yellow-50 dark:bg-yellow-900/20 p-6 rounded-lg mb-6">
                            <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Configurazione Email</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Email Mittente -->
                                <div>
                                    <x-input-label for="mail_from_address" :value="__('Email Mittente')" />
                                    <x-text-input id="mail_from_address" name="mail_from_address" type="email" class="mt-1 block w-full" 
                                                  :value="old('mail_from_address', config('mail.from.address'))" />
                                    <x-input-error class="mt-2" :messages="$errors->get('mail_from_address')" />
                                </div>

                                <!-- Nome Mittente -->
                                <div>
                                    <x-input-label for="mail_from_name" :value="__('Nome Mittente')" />
                                    <x-text-input id="mail_from_name" name="mail_from_name" type="text" class="mt-1 block w-full" 
                                                  :value="old('mail_from_name', config('mail.from.name'))" />
                                    <x-input-error class="mt-2" :messages="$errors->get('mail_from_name')" />
                                </div>
                            </div>
                        </div>

                        <!-- Sezione Pagamenti -->
                        <div class="bg-purple-50 dark:bg-purple-900/20 p-6 rounded-lg mb-6">
                            <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Configurazione Pagamenti</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Stripe Key -->
                                <div>
                                    <x-input-label for="stripe_key" :value="__('Stripe Publishable Key')" />
                                    <x-text-input id="stripe_key" name="stripe_key" type="text" class="mt-1 block w-full" 
                                                  :value="old('stripe_key', config('services.stripe.key'))" />
                                    <x-input-error class="mt-2" :messages="$errors->get('stripe_key')" />
                                </div>

                                <!-- PayPal Client ID -->
                                <div>
                                    <x-input-label for="paypal_client_id" :value="__('PayPal Client ID')" />
                                    <x-text-input id="paypal_client_id" name="paypal_client_id" type="text" class="mt-1 block w-full" 
                                                  :value="old('paypal_client_id', config('services.paypal.client_id'))" />
                                    <x-input-error class="mt-2" :messages="$errors->get('paypal_client_id')" />
                                </div>
                            </div>
                        </div>

                        <!-- Pulsanti -->
                        <div class="flex items-center justify-end space-x-4">
                            <x-secondary-button type="button" onclick="window.location.reload()">
                                {{ __('Ripristina') }}
                            </x-secondary-button>
                            <x-primary-button>
                                {{ __('Salva Impostazioni') }}
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