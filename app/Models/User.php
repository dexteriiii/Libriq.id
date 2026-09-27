<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'member_id',
        'avatar_path',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar_path && Storage::disk('public')->exists($this->avatar_path)) {
            return asset('storage/' . $this->avatar_path);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=ea580c&background=ffedd5';
    }

    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class, 'borrower_id');
    }

    public function activeLoans()
    {
        return $this->loans()->whereIn('status', ['pending', 'borrowed', 'overdue']);
    }

    public function hasOverdueLoans(): bool
    {
        return $this->loans()
            ->whereIn('status', ['borrowed', 'overdue'])
            ->whereNotNull('due_date')
            ->whereDate('due_date', '<', Carbon::today())
            ->exists();
    }

    public function overdueLoansCount(): int
    {
        return $this->loans()
            ->whereIn('status', ['borrowed', 'overdue'])
            ->whereNotNull('due_date')
            ->whereDate('due_date', '<', Carbon::today())
            ->count();
    }

    public function unpaidFines(): int
    {
        return $this->totalUnpaidFines();
    }

    public function totalUnpaidFines(): int
    {
        // 1. Denda dari buku yang sudah dikembalikan tapi belum dibayar
        $returnedFines = (int) $this->loans()
            ->where('status', 'returned')
            ->whereNull('fine_paid_at')
            ->where('fine_amount', '>', 0)
            ->sum('fine_amount');

        // 2. Denda berjalan dari buku aktif yang sedang terlambat
        $finePerDay = (int) Setting::get('fine_per_day', 2000);
        $today = Carbon::today();
        $runningFines = 0;

        $overdueLoans = $this->loans()
            ->whereIn('status', ['borrowed', 'overdue'])
            ->whereNotNull('due_date')
            ->whereDate('due_date', '<', $today)
            ->get();

        foreach ($overdueLoans as $loan) {
            $daysLate = (int) abs($today->diffInDays($loan->due_date));
            $runningFines += ($daysLate * $finePerDay);
        }

        return $returnedFines + $runningFines;
    }

    public function canBorrowBooks(): bool
    {
        if ($this->hasOverdueLoans()) {
            return false;
        }

        if ($this->totalUnpaidFines() > 0) {
            return false;
        }

        return true;
    }

    public function getBorrowBlockReason(): ?string
    {
        $hasOverdue = $this->hasOverdueLoans();
        $unpaidFine = $this->totalUnpaidFines();

        if ($hasOverdue && $unpaidFine > 0) {
            return 'Kamu memiliki buku yang terlambat dikembalikan dan denda belum lunas sebesar Rp' . number_format($unpaidFine, 0, ',', '.') . '. Harap kembalikan buku dan lunasi denda ke admin sebelum meminjam buku baru.';
        }

        if ($hasOverdue) {
            return 'Kamu memiliki buku yang melewati batas jatuh tempo (terlambat). Harap kembalikan buku ke perpustakaan sebelum meminjam buku baru.';
        }

        if ($unpaidFine > 0) {
            return 'Kamu memiliki denda belum lunas sebesar Rp' . number_format($unpaidFine, 0, ',', '.') . '. Harap lunasi denda ke admin sebelum meminjam buku baru.';
        }

        return null;
    }

    public function isMember(): bool
    {
        return $this->role === 'member';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}