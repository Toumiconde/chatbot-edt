<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'enseignant_id',
        'filiere_id',
        'filiere_ids',
        'niveau_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'filiere_ids' => 'array',
        ];
    }

    // ── Helpers de rôle ──
    public function isChef(): bool    { return $this->role === 'chef'; }
    public function isProf(): bool    { return $this->role === 'prof'; }
    public function isEtudiant(): bool { return $this->role === 'etudiant'; }

    // ── Relation avec Enseignant (pour les profs) ──
    public function enseignant()
    {
        return $this->belongsTo(Enseignant::class);
    }

    // ── Relation avec Filiere (département principal) ──
    public function filiere()
    {
        return $this->belongsTo(Filiere::class);
    }

    // ── Relation avec Niveau (Licence pour les étudiants) ──
    public function niveau()
    {
        return $this->belongsTo(Niveau::class);
    }
}
