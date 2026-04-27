<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('facility_pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('facility_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug');
            $table->longText('content_html')->nullable();
            $table->json('meta')->nullable();
            $table->boolean('is_home')->default(false);
            $table->string('status')->default('published');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->unique(['facility_id','slug']);
            $table->index(['facility_id','is_home']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facility_pages');
    }
};
