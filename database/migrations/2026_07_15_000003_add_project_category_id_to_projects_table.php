<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            if (!Schema::hasColumn('projects', 'project_category_id')) {
                $table->foreignId('project_category_id')->nullable()->after('project_type')
                    ->constrained('project_categories')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            if (Schema::hasColumn('projects', 'project_category_id')) {
                $table->dropForeign(['project_category_id']);
                $table->dropColumn('project_category_id');
            }
        });
    }
};
