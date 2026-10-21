<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('market_indices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('city_id')->nullable()->constrained('cities')->nullOnDelete();
            $table->string('property_type')->default('all'); // apartment, villa, office, all
            $table->decimal('avg_rent_yearly', 14, 2)->nullable();
            $table->decimal('avg_sale_sqm', 14, 2)->nullable();
            $table->string('period')->nullable(); // مثل 2026-Q1
            $table->string('source')->default('manual'); // manual, rega
            $table->timestamps();

            $table->index(['city_id', 'property_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('market_indices');
    }
};
