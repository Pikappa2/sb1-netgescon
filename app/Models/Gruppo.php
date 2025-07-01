<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Gruppo extends Model
{
    use HasFactory;
    protected $table = 'gruppi';
    protected $fillable = ['nome_gruppo', 'manager_id'];
    public function manager() { return $this->belongsTo(User::class, 'manager_id'); }
    public function amministratori() { return $this->hasMany(Amministratore::class); }
}
