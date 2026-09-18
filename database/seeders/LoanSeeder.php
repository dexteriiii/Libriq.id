<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Database\Seeder;

class LoanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Data pinjaman anggota yang sudah ada di sistem.
     * Menggunakan ISBN dan email agar tidak bergantung pada ID auto-increment.
     */
    public function run(): void
    {
        $loans = [
            // ── almay: Hutan Hujan (sudah dikembalikan) ─────────────────────
            [
                'borrower_email' => 'member@gmail.com',
                'book_isbn'      => '9789790152786',
                'borrow_date'    => '2026-09-10 00:00:00',
                'due_date'       => '2026-09-17 00:00:00',
                'return_date'    => '2026-09-10 00:00:00',
                'status'         => 'returned',
                'fine_amount'    => 0,
                'fine_paid_at'   => null,
            ],
            // ── almay: Laravel & MariaDB (sedang dipinjam) ──────────────────
            [
                'borrower_email' => 'member@gmail.com',
                'book_isbn'      => '9786230107245',
                'borrow_date'    => '2026-09-12 00:00:00',
                'due_date'       => '2026-09-19 00:00:00',
                'return_date'    => null,
                'status'         => 'borrowed',
                'fine_amount'    => 0,
                'fine_paid_at'   => null,
            ],
            // ── almay: Epidemiologi Kesehatan Lingkungan (sedang dipinjam) ──
            [
                'borrower_email' => 'member@gmail.com',
                'book_isbn'      => '9786235081656',
                'borrow_date'    => '2026-09-12 00:00:00',
                'due_date'       => '2026-09-19 00:00:00',
                'return_date'    => null,
                'status'         => 'borrowed',
                'fine_amount'    => 0,
                'fine_paid_at'   => null,
            ],
        ];

        foreach ($loans as $loanData) {
            $borrower = User::where('email', $loanData['borrower_email'])->first();
            $book     = Book::where('isbn', $loanData['book_isbn'])->first();

            if (!$borrower || !$book) {
                continue;
            }

            Loan::updateOrCreate(
                [
                    'borrower_id' => $borrower->id,
                    'book_id'     => $book->id,
                    'status'      => $loanData['status'],
                ],
                [
                    'borrow_date'  => $loanData['borrow_date'],
                    'due_date'     => $loanData['due_date'],
                    'return_date'  => $loanData['return_date'],
                    'fine_amount'  => $loanData['fine_amount'],
                    'fine_paid_at' => $loanData['fine_paid_at'],
                ]
            );
        }
    }
}
