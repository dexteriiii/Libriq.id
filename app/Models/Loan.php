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
    ];

    protected $casts = [
        'borrow_date' => 'date',
        'due_date' => 'date',
        'return_date' => 'date',
        'fine_paid_at' => 'datetime',
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
        return $this->status !== 'returned'
            && $this->due_date
            && Carbon::today()->gt($this->due_date);
    }

    public function daysOverdue(): int
    {
        if (! $this->isOverdue()) {
            return 0;
        }

        return Carbon::today()->diffInDays($this->due_date);
    }

    /** Label & warna badge status, mengikuti semantic color PRD */
    public function statusBadge(): array
    {
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
