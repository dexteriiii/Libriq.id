<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('borrower_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('book_id')->constrained('books')->cascadeOnDelete();
            $table->date('borrow_date')->nullable();  // diisi saat admin fulfillment
            $table->date('due_date')->nullable();
            $table->date('return_date')->nullable();
            $table->enum('status', ['pending', 'borrowed', 'overdue', 'returned', 'rejected'])
                  ->default('pending');
            $table->unsignedInteger('fine_amount')->default(0);
            $table->timestamp('fine_paid_at')->nullable();
            $table->timestamps();

            $table->index(['borrower_id', 'status']);
            $table->index('due_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};