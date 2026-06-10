<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            FiliereSeeder::class,
            NiveauSeeder::class,
            EnseignantSeeder::class,
            MatiereSeeder::class,
            SalleSeeder::class,
            EmploiDuTempsSeeder::class,
            ModificationSeeder::class,
        ]);
    }
}
