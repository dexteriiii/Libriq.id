<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class LoansTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('loans')->delete();
        
        \DB::table('loans')->insert(array (
            0 => 
            array (
                'id' => 1,
                'borrower_id' => 2,
                'book_id' => 1,
                'borrow_date' => '2026-09-10 00:00:00',
                'due_date' => '2026-09-17 00:00:00',
                'return_date' => '2026-09-10 00:00:00',
                'status' => 'returned',
                'fine_amount' => 0,
                'fine_paid_at' => NULL,
                'created_at' => '2026-09-10 01:32:57',
                'updated_at' => '2026-09-10 01:37:32',
                'renewal_status' => 'none',
                'renewal_count' => 0,
                'renewal_requested_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'borrower_id' => 2,
                'book_id' => 2,
                'borrow_date' => '2026-09-10 00:00:00',
                'due_date' => '2026-09-17 00:00:00',
                'return_date' => '2026-09-10 00:00:00',
                'status' => 'returned',
                'fine_amount' => 0,
                'fine_paid_at' => NULL,
                'created_at' => '2026-09-10 01:51:19',
                'updated_at' => '2026-09-10 02:00:06',
                'renewal_status' => 'none',
                'renewal_count' => 0,
                'renewal_requested_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'borrower_id' => 2,
                'book_id' => 1,
                'borrow_date' => '2026-09-10 00:00:00',
                'due_date' => '2026-09-17 00:00:00',
                'return_date' => '2026-09-10 00:00:00',
                'status' => 'returned',
                'fine_amount' => 0,
                'fine_paid_at' => NULL,
                'created_at' => '2026-09-10 01:53:40',
                'updated_at' => '2026-09-10 02:00:09',
                'renewal_status' => 'none',
                'renewal_count' => 0,
                'renewal_requested_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'borrower_id' => 5,
                'book_id' => 2,
                'borrow_date' => '2026-09-25 00:00:00',
                'due_date' => '2026-10-02 00:00:00',
                'return_date' => NULL,
                'status' => 'borrowed',
                'fine_amount' => 0,
                'fine_paid_at' => NULL,
                'created_at' => '2026-09-17 02:34:41',
                'updated_at' => '2026-09-25 04:15:51',
                'renewal_status' => 'none',
                'renewal_count' => 0,
                'renewal_requested_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'borrower_id' => 2,
                'book_id' => 9,
                'borrow_date' => '2026-09-12 00:00:00',
                'due_date' => '2026-09-19 00:00:00',
                'return_date' => NULL,
                'status' => 'borrowed',
                'fine_amount' => 0,
                'fine_paid_at' => NULL,
                'created_at' => '2026-09-25 02:27:33',
                'updated_at' => '2026-09-25 02:27:33',
                'renewal_status' => 'none',
                'renewal_count' => 0,
                'renewal_requested_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'borrower_id' => 2,
                'book_id' => 8,
                'borrow_date' => '2026-09-12 00:00:00',
                'due_date' => '2026-09-19 00:00:00',
                'return_date' => NULL,
                'status' => 'borrowed',
                'fine_amount' => 0,
                'fine_paid_at' => NULL,
                'created_at' => '2026-09-25 02:27:33',
                'updated_at' => '2026-09-25 02:27:33',
                'renewal_status' => 'none',
                'renewal_count' => 0,
                'renewal_requested_at' => NULL,
            ),
        ));
        
        
    }
}