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
        Schema::table('features', function (Blueprint $table) {
            // Check if columns exist before dropping them
            if (Schema::hasColumn('features', 'name')) {
                $table->dropColumn('name');
            }
            if (Schema::hasColumn('features', 'display_name')) {
                $table->dropColumn('display_name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('features', function (Blueprint $table) {
            // Add back the removed columns
            if (!Schema::hasColumn('features', 'name')) {
                $table->string('name')->after('id');
            }
            if (!Schema::hasColumn('features', 'display_name')) {
                $table->string('display_name')->nullable()->after('name');
            }
        });
    }
};
