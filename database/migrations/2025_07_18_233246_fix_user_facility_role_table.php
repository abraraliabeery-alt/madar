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
        Schema::table('user_facility_role', function (Blueprint $table) {
            $table->unsignedBigInteger('facility_id')->nullable()->change();

            // Add foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('facility_id')->references('id')->on('facilities')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_facility_role', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['facility_id']);
            $table->dropForeign(['role_id']);
            $table->unsignedBigInteger('facility_id')->nullable(false)->change();
        });
    }
};
