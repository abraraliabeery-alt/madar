<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'building_id')) {
                $table->dropForeign(['building_id']);
                $table->dropColumn('building_id');
            }
        });

        Schema::dropIfExists('building_translations');
        Schema::dropIfExists('buildings');
    }

    public function down(): void
    {
        Schema::create('buildings', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('building_id')->nullable()->constrained('buildings')->nullOnDelete();
        });
    }
};
