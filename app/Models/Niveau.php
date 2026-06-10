<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Niveau extends Model
{
    protected $fillable = ['filiere_id', 'libelle'];

    public function filiere()
    {
        return $this->belongsTo(Filiere::class);
    }

    public function emploisDuTemps()
    {
        return $this->hasMany(EmploiDuTemps::class);
    }
}
