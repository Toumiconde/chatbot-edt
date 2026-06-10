<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModificationSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('modifications')->insert([
            [
                'emploi_id'      => 1,
                'ancien_jour'    => 'Lundi',
                'ancienne_heure' => '08:00:00',
                'nouveau_jour'   => 'Mardi',
                'nouvelle_heure' => '10:00:00',
                'motif'          => 'Salle A indisponible',
                'date_modif'     => now()->subHours(5),
                'created_at'     => now()->subHours(5),
                'updated_at'     => now()->subHours(5),
            ],
            [
                'emploi_id'      => 3,
                'ancien_jour'    => 'Mardi',
                'ancienne_heure' => '08:00:00',
                'nouveau_jour'   => 'Mercredi',
                'nouvelle_heure' => '08:00:00',
                'motif'          => 'Enseignant en deplacement',
                'date_modif'     => now()->subHours(10),
                'created_at'     => now()->subHours(10),
                'updated_at'     => now()->subHours(10),
            ],
            [
                'emploi_id'      => 7,
                'ancien_jour'    => 'Jeudi',
                'ancienne_heure' => '14:00:00',
                'nouveau_jour'   => 'Vendredi',
                'nouvelle_heure' => '14:00:00',
                'motif'          => 'Reunion pedagogique',
                'date_modif'     => now()->subHours(20),
                'created_at'     => now()->subHours(20),
                'updated_at'     => now()->subHours(20),
            ],
            [
                'emploi_id'      => 8,
                'ancien_jour'    => 'Vendredi',
                'ancienne_heure' => '08:00:00',
                'nouveau_jour'   => 'Jeudi',
                'nouvelle_heure' => '08:00:00',
                'motif'          => 'Disponibilite salle',
                'date_modif'     => now()->subHours(30),
                'created_at'     => now()->subHours(30),
                'updated_at'     => now()->subHours(30),
            ],
            [
                'emploi_id'      => 2,
                'ancien_jour'    => 'Lundi',
                'ancienne_heure' => '10:00:00',
                'nouveau_jour'   => 'Lundi',
                'nouvelle_heure' => '14:00:00',
                'motif'          => 'Chevauchement horaire',
                'date_modif'     => now()->subHours(40),
                'created_at'     => now()->subHours(40),
                'updated_at'     => now()->subHours(40),
            ],
        ]);
    }
}
