<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('facility_tenders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('facility_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('reference')->nullable();
            $table->date('issue_date')->nullable();
            $table->string('cover_style')->nullable();
            $table->string('status')->default('draft');
            $table->json('data')->nullable();
            $table->timestamps();
            $table->index(['facility_id','status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facility_tenders');
    }
};
