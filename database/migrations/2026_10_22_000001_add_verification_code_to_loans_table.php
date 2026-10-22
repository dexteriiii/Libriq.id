<?php

use App\Models\Loan;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->string('verification_code', 10)->nullable()->index()->after('status');
        });

        // Set 5-digit verification code for any existing pending loans
        try {
            Loan::where('status', 'pending')->whereNull('verification_code')->get()->each(function ($loan) {
                $loan->update([
                    'verification_code' => (string) random_int(10000, 99999),
                ]);
            });
        } catch (\Throwable $e) {
            // ignore if model unavailable during early migration
        }
    }

    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropColumn('verification_code');
        });
    }
};
