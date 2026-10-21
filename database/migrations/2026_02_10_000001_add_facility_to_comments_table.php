<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->nullable()->change();
            $table->unsignedBigInteger('facility_id')->nullable()->after('product_id');
        });
    }

    public function down(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropColumn('facility_id');
            $table->unsignedBigInteger('product_id')->nullable(false)->change();
        });
    }
};
