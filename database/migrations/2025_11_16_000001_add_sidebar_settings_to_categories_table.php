<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            if (!Schema::hasColumn('categories', 'show_in_facility_sidebar')) {
                $table->boolean('show_in_facility_sidebar')->default(false)->after('parent_id');
            }
            if (!Schema::hasColumn('categories', 'sidebar_label')) {
                $table->string('sidebar_label')->nullable()->after('show_in_facility_sidebar');
            }
            if (!Schema::hasColumn('categories', 'sidebar_icon')) {
                $table->string('sidebar_icon')->nullable()->after('sidebar_label');
            }
            if (!Schema::hasColumn('categories', 'sidebar_order')) {
                $table->integer('sidebar_order')->default(0)->after('sidebar_icon');
            }
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            if (Schema::hasColumn('categories', 'show_in_facility_sidebar')) {
                $table->dropColumn('show_in_facility_sidebar');
            }
            if (Schema::hasColumn('categories', 'sidebar_label')) {
                $table->dropColumn('sidebar_label');
            }
            if (Schema::hasColumn('categories', 'sidebar_icon')) {
                $table->dropColumn('sidebar_icon');
            }
            if (Schema::hasColumn('categories', 'sidebar_order')) {
                $table->dropColumn('sidebar_order');
            }
        });
    }
};
