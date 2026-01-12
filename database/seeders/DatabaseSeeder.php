<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
// Importe o seu UserSeeder se necessário, mas geralmente não precisa se estiver no mesmo namespace

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
        ]);
    }
}