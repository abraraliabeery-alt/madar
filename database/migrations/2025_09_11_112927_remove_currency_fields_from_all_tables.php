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
        // Remove currency field from chart_of_accounts table (if it exists)
        if (Schema::hasColumn('chart_of_accounts', 'currency')) {
            Schema::table('chart_of_accounts', function (Blueprint $table) {
                $table->dropColumn('currency');
            });
        }

        // Remove currency field from accounting_entries table (if it exists)
        if (Schema::hasColumn('accounting_entries', 'currency')) {
            Schema::table('accounting_entries', function (Blueprint $table) {
                $table->dropColumn('currency');
            });
        }

        // Remove currency field from invoices table (if it exists)
        if (Schema::hasColumn('invoices', 'currency')) {
            Schema::table('invoices', function (Blueprint $table) {
                $table->dropColumn('currency');
            });
        }

        // Remove currency and currency_rate fields from payments table (if they exist)
        if (Schema::hasColumn('payments', 'currency') || Schema::hasColumn('payments', 'currency_rate')) {
            Schema::table('payments', function (Blueprint $table) {
                $columnsToDrop = [];
                if (Schema::hasColumn('payments', 'currency')) {
                    $columnsToDrop[] = 'currency';
                }
                if (Schema::hasColumn('payments', 'currency_rate')) {
                    $columnsToDrop[] = 'currency_rate';
                }
                $table->dropColumn($columnsToDrop);
            });
        }

        // Remove currency field from offers table (if it exists)
        if (Schema::hasColumn('offers', 'currency')) {
            Schema::table('offers', function (Blueprint $table) {
                $table->dropColumn('currency');
            });
        }

        // Remove currency field from contracts table (if it exists)
        if (Schema::hasColumn('contracts', 'currency')) {
            Schema::table('contracts', function (Blueprint $table) {
                $table->dropColumn('currency');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add currency field back to chart_of_accounts table (if it doesn't exist)
        if (!Schema::hasColumn('chart_of_accounts', 'currency')) {
            Schema::table('chart_of_accounts', function (Blueprint $table) {
                $table->string('currency', 3)->default('SAR')->after('description');
            });
        }

        // Add currency field back to accounting_entries table (if it doesn't exist)
        if (!Schema::hasColumn('accounting_entries', 'currency')) {
            Schema::table('accounting_entries', function (Blueprint $table) {
                $table->string('currency', 3)->default('SAR')->after('amount');
            });
        }

        // Add currency field back to invoices table (if it doesn't exist)
        if (!Schema::hasColumn('invoices', 'currency')) {
            Schema::table('invoices', function (Blueprint $table) {
                $table->string('currency', 3)->default('SAR')->after('amount');
            });
        }

        // Add currency and currency_rate fields back to payments table (if they don't exist)
        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'currency')) {
                $table->string('currency', 3)->default('SAR')->after('amount');
            }
            if (!Schema::hasColumn('payments', 'currency_rate')) {
                $table->decimal('currency_rate', 6, 4)->default(1)->after('processing_fee');
            }
        });

        // Add currency field back to offers table (if it doesn't exist)
        if (!Schema::hasColumn('offers', 'currency')) {
            Schema::table('offers', function (Blueprint $table) {
                $table->string('currency', 3)->default('SAR')->after('price');
            });
        }

        // Add currency field back to contracts table (if it doesn't exist)
        if (!Schema::hasColumn('contracts', 'currency')) {
            Schema::table('contracts', function (Blueprint $table) {
                $table->string('currency', 3)->default('SAR')->after('total_amount');
            });
        }
    }
};