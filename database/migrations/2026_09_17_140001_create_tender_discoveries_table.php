<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tender_discoveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('source_id')->constrained('tender_sources')->cascadeOnDelete();
            $table->string('title');
            $table->string('reference')->nullable();
            $table->string('url')->nullable();
            $table->date('closing_date')->nullable();
            $table->text('description')->nullable();
            $table->json('raw_data')->nullable();
            $table->boolean('is_imported')->default(false);
            $table->foreignId('imported_facility_tender_id')->nullable()->constrained('facility_tenders')->nullOnDelete();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index(['source_id', 'is_imported']);
            $table->index('closing_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tender_discoveries');
    }
};
