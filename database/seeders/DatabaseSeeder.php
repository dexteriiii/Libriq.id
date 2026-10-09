<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Urutan: Users → Books → Loans → Settings
     */
    public function run(): void
    {
        $this->call([
            UsersTableSeeder::class,
            BooksTableSeeder::class,
            LoansTableSeeder::class,
            SettingsTableSeeder::class,
        ]);
    }
}