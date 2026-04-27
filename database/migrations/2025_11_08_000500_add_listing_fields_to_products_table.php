<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('listing_type')->nullable()->after('additional_info'); // sale | rent | both
            $table->string('rent_period')->nullable()->after('listing_type'); // daily | monthly | yearly
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['listing_type', 'rent_period']);
        });
    }
};
