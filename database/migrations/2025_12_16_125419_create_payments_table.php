<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_kode')->unique();

            $table->foreignId('transaction_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('bank_id')
                ->constrained()
                ->restrictOnDelete();

            $table->date('payment_date');
            $table->decimal('amount', 18, 2);
            $table->string('proof')->nullable();

            $table->enum('status', ['PROSES', 'SUCCESS', 'REJECTED'])
                ->default('PROSES');

            $table->foreignId('verified_by')
                ->nullable()
                ->constrained('users');

            $table->timestamp('verified_at')->nullable();
            $table->string('code_payment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
