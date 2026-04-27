<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('facility_user')) {
            Schema::table('facility_user', function (Blueprint $table) {
                if (!Schema::hasColumn('facility_user', 'department_id')) {
                    $table->unsignedBigInteger('department_id')->nullable()->after('facility_id');
                }
                if (!Schema::hasColumn('facility_user', 'position_id')) {
                    $table->unsignedBigInteger('position_id')->nullable()->after('department_id');
                }
                if (!Schema::hasColumn('facility_user', 'hire_date')) {
                    $table->date('hire_date')->nullable()->after('position_id');
                }
                if (!Schema::hasColumn('facility_user', 'employment_type')) {
                    $table->string('employment_type', 50)->nullable()->after('hire_date');
                }
                if (!Schema::hasColumn('facility_user', 'employment_status')) {
                    $table->string('employment_status', 50)->default('active')->after('employment_type');
                }

                $table->index(['department_id']);
                $table->index(['position_id']);

                $table->foreign('department_id')->references('id')->on('departments')->nullOnDelete();
                $table->foreign('position_id')->references('id')->on('positions')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('facility_user')) {
            Schema::table('facility_user', function (Blueprint $table) {
                if (Schema::hasColumn('facility_user', 'department_id')) {
                    $table->dropForeign(['department_id']);
                    $table->dropIndex(['department_id']);
                    $table->dropColumn('department_id');
                }
                if (Schema::hasColumn('facility_user', 'position_id')) {
                    $table->dropForeign(['position_id']);
                    $table->dropIndex(['position_id']);
                    $table->dropColumn('position_id');
                }
                if (Schema::hasColumn('facility_user', 'hire_date')) {
                    $table->dropColumn('hire_date');
                }
                if (Schema::hasColumn('facility_user', 'employment_type')) {
                    $table->dropColumn('employment_type');
                }
                if (Schema::hasColumn('facility_user', 'employment_status')) {
                    $table->dropColumn('employment_status');
                }
            });
        }
    }
};
