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
        Schema::table('offers', function (Blueprint $table) {
            $table->string('offer_title')->nullable()->after('created_by');
            $table->text('offer_description')->nullable()->after('offer_title');
            $table->json('payment_plan')->nullable()->after('offer_description');
            $table->text('special_conditions')->nullable()->after('payment_plan');
            $table->text('marketing_notes')->nullable()->after('special_conditions');
            $table->integer('priority')->default(5)->after('marketing_notes');
            $table->boolean('auto_renew')->default(false)->after('priority');
            $table->integer('min_contract_duration')->nullable()->after('auto_renew');
            $table->integer('max_contract_duration')->nullable()->after('min_contract_duration');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->dropColumn([
                'offer_title',
                'offer_description',
                'payment_plan',
                'special_conditions',
                'marketing_notes',
                'priority',
                'auto_renew',
                'min_contract_duration',
                'max_contract_duration'
            ]);
        });
    }
};
