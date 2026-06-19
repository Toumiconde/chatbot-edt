<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FiliereSeeder extends Seeder
{
    public function run(): void
    {
        // Supprimer les anciennes données pour éviter les doublons
        DB::table('filieres')->truncate();

        DB::table('filieres')->insert([
            [
                'nom'        => 'Nouvelles Technologies de l\'Information et de la Communication',
                'code'       => 'NTIC',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom'        => 'Développement Logiciel',
                'code'       => 'DL',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
