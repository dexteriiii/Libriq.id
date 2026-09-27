<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->string('return_status')->default('none')->after('renewal_requested_at');
            $table->timestamp('return_requested_at')->nullable()->after('return_status');
            $table->string('fine_payment_status')->default('none')->after('fine_paid_at');
            $table->timestamp('fine_payment_requested_at')->nullable()->after('fine_payment_status');
        });
    }

    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropColumn([
                'return_status',
                'return_requested_at',
                'fine_payment_status',
                'fine_payment_requested_at',
            ]);
        });
    }
};
