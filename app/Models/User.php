<?php

namespace App\Models;

// Import necessari
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Lab404\Impersonate\Models\Impersonate; // Per la funzionalità di impersonificazione
use Spatie\Permission\Traits\HasRoles; // Per la gestione dei ruoli e permessi
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    // Aggiungi il trait Impersonate
    use HasApiTokens, HasFactory, Notifiable, Impersonate, HasRoles; // Aggiungi HasRoles qui


    protected $fillable = ['name', 'email', 'password', 'email_verified_at']; // Aggiunto email_verified_at
    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return ['email_verified_at' => 'datetime', 'password' => 'hashed'];
    }

    /**
     * Get the amministratore record associated with the user.
     */
    public function amministratore(): HasOne
    {
        return $this->hasOne(Amministratore::class, 'user_id');
    }

    public function tickets(): HasMany { return $this->hasMany(Ticket::class, 'aperto_da_user_id'); }

    /**
     * Regola per il pacchetto: definisce se l'utente ATTUALE
     * ha il permesso di impersonare altri.
     */
    public function canImpersonate(): bool
    
    {   
        // Solo il Super-Admin può farlo.
        return $this->hasRole('super-admin');
    }

    /**
     * Regola per il pacchetto: definisce se questo specifico
     * utente PUÒ ESSERE impersonato.
     */
    public function canBeImpersonated(): bool
    {
        // Solo gli utenti con ruolo 'admin' possono essere impersonati.
        return $this->hasRole('admin');
    }
}