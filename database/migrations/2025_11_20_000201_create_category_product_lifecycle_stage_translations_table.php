<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('category_product_lifecycle_stage_translations')) {
            Schema::create('category_product_lifecycle_stage_translations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('stage_id')->constrained('category_product_lifecycle_stages')->onDelete('cascade');
                $table->string('locale', 5)->index();
                $table->string('name');
                $table->text('description')->nullable();
                $table->timestamps();

                $table->unique(['stage_id', 'locale'], 'cpl_stage_locale_unique');
            });
        } else {
            Schema::table('category_product_lifecycle_stage_translations', function (Blueprint $table) {
                $table->unique(['stage_id', 'locale'], 'cpl_stage_locale_unique');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('category_product_lifecycle_stage_translations');
    }
};
