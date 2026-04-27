<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('execution_bids', function (Blueprint $table) {
            $table->id();
            $table->foreignId('execution_request_id')->constrained('execution_requests')->cascadeOnDelete();
            $table->foreignId('executor_facility_id')->constrained('facilities')->cascadeOnDelete();
            $table->decimal('price_total', 15, 2)->nullable();
            $table->string('currency', 10)->nullable();
            $table->unsignedInteger('duration_days')->nullable();
            $table->unsignedInteger('warranty_months')->nullable();
            $table->string('status')->default('pending'); // pending, accepted, rejected, withdrawn
            $table->unsignedTinyInteger('score')->nullable();
            $table->json('data')->nullable();
            $table->timestamps();

            $table->index(['execution_request_id', 'status']);
            $table->index(['executor_facility_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('execution_bids');
    }
};
