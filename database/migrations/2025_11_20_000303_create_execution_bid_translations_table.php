<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('execution_bid_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('execution_bid_id')->constrained('execution_bids')->cascadeOnDelete();
            $table->string('locale', 5)->index();
            $table->string('title')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['execution_bid_id', 'locale'], 'exec_bid_locale_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('execution_bid_translations');
    }
};
