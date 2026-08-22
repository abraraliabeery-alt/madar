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
        Schema::table('package_translations', function (Blueprint $table) {
            if (!Schema::hasColumn('package_translations', 'package_id')) {
                $table->unsignedBigInteger('package_id')->nullable()->index()->after('id');
            }
            if (!Schema::hasColumn('package_translations', 'locale')) {
                $table->string('locale', 10)->nullable()->after('package_id');
            }
            if (!Schema::hasColumn('package_translations', 'name')) {
                $table->string('name')->nullable()->after('locale');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('package_translations', function (Blueprint $table) {
            $table->dropColumn(['package_id', 'locale', 'name']);
        });
    }
};
