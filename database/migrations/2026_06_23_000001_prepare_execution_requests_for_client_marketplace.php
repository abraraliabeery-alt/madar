<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('execution_requests', function (Blueprint $table) {
            $table->unsignedBigInteger('facility_id')->nullable()->change();
            $table->foreignId('client_user_id')->nullable()->after('facility_id')
                ->constrained('users')->nullOnDelete();
            $table->index(['client_user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::table('execution_requests', function (Blueprint $table) {
            $table->dropIndex(['client_user_id', 'status']);
            $table->dropForeign(['client_user_id']);
            $table->dropColumn('client_user_id');
            $table->unsignedBigInteger('facility_id')->nullable(false)->change();
        });
    }
};
