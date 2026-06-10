<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmploiDuTempsSeeder extends Seeder
{
    public function run(): void
    {
        // filiere_id=1 (L-INFO), niveau_id=1 (L1)
        $l1 = [
            ['filiere_id' => 1, 'niveau_id' => 1, 'matiere_id' => 1, 'enseignant_id' => 1, 'salle_id' => 1, 'jour' => 'Lundi',    'heure_debut' => '08:00:00', 'heure_fin' => '10:00:00'],
            ['filiere_id' => 1, 'niveau_id' => 1, 'matiere_id' => 7, 'enseignant_id' => 5, 'salle_id' => 2, 'jour' => 'Lundi',    'heure_debut' => '10:00:00', 'heure_fin' => '12:00:00'],
            ['filiere_id' => 1, 'niveau_id' => 1, 'matiere_id' => 1, 'enseignant_id' => 1, 'salle_id' => 4, 'jour' => 'Mardi',    'heure_debut' => '08:00:00', 'heure_fin' => '10:00:00'],
            ['filiere_id' => 1, 'niveau_id' => 1, 'matiere_id' => 7, 'enseignant_id' => 5, 'salle_id' => 2, 'jour' => 'Mercredi', 'heure_debut' => '10:00:00', 'heure_fin' => '12:00:00'],
            ['filiere_id' => 1, 'niveau_id' => 1, 'matiere_id' => 1, 'enseignant_id' => 1, 'salle_id' => 1, 'jour' => 'Jeudi',    'heure_debut' => '14:00:00', 'heure_fin' => '16:00:00'],
            ['filiere_id' => 1, 'niveau_id' => 1, 'matiere_id' => 7, 'enseignant_id' => 5, 'salle_id' => 3, 'jour' => 'Vendredi', 'heure_debut' => '08:00:00', 'heure_fin' => '10:00:00'],
        ];

        // filiere_id=1 (L-INFO), niveau_id=2 (L2)
        $l2 = [
            ['filiere_id' => 1, 'niveau_id' => 2, 'matiere_id' => 2, 'enseignant_id' => 2, 'salle_id' => 4, 'jour' => 'Lundi',    'heure_debut' => '08:00:00', 'heure_fin' => '10:00:00'],
            ['filiere_id' => 1, 'niveau_id' => 2, 'matiere_id' => 4, 'enseignant_id' => 3, 'salle_id' => 5, 'jour' => 'Lundi',    'heure_debut' => '10:00:00', 'heure_fin' => '12:00:00'],
            ['filiere_id' => 1, 'niveau_id' => 2, 'matiere_id' => 3, 'enseignant_id' => 4, 'salle_id' => 1, 'jour' => 'Mardi',    'heure_debut' => '08:00:00', 'heure_fin' => '10:00:00'],
            ['filiere_id' => 1, 'niveau_id' => 2, 'matiere_id' => 6, 'enseignant_id' => 1, 'salle_id' => 4, 'jour' => 'Mardi',    'heure_debut' => '14:00:00', 'heure_fin' => '16:00:00'],
            ['filiere_id' => 1, 'niveau_id' => 2, 'matiere_id' => 2, 'enseignant_id' => 2, 'salle_id' => 5, 'jour' => 'Mercredi', 'heure_debut' => '08:00:00', 'heure_fin' => '10:00:00'],
            ['filiere_id' => 1, 'niveau_id' => 2, 'matiere_id' => 4, 'enseignant_id' => 3, 'salle_id' => 2, 'jour' => 'Jeudi',    'heure_debut' => '10:00:00', 'heure_fin' => '12:00:00'],
            ['filiere_id' => 1, 'niveau_id' => 2, 'matiere_id' => 8, 'enseignant_id' => 5, 'salle_id' => 3, 'jour' => 'Jeudi',    'heure_debut' => '14:00:00', 'heure_fin' => '16:00:00'],
            ['filiere_id' => 1, 'niveau_id' => 2, 'matiere_id' => 3, 'enseignant_id' => 4, 'salle_id' => 1, 'jour' => 'Vendredi', 'heure_debut' => '08:00:00', 'heure_fin' => '10:00:00'],
            ['filiere_id' => 1, 'niveau_id' => 2, 'matiere_id' => 6, 'enseignant_id' => 1, 'salle_id' => 2, 'jour' => 'Vendredi', 'heure_debut' => '10:00:00', 'heure_fin' => '12:00:00'],
        ];

        $now = now();
        $rows = array_merge($l1, $l2);
        foreach ($rows as &$row) {
            $row['created_at'] = $now;
            $row['updated_at'] = $now;
        }

        DB::table('emplois_du_temps')->insert($rows);
    }
}
