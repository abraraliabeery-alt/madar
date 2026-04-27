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
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'available_for_rent')) {
                $table->boolean('available_for_rent')->default(false);
            }
            if (!Schema::hasColumn('products', 'available_for_sale')) {
                $table->boolean('available_for_sale')->default(false);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'available_for_sale')) {
                $table->dropColumn('available_for_sale');
            }
            if (Schema::hasColumn('products', 'available_for_rent')) {
                $table->dropColumn('available_for_rent');
            }
        });
    }
};
