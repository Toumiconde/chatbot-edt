<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FiliereSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('filieres')->insert([
            ['nom' => 'Licence Informatique', 'code' => 'L-INFO', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Licence Mathematiques', 'code' => 'L-MATH', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
