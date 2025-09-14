<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Add missing columns to existing offer_translations table.
     */
    public function up(): void
    {
        // Add missing columns to existing offer_translations table
        Schema::table('offer_translations', function (Blueprint $table) {
            if (!Schema::hasColumn('offer_translations', 'offer_title')) {
                $table->string('offer_title')->nullable()->after('locale');
            }
            if (!Schema::hasColumn('offer_translations', 'offer_description')) {
                $table->text('offer_description')->nullable()->after('offer_title');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove the columns that were added
        Schema::table('offer_translations', function (Blueprint $table) {
            if (Schema::hasColumn('offer_translations', 'offer_title')) {
                $table->dropColumn('offer_title');
            }
            if (Schema::hasColumn('offer_translations', 'offer_description')) {
                $table->dropColumn('offer_description');
            }
        });
    }
};
