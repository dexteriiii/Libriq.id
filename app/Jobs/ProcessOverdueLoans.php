<?php

namespace App\Jobs;

use App\Models\Loan;
use App\Models\Notification;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessOverdueLoans implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $today      = Carbon::today();
        $finePerDay = (int) Setting::get('fine_per_day', 2000);

        // ── 1. Peringatan Jatuh Tempo (H-3 dan H-1) ─────────────────────────
        $upcomingDueLoans = Loan::with(['book', 'borrower'])
            ->where('status', 'borrowed')
            ->whereNotNull('due_date')
            ->whereIn('due_date', [
                $today->copy()->addDays(3)->toDateString(),
                $today->copy()->addDays(1)->toDateString(),
            ])
            ->get();

        foreach ($upcomingDueLoans as $loan) {
            $daysLeft = $today->diffInDays($loan->due_date);
            $msg = "Peringatan Jatuh Tempo: Buku \"{$loan->book->title}\" harus dikembalikan dalam {$daysLeft} hari lagi ({$loan->due_date->format('d M Y')}).";

            $alreadyNotifiedToday = Notification::where('user_id', $loan->borrower_id)
                ->where('type', 'due_soon_warning')
                ->whereDate('created_at', $today)
                ->exists();

            if (! $alreadyNotifiedToday) {
                Notification::create([
                    'user_id'    => $loan->borrower_id,
                    'type'       => 'due_soon_warning',
                    'message'    => $msg,
                    'created_at' => now(),
                ]);
            }
        }

        // ── 2. Proses Overdue & Kalkulasi Denda Harian ────────────────────────
        $overdueLoans = Loan::with(['book', 'borrower'])
            ->whereIn('status', ['borrowed', 'overdue'])
            ->whereNotNull('due_date')
            ->where('due_date', '<', $today)
            ->get();

        $processedCount = 0;

        foreach ($overdueLoans as $loan) {
            $daysLate  = $today->diffInDays($loan->due_date);
            $fineAmount = $daysLate * $finePerDay;

            $wasAlreadyOverdue = $loan->status === 'overdue';

            $loan->update([
                'status'      => 'overdue',
                'fine_amount' => $fineAmount,
            ]);

            // Kirim notifikasi hanya pertama kali status berubah ke overdue
            if (! $wasAlreadyOverdue) {
                Notification::create([
                    'user_id'    => $loan->borrower_id,
                    'type'       => 'loan_overdue',
                    'message'    => "Buku \"{$loan->book->title}\" sudah melewati batas pengembalian ({$loan->due_date->format('d M Y')}). Denda saat ini: Rp" . number_format($fineAmount, 0, ',', '.') . '. Segera kembalikan untuk menghindari denda lebih lanjut.',
                    'created_at' => now(),
                ]);
            }

            $processedCount++;
        }

        Log::info("ProcessOverdueLoans: {$processedCount} loans processed on {$today->toDateString()}.");
    }
}
