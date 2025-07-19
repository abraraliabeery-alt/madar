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
        Schema::table('permission_translations', function (Blueprint $table) {
            $table->unsignedBigInteger('permission_id')->after('id');
            $table->string('locale')->after('permission_id');
            $table->string('name')->after('locale');
            $table->string('display_name')->nullable()->after('name');
            $table->text('description')->nullable()->after('display_name');

            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
            $table->unique(['permission_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permission_translations', function (Blueprint $table) {
            $table->dropForeign(['permission_id']);
            $table->dropUnique(['permission_id', 'locale']);
            $table->dropColumn(['permission_id', 'locale', 'name', 'display_name', 'description']);
        });
    }
};
