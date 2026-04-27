<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stage_attributes', function (Blueprint $table) {
            $table->id();
            $table->string('stage_key')->index();
            $table->string('type');
            $table->boolean('required')->default(false);
            $table->string('icon')->nullable();
            $table->string('symbol')->nullable();
            $table->boolean('show_in_stage_card')->default(false);
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stage_attributes');
    }
};
