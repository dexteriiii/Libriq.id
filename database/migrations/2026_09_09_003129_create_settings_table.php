<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->string('value');
            $table->timestamps();
        });

        // Nilai default sesuai aturan bisnis pada PRD
        Schema::table('settings', function () {
            \Illuminate\Support\Facades\DB::table('settings')->insert([
                ['key' => 'loan_duration_days', 'value' => '7', 'created_at' => now(), 'updated_at' => now()],
                ['key' => 'max_active_loans', 'value' => '3', 'created_at' => now(), 'updated_at' => now()],
                ['key' => 'fine_per_day', 'value' => '2000', 'created_at' => now(), 'updated_at' => now()],
                ['key' => 'max_unpaid_fine', 'value' => '20000', 'created_at' => now(), 'updated_at' => now()],
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};