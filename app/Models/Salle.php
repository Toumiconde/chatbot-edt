<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Salle extends Model
{
    protected $fillable = ['nom', 'capacite', 'batiment'];

    public function emploisDuTemps()
    {
        return $this->hasMany(EmploiDuTemps::class);
    }
}
