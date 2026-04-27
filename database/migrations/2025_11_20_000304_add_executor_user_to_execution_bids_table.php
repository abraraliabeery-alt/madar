<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('execution_bids', function (Blueprint $table) {
            $table->foreignId('executor_user_id')->nullable()->after('executor_facility_id')
                ->constrained('users')->nullOnDelete();
            $table->index(['executor_user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::table('execution_bids', function (Blueprint $table) {
            $table->dropForeign(['executor_user_id']);
            $table->dropIndex(['executor_user_id', 'status']);
            $table->dropColumn('executor_user_id');
        });
    }
};
