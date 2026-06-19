<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enseignant extends Model
{
    protected $fillable = ['nom', 'prenom', 'email'];

    public function emploisDuTemps()
    {
        return $this->hasMany(EmploiDuTemps::class);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }
}
