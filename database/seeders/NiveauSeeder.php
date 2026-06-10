<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NiveauSeeder extends Seeder
{
    public function run(): void
    {
        // L-INFO : L1, L2, L3
        DB::table('niveaux')->insert([
            ['filiere_id' => 1, 'libelle' => 'L1', 'created_at' => now(), 'updated_at' => now()],
            ['filiere_id' => 1, 'libelle' => 'L2', 'created_at' => now(), 'updated_at' => now()],
            ['filiere_id' => 1, 'libelle' => 'L3', 'created_at' => now(), 'updated_at' => now()],
            // L-MATH : L1, L2
            ['filiere_id' => 2, 'libelle' => 'L1', 'created_at' => now(), 'updated_at' => now()],
            ['filiere_id' => 2, 'libelle' => 'L2', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
