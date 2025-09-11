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
            $table->string('profile_picture')->nullable()->after('avatar');
            $table->text('bio')->nullable()->after('profile_picture');
            $table->string('location')->nullable()->after('bio');
            $table->date('date_of_birth')->nullable()->after('location');
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('date_of_birth');
            $table->timestamp('phone_verified_at')->nullable()->after('email_verified_at');
            $table->timestamp('last_login_at')->nullable()->after('phone_verified_at');
            $table->boolean('two_factor_enabled')->default(false)->after('last_login_at');
            $table->json('notification_settings')->nullable()->after('two_factor_enabled');
            $table->json('privacy_settings')->nullable()->after('notification_settings');
            $table->json('preferences')->nullable()->after('privacy_settings');
            $table->json('security_settings')->nullable()->after('preferences');
            $table->timestamp('password_changed_at')->nullable()->after('security_settings');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'profile_picture',
                'bio',
                'location',
                'date_of_birth',
                'gender',
                'phone_verified_at',
                'last_login_at',
                'two_factor_enabled',
                'notification_settings',
                'privacy_settings',
                'preferences',
                'security_settings',
                'password_changed_at',
            ]);
        });
    }
};
