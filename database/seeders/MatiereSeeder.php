<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MatiereSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('matieres')->insert([
            ['nom' => 'Algorithmique',         'code' => 'ALGO-L1', 'credits' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Base de Donnees',        'code' => 'BDD-L2',  'credits' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Reseaux Informatiques',  'code' => 'RES-L2',  'credits' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Programmation Orientee Objet', 'code' => 'POO-L2', 'credits' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Developpement Web',      'code' => 'WEB-L3',  'credits' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['nom' => "Systemes d'Exploitation", 'code' => 'SYS-L2', 'credits' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Mathematiques Discretes','code' => 'MATH-L1', 'credits' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Statistiques',           'code' => 'STAT-L2', 'credits' => 3, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
