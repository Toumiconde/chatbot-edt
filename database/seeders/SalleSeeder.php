<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SalleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('salles')->insert([
            ['nom' => 'Salle A', 'capacite' => 50, 'batiment' => 'Bloc A', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Salle B', 'capacite' => 40, 'batiment' => 'Bloc A', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Salle C', 'capacite' => 35, 'batiment' => 'Bloc B', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Labo 1',  'capacite' => 25, 'batiment' => 'Bloc B', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Labo 2',  'capacite' => 20, 'batiment' => 'Bloc C', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
