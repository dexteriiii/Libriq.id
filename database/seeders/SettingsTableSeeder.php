<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('settings')->delete();
        
        \DB::table('settings')->insert(array (
            0 => 
            array (
                'key' => 'loan_duration_days',
                'value' => '7',
                'created_at' => '2026-09-10 00:25:31',
                'updated_at' => '2026-09-10 00:25:31',
            ),
            1 => 
            array (
                'key' => 'max_active_loans',
                'value' => '3',
                'created_at' => '2026-09-10 00:25:31',
                'updated_at' => '2026-09-10 00:25:31',
            ),
            2 => 
            array (
                'key' => 'fine_per_day',
                'value' => '2000',
                'created_at' => '2026-09-10 00:25:31',
                'updated_at' => '2026-09-10 00:25:31',
            ),
            3 => 
            array (
                'key' => 'max_unpaid_fine',
                'value' => '20000',
                'created_at' => '2026-09-10 00:25:31',
                'updated_at' => '2026-09-10 00:25:31',
            ),
        ));
        
        
    }
}