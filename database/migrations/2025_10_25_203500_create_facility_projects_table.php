<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('facility_projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('facility_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug');
            $table->text('excerpt')->nullable();
            $table->longText('content')->nullable();
            $table->string('cover_image')->nullable();
            $table->json('gallery')->nullable();
            $table->string('status')->default('published');
            $table->integer('order')->default(0);
            $table->timestamps();
            $table->unique(['facility_id','slug']);
            $table->index(['facility_id','status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facility_projects');
    }
};
