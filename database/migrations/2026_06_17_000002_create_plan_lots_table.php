<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plan_lots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->constrained('plans')->cascadeOnDelete();

            $table->string('lot_number');
            $table->string('usage')->nullable();
            $table->enum('status', ['available', 'reserved', 'sold'])->default('available');

            $table->decimal('area_m2', 12, 2)->nullable();
            $table->unsignedBigInteger('price')->nullable();

            $table->json('geometry')->nullable();
            $table->json('centroid')->nullable();

            $table->timestamps();

            $table->unique(['plan_id', 'lot_number']);
            $table->index(['plan_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plan_lots');
    }
};
