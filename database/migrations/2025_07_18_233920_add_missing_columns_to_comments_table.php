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
        // Add missing columns if they don't exist (safe for fresh installs and existing DBs)
        if (!Schema::hasColumn('comments', 'product_id') ||
            !Schema::hasColumn('comments', 'user_id') ||
            !Schema::hasColumn('comments', 'comment') ||
            !Schema::hasColumn('comments', 'rating')) {

            Schema::table('comments', function (Blueprint $table) {
                if (!Schema::hasColumn('comments', 'product_id')) {
                    $table->unsignedBigInteger('product_id')->index()->after('id');
                }
                if (!Schema::hasColumn('comments', 'user_id')) {
                    $table->unsignedBigInteger('user_id')->index()->after('product_id');
                }
                if (!Schema::hasColumn('comments', 'comment')) {
                    $table->text('comment')->after('user_id');
                }
                if (!Schema::hasColumn('comments', 'rating')) {
                    $table->unsignedTinyInteger('rating')->nullable()->after('comment');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            if (Schema::hasColumn('comments', 'rating')) {
                $table->dropColumn('rating');
            }
            if (Schema::hasColumn('comments', 'comment')) {
                $table->dropColumn('comment');
            }
            if (Schema::hasColumn('comments', 'user_id')) {
                $table->dropIndex(['user_id']);
                $table->dropColumn('user_id');
            }
            if (Schema::hasColumn('comments', 'product_id')) {
                $table->dropIndex(['product_id']);
                $table->dropColumn('product_id');
            }
        });
    }
};


