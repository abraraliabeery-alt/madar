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
        Schema::create('accounting_entries', function (Blueprint $table) {
            $table->id();
            $table->enum('entry_type', ['debit', 'credit']);
            $table->enum('account_type', ['revenue', 'receivable', 'commission', 'liability', 'expense']);
            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('SAR');
            $table->text('description');
            $table->string('reference_type')->nullable(); // 'invoice', 'payment', 'contract', 'commission'
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->unsignedBigInteger('contract_id');
            $table->unsignedBigInteger('facility_id')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->date('entry_date');
            $table->timestamps();

            $table->foreign('contract_id')->references('id')->on('contracts')->onDelete('cascade');
            $table->foreign('facility_id')->references('id')->on('facilities')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            
            $table->index(['entry_type', 'account_type']);
            $table->index(['reference_type', 'reference_id']);
            $table->index('entry_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounting_entries');
    }
};
