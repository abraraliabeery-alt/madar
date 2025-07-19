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
        Schema::table('facilities', function (Blueprint $table) {
            $table->string('name')->after('id');
            $table->text('description')->nullable()->after('name');
            $table->string('address')->nullable()->after('description');
            $table->string('phone')->nullable()->after('address');
            $table->string('email')->nullable()->after('phone');
            $table->string('website')->nullable()->after('email');
            $table->string('license_number')->nullable()->after('website');
            $table->date('license_expiry')->nullable()->after('license_number');
            $table->boolean('is_verified')->default(false)->after('is_primary');
            $table->boolean('is_featured')->default(false)->after('is_verified');
            $table->decimal('rating', 3, 2)->default(0)->after('is_featured');
            $table->integer('rating_count')->default(0)->after('rating');
            $table->integer('products_count')->default(0)->after('rating_count');
            $table->unsignedBigInteger('category_id')->nullable()->after('products_count');
            $table->unsignedBigInteger('owner_user_id')->nullable()->after('category_id');

            // Add foreign key constraints
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            $table->foreign('owner_user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('facilities', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropForeign(['owner_user_id']);

            $table->dropColumn([
                'name', 'description', 'address', 'phone', 'email', 'website',
                'license_number', 'license_expiry', 'is_verified', 'is_featured',
                'rating', 'rating_count', 'products_count', 'category_id', 'owner_user_id'
            ]);
        });
    }
};
