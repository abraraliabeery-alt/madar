<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stage_attribute_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stage_attribute_id')->constrained('stage_attributes')->onDelete('cascade');
            $table->string('locale', 5)->index();
            $table->string('name');
            $table->string('symbol')->nullable();
            $table->timestamps();

            $table->unique(['stage_attribute_id', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stage_attribute_translations');
    }
};
