<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EnseignantSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('enseignants')->insert([
            ['nom' => 'Camara',  'prenom' => 'Ibrahima',  'email' => 'i.camara@uganc.edu.gn',  'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Diallo',  'prenom' => 'Fatoumata', 'email' => 'f.diallo@uganc.edu.gn',  'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Bah',     'prenom' => 'Mamadou',   'email' => 'm.bah@uganc.edu.gn',      'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Kouyate', 'prenom' => 'Sekou',     'email' => 's.kouyate@uganc.edu.gn',  'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Kaba',    'prenom' => 'Makera',    'email' => 'm.kaba@uganc.edu.gn',     'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
