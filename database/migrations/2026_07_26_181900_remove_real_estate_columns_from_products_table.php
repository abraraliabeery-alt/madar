<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $columns = [
            'bedrooms',
            'bathrooms',
            'area',
            'floor_number',
            'total_floors',
            'parking_spaces',
            'furnished',
            'available_for_rent',
            'available_for_sale',
            'listing_type',
            'rent_period',
        ];

        $existing = array_filter($columns, fn ($column) => Schema::hasColumn('products', $column));

        if (! empty($existing)) {
            Schema::table('products', function (Blueprint $table) use ($existing) {
                $table->dropColumn($existing);
            });
        }
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->integer('bedrooms')->nullable()->after('additional_info');
            $table->integer('bathrooms')->nullable()->after('bedrooms');
            $table->decimal('area', 10, 2)->nullable()->after('bathrooms');
            $table->integer('floor_number')->nullable()->after('area');
            $table->integer('total_floors')->nullable()->after('floor_number');
            $table->integer('parking_spaces')->nullable()->after('total_floors');
            $table->boolean('furnished')->default(false)->after('parking_spaces');
            $table->boolean('available_for_rent')->default(false)->after('furnished');
            $table->boolean('available_for_sale')->default(false)->after('available_for_rent');
            $table->string('listing_type')->nullable()->after('available_for_sale');
            $table->string('rent_period')->nullable()->after('listing_type');
        });
    }
};
