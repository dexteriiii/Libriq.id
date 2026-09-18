<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->string('renewal_status')->default('none')->after('status');
            $table->unsignedInteger('renewal_count')->default(0)->after('renewal_status');
            $table->timestamp('renewal_requested_at')->nullable()->after('renewal_count');
        });
    }

    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropColumn(['renewal_status', 'renewal_count', 'renewal_requested_at']);
        });
    }
};
