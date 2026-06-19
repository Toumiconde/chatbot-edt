<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Chef de Programme NTIC
        User::firstOrCreate(
            ['email' => 'chef.ntic@uganc.edu.gn'],
            [
                'name'       => 'Chef de Programme NTIC',
                'password'   => Hash::make('secret123'),
                'role'       => 'chef',
                'filiere_id' => 1,
                'filiere_ids'=> [1],
            ]
        );

        // Chef de Programme DL
        User::firstOrCreate(
            ['email' => 'chef.dl@uganc.edu.gn'],
            [
                'name'       => 'Chef de Programme DL',
                'password'   => Hash::make('secret123'),
                'role'       => 'chef',
                'filiere_id' => 2,
                'filiere_ids'=> [2],
            ]
        );
    }
}
