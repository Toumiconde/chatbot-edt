<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Annonce extends Model
{
    protected $fillable = [
        'titre', 'message', 'type', 'auteur',
        'filiere_id', 'niveau_id', 'urgent', 'expires_at',
    ];

    protected $casts = [
        'urgent'     => 'boolean',
        'expires_at' => 'datetime',
    ];

    public function filiere()
    {
        return $this->belongsTo(Filiere::class);
    }

    public function niveau()
    {
        return $this->belongsTo(Niveau::class);
    }

    // Scope : exclut les annonces expirées
    public function scopeActives($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }
}
