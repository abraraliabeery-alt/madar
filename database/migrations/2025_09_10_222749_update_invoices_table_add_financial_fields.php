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
        Schema::table('invoices', function (Blueprint $table) {
            $table->integer('installment_number')->nullable()->after('created_by');
            $table->decimal('installment_amount', 10, 2)->nullable()->after('installment_number');
            $table->decimal('late_fee_amount', 10, 2)->default(0)->after('installment_amount');
            $table->decimal('discount_amount', 10, 2)->default(0)->after('late_fee_amount');
            $table->decimal('tax_rate', 5, 4)->default(0)->after('discount_amount');
            $table->decimal('tax_amount', 10, 2)->default(0)->after('tax_rate');
            $table->decimal('net_amount', 10, 2)->nullable()->after('tax_amount');
            $table->integer('payment_terms_days')->default(30)->after('net_amount');
            $table->boolean('reminder_sent')->default(false)->after('payment_terms_days');
            $table->integer('reminder_count')->default(0)->after('reminder_sent');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn([
                'installment_number',
                'installment_amount',
                'late_fee_amount',
                'discount_amount',
                'tax_rate',
                'tax_amount',
                'net_amount',
                'payment_terms_days',
                'reminder_sent',
                'reminder_count'
            ]);
        });
    }
};
