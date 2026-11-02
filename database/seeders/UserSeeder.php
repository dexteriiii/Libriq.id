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
                'name'      => 'almay',
                'nis'       => '10001',
                'kelas'     => 'XII RPL 1',
                'member_id' => '10001',
                'password'  => Hash::make('password'),
                'role'      => 'member',
            ]
        );

        // Member demo 2
        User::updateOrCreate(
            ['email' => 'a@gmail.com'],
            [
                'name'      => 'Anggota Demo',
                'nis'       => '10002',
                'kelas'     => 'XI TKJ 2',
                'member_id' => '10002',
                'password'  => Hash::make('password'),
                'role'      => 'member',
            ]
        );
    }
}
