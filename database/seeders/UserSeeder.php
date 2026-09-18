<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin utama
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name'     => 'Admin',
                'password' => Hash::make('password'),
                'role'     => 'admin',
            ]
        );

        // Member demo 1 (almay — memiliki riwayat pinjam)
        User::updateOrCreate(
            ['email' => 'member@gmail.com'],
            [
                'name'     => 'almay',
                'password' => Hash::make('password'),
                'role'     => 'member',
            ]
        );

        // Member demo 2
        User::updateOrCreate(
            ['email' => 'a@gmail.com'],
            [
                'name'     => 'Anggota Demo',
                'password' => Hash::make('password'),
                'role'     => 'member',
            ]
        );
    }
}
