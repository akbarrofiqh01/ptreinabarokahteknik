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
        Schema::create('transaction_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('item_name');
            $table->integer('qty');
            $table->decimal('price', 18, 2);
            $table->decimal('total', 18, 2);

            $table->boolean('is_ready')->default(false);
            $table->timestamp('ready_at')->nullable();

            $table->enum('source', ['MANUAL', 'EXCEL'])->default('MANUAL');
            $table->string('code_t_item')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_items');
    }
};
