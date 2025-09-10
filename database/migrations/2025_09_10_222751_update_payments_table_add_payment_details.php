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
        Schema::table('payments', function (Blueprint $table) {
            $table->string('payment_reference')->nullable()->after('created_by');
            $table->string('bank_reference')->nullable()->after('payment_reference');
            $table->string('transaction_id')->nullable()->after('bank_reference');
            $table->decimal('processing_fee', 10, 2)->default(0)->after('transaction_id');
            $table->decimal('currency_rate', 6, 4)->default(1)->after('processing_fee');
            $table->string('payment_gateway')->nullable()->after('currency_rate');
            $table->json('gateway_response')->nullable()->after('payment_gateway');
            $table->integer('installment_number')->nullable()->after('gateway_response');
            $table->decimal('late_fee_paid', 10, 2)->default(0)->after('installment_number');
            $table->decimal('discount_applied', 10, 2)->default(0)->after('late_fee_paid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn([
                'payment_reference',
                'bank_reference',
                'transaction_id',
                'processing_fee',
                'currency_rate',
                'payment_gateway',
                'gateway_response',
                'installment_number',
                'late_fee_paid',
                'discount_applied'
            ]);
        });
    }
};
