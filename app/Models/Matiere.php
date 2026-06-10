<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Matiere extends Model
{
    protected $fillable = ['nom', 'code', 'credits'];

    public function emploisDuTemps()
    {
        return $this->hasMany(EmploiDuTemps::class);
    }
}
