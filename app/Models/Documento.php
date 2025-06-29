<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Documento extends Model
{
    use HasFactory;

    protected $table = 'documenti';

    protected $fillable = [
        'documentable_id',
        'documentable_type',
        'nome_file',
        'path_file',
        'tipo_documento',
        'dimensione_file',
        'mime_type',
        'descrizione',
        'xml_data',
        'hash_file',
    ];

    protected $casts = [
        'xml_data' => 'array',
        'dimensione_file' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relazione polimorfica
     */
    public function documentable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope per tipo documento
     */
    public function scopeTipo($query, $tipo)
    {
        return $query->where('tipo_documento', $tipo);
    }

    /**
     * Accessor per URL download
     */
    public function getUrlDownloadAttribute()
    {
        return route('admin.documenti.download', $this->id);
    }

    /**
     * Accessor per dimensione leggibile
     */
    public function getDimensioneLeggibileAttribute()
    {
        $bytes = $this->dimensione_file;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }
}