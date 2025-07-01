<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Stabile;
use App\Policies\StabilePolicy;


class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
        Stabile::class => StabilePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Gate per accesso pannello super-admin
        Gate::define('access-super-admin-panel', function (User $user) {
            return $user->hasRole('super-admin');
        });

        // Gate per accesso pannello admin (Amministratore di Condominio)
        Gate::define('access-admin-panel', function (User $user) {
            // L'utente deve avere il ruolo 'amministratore' ED essere collegato a un record 'amministratori'
            return $user->hasRole('amministratore') && $user->amministratore()->exists();
        });

        // Gate per accesso pannello condomino
        Gate::define('access-condomino-panel', function (User $user) {
            return $user->hasRole('condomino');
        });

        // Gate per l'impersonificazione
        Gate::define('impersonate', function (User $user) {
            // Solo i super-admin possono impersonare
            return $user->hasRole('super-admin');
        });

        // Gate per verificare se un utente può essere impersonato
        Gate::define('canBeImpersonated', function (User $user, User $targetUser) {
            // Un super-admin non può essere impersonato da nessuno
            return !$targetUser->hasRole('super-admin');
        });
    }
}
