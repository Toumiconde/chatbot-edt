<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Filiere extends Model
{
    protected $fillable = ['nom', 'code'];

    public function niveaux()
    {
        return $this->hasMany(Niveau::class);
    }

    public function emploisDuTemps()
    {
        return $this->hasMany(EmploiDuTemps::class);
    }
}
