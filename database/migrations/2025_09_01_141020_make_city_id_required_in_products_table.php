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
            // First, update existing products to have a default city_id if they don't have one
            // Get the first active city
            $firstCity = \App\Models\City::where('is_active', true)->first();
            if ($firstCity) {
                \DB::table('products')->whereNull('city_id')->update(['city_id' => $firstCity->id]);
            }
            
            // Drop the foreign key constraint first
            $table->dropForeign(['city_id']);
            
            // Make city_id required
            $table->unsignedBigInteger('city_id')->nullable(false)->change();
            
            // Add back the foreign key constraint with cascade delete
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Make city_id nullable again
            $table->unsignedBigInteger('city_id')->nullable()->change();
            
            // Restore original foreign key constraint
            $table->dropForeign(['city_id']);
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('set null');
        });
    }
};
