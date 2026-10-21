<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tender_sources', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('country', 10)->default('sa');
            $table->string('base_url');
            $table->string('list_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('config')->nullable();
            $table->timestamp('last_crawled_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tender_sources');
    }
};
