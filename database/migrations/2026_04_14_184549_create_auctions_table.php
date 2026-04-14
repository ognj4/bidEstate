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
        Schema::create('auctions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->foreignId('winner_id')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('start_price', 12, 2);
            $table->decimal('current_price', 12, 2);
            $table->decimal('min_increment', 10, 2)->default(500);
            $table->decimal('buy_now_price', 12, 2)->nullable();
            $table->dateTime('ends_at');
            $table->enum('status', ['pending', 'active', 'finished', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auctions');
    }
};
