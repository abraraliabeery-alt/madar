<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('facility_category_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('facility_category_id');
            $table->string('locale');
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->foreign('facility_category_id')->references('id')->on('facility_categories')->onDelete('cascade');
            $table->unique(['facility_category_id', 'locale'], 'facility_cat_trans_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facility_category_translations');
    }
};
