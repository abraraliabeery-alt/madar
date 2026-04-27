<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('land_studies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->json('inputs');
            $table->longText('report')->nullable();
            $table->json('scenarios')->nullable();
            $table->json('images')->nullable();
            $table->string('status')->default('pending');
            $table->decimal('cost_usd', 8, 2)->nullable();
            $table->text('error')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('land_studies');
    }
};
