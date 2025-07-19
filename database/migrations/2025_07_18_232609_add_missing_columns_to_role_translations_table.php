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
        Schema::table('role_translations', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id')->after('id');
            $table->string('locale')->after('role_id');
            $table->string('name')->after('locale');
            $table->string('display_name')->nullable()->after('name');
            $table->text('description')->nullable()->after('display_name');

            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->unique(['role_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('role_translations', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropUnique(['role_id', 'locale']);
            $table->dropColumn(['role_id', 'locale', 'name', 'display_name', 'description']);
        });
    }
};
