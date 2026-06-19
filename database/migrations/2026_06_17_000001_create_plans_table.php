<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('plan_number')->nullable();
            $table->decimal('center_lat', 12, 9)->nullable();
            $table->decimal('center_lng', 12, 9)->nullable();
            $table->decimal('area_km2', 10, 4)->nullable();

            $table->string('boundary_geojson_path')->nullable();
            $table->string('overlay_image_url')->nullable();

            $table->json('bounds')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
