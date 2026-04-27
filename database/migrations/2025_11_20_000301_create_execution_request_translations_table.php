<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('execution_request_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('execution_request_id')->constrained('execution_requests')->cascadeOnDelete();
            $table->string('locale', 5)->index();
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->unique(['execution_request_id', 'locale'], 'exec_req_locale_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('execution_request_translations');
    }
};
