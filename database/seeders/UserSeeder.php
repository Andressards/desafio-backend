<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar um usuário comum com saldo
        $common = \App\Models\User::create([
            'name' => 'Usuário Comum',
            'email' => 'comum@teste.com',
            'document' => '12345678901',
            'type' => 'common',
            'password' => bcrypt('password'),
        ]);
        $common->wallet()->create(['balance' => 1000.00]);

        // Criar um lojista
        $merchant = \App\Models\User::create([
            'name' => 'Lojista Teste',
            'email' => 'lojista@teste.com',
            'document' => '98765432100',
            'type' => 'merchant',
            'password' => bcrypt('password'),
        ]);
        $merchant->wallet()->create(['balance' => 0.00]);
    }
}
