<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Urutan: Users → Books → Loans → Settings
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        $this->call([
            UsersTableSeeder::class,
            BooksTableSeeder::class,
            LoansTableSeeder::class,
            SettingsTableSeeder::class,
        ]);

        Schema::enableForeignKeyConstraints();
    }
}
