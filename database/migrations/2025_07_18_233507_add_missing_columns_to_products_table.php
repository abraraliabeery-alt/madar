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
            $table->string('title')->after('id');
            $table->text('description')->nullable()->after('title');
            $table->string('address')->nullable()->after('description');
            $table->integer('rooms')->nullable()->after('address');
            $table->integer('bathrooms')->nullable()->after('rooms');
            $table->decimal('area', 10, 2)->nullable()->after('bathrooms');
            $table->string('floor')->nullable()->after('area');
            $table->integer('floors_count')->nullable()->after('floor');
            $table->integer('parking_spaces')->nullable()->after('floors_count');
            $table->boolean('is_featured')->default(false)->after('is_active');
            $table->boolean('is_verified')->default(false)->after('is_featured');
            $table->integer('views_count')->default(0)->after('is_verified');
            $table->decimal('rating', 3, 2)->default(0)->after('views_count');
            $table->integer('rating_count')->default(0)->after('rating');
            $table->string('booking_number')->unique()->nullable()->after('rating_count');
            $table->date('available_from')->nullable()->after('booking_number');
            $table->date('available_to')->nullable()->after('available_from');
            $table->string('contact_phone')->nullable()->after('available_to');
            $table->string('contact_email')->nullable()->after('contact_phone');
            $table->text('additional_info')->nullable()->after('contact_email');

            // Add foreign key constraints
            $table->foreign('facility_id')->references('id')->on('facilities')->onDelete('set null');
            $table->foreign('owner_user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('seller_user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['facility_id']);
            $table->dropForeign(['owner_user_id']);
            $table->dropForeign(['seller_user_id']);
            $table->dropForeign(['category_id']);

            $table->dropColumn([
                'title', 'description', 'address', 'rooms', 'bathrooms', 'area',
                'floor', 'floors_count', 'parking_spaces', 'is_featured', 'is_verified',
                'views_count', 'rating', 'rating_count', 'booking_number', 'available_from',
                'available_to', 'contact_phone', 'contact_email', 'additional_info'
            ]);
        });
    }
};
