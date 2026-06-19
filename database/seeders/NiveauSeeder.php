<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NiveauSeeder extends Seeder
{
    public function run(): void
    {
        // Supprimer les données existantes
        DB::table('niveaux')->truncate();

        // NTIC (filiere_id=1) : L1, L2, L3
        DB::table('niveaux')->insert([
            ['filiere_id' => 1, 'libelle' => 'L1 — Semestre 1 & 2', 'created_at' => now(), 'updated_at' => now()],
            ['filiere_id' => 1, 'libelle' => 'L2 — Semestre 3 & 4', 'created_at' => now(), 'updated_at' => now()],
            ['filiere_id' => 1, 'libelle' => 'L3 — Semestre 5 & 6', 'created_at' => now(), 'updated_at' => now()],
            // DL (filiere_id=2) : L1, L2, L3
            ['filiere_id' => 2, 'libelle' => 'L1 — Semestre 1 & 2', 'created_at' => now(), 'updated_at' => now()],
            ['filiere_id' => 2, 'libelle' => 'L2 — Semestre 3 & 4', 'created_at' => now(), 'updated_at' => now()],
            ['filiere_id' => 2, 'libelle' => 'L3 — Semestre 5 & 6', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
