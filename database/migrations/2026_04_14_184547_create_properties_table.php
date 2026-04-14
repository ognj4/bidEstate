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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description');
            $table->enum('type', ['stan', 'kuca', 'zemljiste', 'poslovni']);
            $table->decimal('area_m2', 8, 2);
            $table->unsignedInteger('rooms')->nullable();
            $table->unsignedInteger('floor')->nullable();
            $table->unsignedInteger('total_floors')->nullable();
            $table->string('city');
            $table->string('address');
            $table->unsignedInteger('year_built')->nullable();
            $table->enum('status', ['draft', 'active', 'sold', 'cancelled'])->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
