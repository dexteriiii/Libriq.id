<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::updateOrCreate(
            ['email' => 'admin@libriq.id'],
            [
                'name'     => 'Administrator',
                'password' => Hash::make('password'),
                'role'     => 'admin',
            ]
        );

        // Create test member
        User::updateOrCreate(
            ['email' => 'member@libriq.id'],
            [
                'name'     => 'Anggota Demo',
                'password' => Hash::make('password'),
                'role'     => 'member',
            ]
        );
    }
}
