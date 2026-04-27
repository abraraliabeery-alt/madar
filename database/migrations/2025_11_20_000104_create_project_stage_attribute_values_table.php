<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('project_stage_attribute_values')) {
            Schema::create('project_stage_attribute_values', function (Blueprint $table) {
                $table->id();
                $table->foreignId('project_stage_id')->constrained('project_stages')->onDelete('cascade');
                $table->foreignId('stage_attribute_id')->constrained('stage_attributes')->onDelete('cascade');
                $table->text('value')->nullable();
                $table->timestamps();

                // اسم مختصر للـ index لتفادي حد طول الأسماء في MySQL
                $table->unique(['project_stage_id', 'stage_attribute_id'], 'ps_stage_attr_unique');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('project_stage_attribute_values');
    }
};
