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
        Schema::table('facilities', function (Blueprint $table) {
            // Add the new facility_category_id field
            $table->unsignedBigInteger('facility_category_id')->nullable();
            $table->foreign('facility_category_id')->references('id')->on('facility_categories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('facilities', function (Blueprint $table) {
            // Remove the new facility_category_id field
            $table->dropForeign(['facility_category_id']);
            $table->dropColumn('facility_category_id');
        });
    }
};
