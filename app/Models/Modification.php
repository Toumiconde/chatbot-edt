<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modification extends Model
{
    protected $fillable = [
        'emploi_id', 'ancien_jour', 'ancienne_heure',
        'nouveau_jour', 'nouvelle_heure', 'motif', 'date_modif',
    ];

    protected $casts = [
        'date_modif' => 'datetime',
    ];

    public function emploi()
    {
        return $this->belongsTo(EmploiDuTemps::class, 'emploi_id');
    }
}
