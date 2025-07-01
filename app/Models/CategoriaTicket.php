<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class CategoriaTicket extends Model
{
    
    use HasFactory;

    protected $table = 'categorie_ticket';
    protected $fillable = ['nome', 'descrizione'];

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'categoria_ticket_id', 'id');
    }
}
