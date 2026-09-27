<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'borrower_id', 'book_id', 'borrow_date', 'due_date',
        'return_date', 'status', 'fine_amount', 'fine_paid_at',
        'renewal_status', 'renewal_count', 'renewal_requested_at',
        'return_status', 'return_requested_at',
        'fine_payment_status', 'fine_payment_requested_at',
    ];

    protected $casts = [
        'borrow_date' => 'date',
        'due_date' => 'date',
        'return_date' => 'date',
        'fine_paid_at' => 'datetime',
        'renewal_requested_at' => 'datetime',
        'return_requested_at' => 'datetime',
        'fine_payment_requested_at' => 'datetime',
    ];

    public function borrower(): BelongsTo
    {
        return $this->belongsTo(User::class, 'borrower_id');
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function isOverdue(): bool
    {
        return ! in_array($this->status, ['returned', 'rejected', 'pending'])
            && $this->due_date
            && Carbon::today()->gt($this->due_date);
    }

    public function daysOverdue(): int
    {
        if (! $this->isOverdue()) {
            return 0;
        }

        return (int) abs(Carbon::today()->diffInDays($this->due_date));
    }

    /** Hitung denda saat ini secara dinamis */
    public function calculateCurrentFine(): int
    {
        if ($this->status === 'returned') {
            return (int) $this->fine_amount;
        }

        if ($this->isOverdue() || $this->status === 'overdue') {
            $finePerDay = (int) Setting::get('fine_per_day', 2000);
            return $this->daysOverdue() * $finePerDay;
        }

        return (int) $this->fine_amount;
    }

    /** Sinkronisasi status keterlambatan & denda berjalan untuk semua transaksi aktif */
    public static function syncOverdueLoans(): void
    {
        $today = Carbon::today();
        $finePerDay = (int) Setting::get('fine_per_day', 2000);

        self::whereIn('status', ['borrowed', 'overdue'])
            ->whereNotNull('due_date')
            ->whereDate('due_date', '<', $today)
            ->get()
            ->each(function (Loan $loan) use ($today, $finePerDay) {
                $daysLate = (int) abs($today->diffInDays($loan->due_date));
                $fine = $daysLate * $finePerDay;

                if ($loan->status !== 'overdue' || (int) $loan->fine_amount !== (int) $fine) {
                    $loan->update([
                        'status'      => 'overdue',
                        'fine_amount' => $fine,
                    ]);
                }
            });
    }

    /** Cek apakah ada anggota lain yang sedang antre/reservasi buku ini */
    public function hasPendingReservation(): bool
    {
        return self::where('book_id', $this->book_id)
            ->where('borrower_id', '!=', $this->borrower_id)
            ->where('status', 'pending')
            ->exists();
    }

    /** Cek apakah pinjaman ini memenuhi syarat perpanjangan */
    public function canBeRenewed(): bool
    {
        return $this->status === 'borrowed'
            && ! $this->isOverdue()
            && $this->renewal_status !== 'pending'
            && ! $this->hasPendingReservation();
    }

    /** Label & warna badge status, mengikuti semantic color PRD */
    public function statusBadge(): array
    {
        if ($this->return_status === 'pending') {
            return ['label' => 'Menunggu Konfirmasi Pengembalian', 'color' => 'amber'];
        }

        if ($this->fine_payment_status === 'pending') {
            return ['label' => 'Menunggu Konfirmasi Bayar Denda', 'color' => 'blue'];
        }

        if ($this->status === 'borrowed' && $this->renewal_status === 'pending') {
            return ['label' => 'Dipinjam (Perpanjangan Diajukan)', 'color' => 'blue'];
        }

        if ($this->isOverdue() || $this->status === 'overdue') {
            return ['label' => 'Terlambat (Overdue)', 'color' => 'red'];
        }

        return match ($this->status) {
            'pending'  => ['label' => 'Menunggu Persetujuan', 'color' => 'amber'],
            'borrowed' => ['label' => 'Dipinjam', 'color' => 'emerald'],
            'overdue'  => ['label' => 'Terlambat', 'color' => 'red'],
            'returned' => ['label' => 'Dikembalikan', 'color' => 'emerald'],
            'rejected' => ['label' => 'Ditolak', 'color' => 'red'],
            default    => ['label' => ucfirst($this->status), 'color' => 'stone'],
        };
    }
}
