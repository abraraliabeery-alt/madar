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
            // Real estate specific fields
            $table->unsignedBigInteger('city_id')->nullable()->after('category_id');
            $table->unsignedBigInteger('status_id')->nullable()->after('city_id');
            $table->unsignedBigInteger('building_id')->nullable()->after('status_id');
            $table->unsignedBigInteger('project_id')->nullable()->after('building_id');
            $table->unsignedBigInteger('package_id')->nullable()->after('project_id');
            
            // Property details
            $table->integer('bedrooms')->nullable()->after('additional_info');
            $table->integer('bathrooms')->nullable()->after('bedrooms');
            $table->decimal('area', 10, 2)->nullable()->after('bathrooms');
            $table->integer('floor_number')->nullable()->after('area');
            $table->integer('total_floors')->nullable()->after('floor_number');
            $table->integer('parking_spaces')->nullable()->after('total_floors');
            
            // Property features
            $table->boolean('furnished')->default(false)->after('parking_spaces');
            $table->boolean('available_for_rent')->default(false)->after('furnished');
            $table->boolean('available_for_sale')->default(false)->after('available_for_rent');
            
            // Rename image to main_image for consistency with controller
            $table->renameColumn('image', 'main_image');
            
            // Add foreign key constraints
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('set null');
            $table->foreign('status_id')->references('id')->on('statuses')->onDelete('set null');
            $table->foreign('building_id')->references('id')->on('buildings')->onDelete('set null');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('set null');
            $table->foreign('package_id')->references('id')->on('packages')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Drop foreign key constraints first
            $table->dropForeign(['city_id']);
            $table->dropForeign(['status_id']);
            $table->dropForeign(['building_id']);
            $table->dropForeign(['project_id']);
            $table->dropForeign(['package_id']);
            
            // Drop added columns
            $table->dropColumn([
                'city_id',
                'status_id', 
                'building_id',
                'project_id',
                'package_id',
                'bedrooms',
                'bathrooms',
                'area',
                'floor_number',
                'total_floors',
                'parking_spaces',
                'furnished',
                'available_for_rent',
                'available_for_sale'
            ]);
            
            // Rename main_image back to image
            $table->renameColumn('main_image', 'image');
        });
    }
};
