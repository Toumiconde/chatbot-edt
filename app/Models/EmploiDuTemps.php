<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmploiDuTemps extends Model
{
    protected $table = 'emplois_du_temps';

    protected $fillable = [
        'filiere_id', 'niveau_id', 'semestre', 'matiere_id',
        'enseignant_id', 'salle_id', 'jour',
        'heure_debut', 'heure_fin',
    ];

    public function filiere()
    {
        return $this->belongsTo(Filiere::class);
    }

    public function niveau()
    {
        return $this->belongsTo(Niveau::class);
    }

    public function matiere()
    {
        return $this->belongsTo(Matiere::class);
    }

    public function enseignant()
    {
        return $this->belongsTo(Enseignant::class);
    }

    public function salle()
    {
        return $this->belongsTo(Salle::class);
    }

    public function modifications()
    {
        return $this->hasMany(Modification::class, 'emploi_id');
    }
}
