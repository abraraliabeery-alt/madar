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
        // Remove currency field from chart_of_accounts table
        Schema::table('chart_of_accounts', function (Blueprint $table) {
            $table->dropColumn('currency');
        });

        // Remove currency field from accounting_entries table
        Schema::table('accounting_entries', function (Blueprint $table) {
            $table->dropColumn('currency');
        });

        // Remove currency field from invoices table
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('currency');
        });

        // Remove currency and currency_rate fields from payments table
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['currency', 'currency_rate']);
        });

        // Remove currency field from offers table
        Schema::table('offers', function (Blueprint $table) {
            $table->dropColumn('currency');
        });

        // Remove currency field from contracts table
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropColumn('currency');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add currency field back to chart_of_accounts table
        Schema::table('chart_of_accounts', function (Blueprint $table) {
            $table->string('currency', 3)->default('SAR')->after('description');
        });

        // Add currency field back to accounting_entries table
        Schema::table('accounting_entries', function (Blueprint $table) {
            $table->string('currency', 3)->default('SAR')->after('amount');
        });

        // Add currency field back to invoices table
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('currency', 3)->default('SAR')->after('amount');
        });

        // Add currency and currency_rate fields back to payments table
        Schema::table('payments', function (Blueprint $table) {
            $table->string('currency', 3)->default('SAR')->after('amount');
            $table->decimal('currency_rate', 6, 4)->default(1)->after('processing_fee');
        });

        // Add currency field back to offers table
        Schema::table('offers', function (Blueprint $table) {
            $table->string('currency', 3)->default('SAR')->after('price');
        });

        // Add currency field back to contracts table
        Schema::table('contracts', function (Blueprint $table) {
            $table->string('currency', 3)->default('SAR')->after('total_amount');
        });
    }
};