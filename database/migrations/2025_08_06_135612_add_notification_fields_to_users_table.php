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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('notification_email')->default(true)->after('telegram');
            $table->boolean('notification_sms')->default(false)->after('notification_email');
            $table->boolean('notification_push')->default(true)->after('notification_sms');
            $table->enum('notification_frequency', ['immediate', 'hourly', 'daily', 'weekly'])->default('immediate')->after('notification_push');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['notification_email', 'notification_sms', 'notification_push', 'notification_frequency']);
        });
    }
};
