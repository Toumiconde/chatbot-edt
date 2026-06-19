<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Matiere extends Model
{
    protected $fillable = ['nom', 'code', 'credits', 'filiere_id', 'niveau_id', 'semestre'];

    public function emploisDuTemps()
    {
        return $this->hasMany(EmploiDuTemps::class);
    }

    public function filiere()
    {
        return $this->belongsTo(Filiere::class);
    }

    public function niveau()
    {
        return $this->belongsTo(Niveau::class);
    }
}
