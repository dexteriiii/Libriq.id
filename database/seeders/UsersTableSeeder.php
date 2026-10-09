<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('users')->delete();
        
        \DB::table('users')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'email_verified_at' => NULL,
                'password' => '$2y$12$t98O4TI178XcS3JLk6Pke.K7Ewv04.TEW3IDNJkcSV0iH0BP0.oMC',
                'remember_token' => NULL,
                'created_at' => '2026-09-10 00:28:40',
                'updated_at' => '2026-09-25 02:27:36',
                'role' => 'admin',
                'member_id' => NULL,
                'avatar_path' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'almay',
                'email' => 'member@gmail.com',
                'email_verified_at' => NULL,
                'password' => '$2y$12$W.3HCkKnM09VeQWTb5GtUOg1QiqHi4vt65RA3aeHTN9SNw78Led6G',
                'remember_token' => 'yZjxfRMequFUohYtSCdcSvi9yL5mTlD1obVM12iXRPdQiopf70CXUSPhjnqQ',
                'created_at' => '2026-09-10 00:29:30',
                'updated_at' => '2026-09-25 02:27:36',
                'role' => 'member',
                'member_id' => NULL,
                'avatar_path' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Administrator',
                'email' => 'admin@libriq.id',
                'email_verified_at' => NULL,
                'password' => '$2y$12$.fjOIrfH3vM2gk716AfYBOCXR8N1/0FMBhyH5D/Uv14lBT/nMCC1e',
                'remember_token' => NULL,
                'created_at' => '2026-09-17 02:16:44',
                'updated_at' => '2026-09-17 02:16:44',
                'role' => 'admin',
                'member_id' => NULL,
                'avatar_path' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Anggota Demo',
                'email' => 'member@libriq.id',
                'email_verified_at' => NULL,
                'password' => '$2y$12$SMpeZOST1DbPaFu/n567UevZNc9w35izUlOafzP8/Xf5vrWBhfCTe',
                'remember_token' => NULL,
                'created_at' => '2026-09-17 02:16:44',
                'updated_at' => '2026-09-17 02:16:44',
                'role' => 'member',
                'member_id' => NULL,
                'avatar_path' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Anggota Demo',
                'email' => 'a@gmail.com',
                'email_verified_at' => NULL,
                'password' => '$2y$12$qrS2b0mFCcV7p989sQ8bBeB90Epvz8ot/sCVUCrrEFx/UeJfmRCpu',
                'remember_token' => NULL,
                'created_at' => '2026-09-17 02:34:28',
                'updated_at' => '2026-09-25 02:27:36',
                'role' => 'member',
                'member_id' => NULL,
                'avatar_path' => NULL,
            ),
        ));
        
        
    }
}