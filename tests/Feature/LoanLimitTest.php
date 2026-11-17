<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Loan;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoanLimitTest extends TestCase
{
    use RefreshDatabase;

    public function test_member_receives_alert_when_loan_limit_is_reached(): void
    {
        // Set max_active_loans = 3
        Setting::set('max_active_loans', 3);

        $member = User::factory()->create([
            'role' => 'member',
            'email' => 'siswa@example.com',
        ]);

        $books = [];
        for ($i = 1; $i <= 4; $i++) {
            $books[] = Book::create([
                'title'           => "Buku Test {$i}",
                'author'          => "Penulis {$i}",
                'isbn'            => "978123456789{$i}",
                'total_stock'     => 5,
                'available_stock' => 5,
            ]);
        }

        // Buat 3 peminjaman aktif untuk member
        for ($i = 0; $i < 3; $i++) {
            Loan::create([
                'borrower_id'       => $member->id,
                'book_id'           => $books[$i]->id,
                'status'            => 'borrowed',
                'borrow_date'       => now(),
                'due_date'          => now()->addDays(7),
                'verification_code' => '12345',
            ]);
        }

        // Coba pinjam buku ke-4
        $targetBook = $books[3];

        $response = $this->actingAs($member)
            ->from(route('member.catalog.show', $targetBook))
            ->post(route('member.loans.store', $targetBook));

        $response->assertRedirect(route('member.catalog.show', $targetBook));
        $response->assertSessionHas('error');

        // Buka halaman detail buku ke-4 dan periksa apakah ada info banner batas pinjaman habis
        $showResponse = $this->actingAs($member)->get(route('member.catalog.show', $targetBook));
        $showResponse->assertStatus(200);
        $showResponse->assertSee('Batas Pinjam Buku Sudah Habis');
        $showResponse->assertSee('3 buku');
    }
}
