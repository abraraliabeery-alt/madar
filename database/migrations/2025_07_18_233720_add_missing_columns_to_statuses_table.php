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
        Schema::table('statuses', function (Blueprint $table) {
            $table->string('name')->after('id');
            $table->string('display_name')->nullable()->after('name');
            $table->string('color')->nullable()->after('display_name');
            $table->string('icon')->nullable()->after('color');
            $table->boolean('is_active')->default(true)->after('icon');
            $table->integer('order')->default(0)->after('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('statuses', function (Blueprint $table) {
            $table->dropColumn(['name', 'display_name', 'color', 'icon', 'is_active', 'order']);
        });
    }
};
