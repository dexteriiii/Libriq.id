<?php

use App\Jobs\ProcessOverdueLoans;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Hitung denda overdue setiap hari pukul 00:05 (PRD 4.4)
Schedule::job(ProcessOverdueLoans::class)->dailyAt('00:05');
