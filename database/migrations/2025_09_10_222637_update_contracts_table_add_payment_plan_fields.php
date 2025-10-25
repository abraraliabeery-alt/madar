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
        Schema::table('contracts', function (Blueprint $table) {
            $table->json('payment_plan')->nullable()->after('terms_conditions');
            $table->enum('payment_frequency', ['monthly', 'quarterly', 'yearly', 'custom'])->nullable()->after('payment_plan');
            $table->integer('total_installments')->default(1)->after('payment_frequency');
            $table->integer('paid_installments')->default(0)->after('total_installments');
            $table->date('next_payment_date')->nullable()->after('paid_installments');
            $table->decimal('late_fee_rate', 5, 4)->default(0)->after('next_payment_date');
            $table->decimal('late_fee_amount', 10, 2)->default(0)->after('late_fee_rate');
            $table->decimal('early_payment_discount', 10, 2)->default(0)->after('late_fee_amount');
            $table->integer('contract_duration_months')->nullable()->after('early_payment_discount');
            $table->text('renewal_terms')->nullable()->after('contract_duration_months');
            $table->text('termination_terms')->nullable()->after('renewal_terms');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropColumn([
                'payment_plan',
                'payment_frequency',
                'total_installments',
                'paid_installments',
                'next_payment_date',
                'late_fee_rate',
                'late_fee_amount',
                'early_payment_discount',
                'contract_duration_months',
                'renewal_terms',
                'termination_terms'
            ]);
        });
    }
};
