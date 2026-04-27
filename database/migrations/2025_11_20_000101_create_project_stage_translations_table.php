<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_stage_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_stage_id')->constrained('project_stages')->onDelete('cascade');
            $table->string('locale', 5)->index();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->unique(['project_stage_id', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_stage_translations');
    }
};
