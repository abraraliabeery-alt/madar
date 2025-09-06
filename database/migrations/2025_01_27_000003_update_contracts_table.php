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
            $table->unsignedBigInteger('offer_id')->nullable()->after('product_id');
            $table->enum('contract_type', ['sale', 'rent'])->after('offer_id');
            $table->string('contract_number')->unique()->nullable()->after('contract_type');
            $table->decimal('total_amount', 12, 2)->nullable()->after('end_date');
            $table->string('currency', 3)->default('SAR')->after('total_amount');
            $table->decimal('deposit_amount', 12, 2)->nullable()->after('currency');
            $table->decimal('commission_rate', 5, 4)->nullable()->after('deposit_amount');
            $table->decimal('commission_amount', 12, 2)->nullable()->after('commission_rate');
            $table->enum('status', ['draft', 'active', 'completed', 'cancelled'])->default('draft')->after('commission_amount');
            $table->text('terms_conditions')->nullable()->after('status');
            $table->unsignedBigInteger('created_by')->nullable()->after('facility_id');

            $table->foreign('offer_id')->references('id')->on('offers')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropForeign(['offer_id']);
            $table->dropForeign(['created_by']);
            $table->dropColumn([
                'offer_id',
                'contract_type',
                'contract_number',
                'total_amount',
                'currency',
                'deposit_amount',
                'commission_rate',
                'commission_amount',
                'status',
                'terms_conditions',
                'created_by'
            ]);
        });
    }
};
