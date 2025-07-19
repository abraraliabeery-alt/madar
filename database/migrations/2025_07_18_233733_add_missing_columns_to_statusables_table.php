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
        Schema::table('statusables', function (Blueprint $table) {
            $table->text('notes')->nullable()->after('statusable_id');
            $table->unsignedBigInteger('user_id')->nullable()->after('notes');

            // Add foreign key constraints
            $table->foreign('status_id')->references('id')->on('statuses')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');

            // Add indexes
            $table->index(['statusable_type', 'statusable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('statusables', function (Blueprint $table) {
            $table->dropForeign(['status_id']);
            $table->dropForeign(['user_id']);
            $table->dropIndex(['statusable_type', 'statusable_id']);
            $table->dropColumn(['notes', 'user_id']);
        });
    }
};
