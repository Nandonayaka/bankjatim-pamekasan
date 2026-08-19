<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus semua user lama terlebih dahulu
        User::truncate();

        User::create([
            'name'     => 'Jaka Kusuma A',
            'username' => 'jaka',
            'email'    => 'jaka@gmail.com',
            'password' => Hash::make('password'),
        ]);
    }
}
