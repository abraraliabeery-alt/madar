<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loan_offers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('loan_request_id');
            $table->unsignedBigInteger('bank_user_id'); // موظف البنك مقدم العرض
            $table->decimal('amount', 15, 2)->nullable();
            $table->decimal('profit_rate', 5, 2)->nullable(); // نسبة الربح %
            $table->integer('term_months')->nullable();
            $table->decimal('monthly_payment', 15, 2)->nullable();
            $table->decimal('fees', 15, 2)->nullable();
            $table->string('status')->default('pending'); // pending, submitted, updated, withdrawn, rejected
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('loan_request_id')->references('id')->on('loan_requests')->onDelete('cascade');
            $table->foreign('bank_user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // إضافة مفتاح أجنبي لاختيار العرض في طلب القرض بعد إنشاء جدول العروض
        Schema::table('loan_requests', function (Blueprint $table) {
            if (Schema::hasColumn('loan_requests', 'chosen_offer_id')) {
                $table->foreign('chosen_offer_id')->references('id')->on('loan_offers')->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('loan_requests', function (Blueprint $table) {
            if (Schema::hasColumn('loan_requests', 'chosen_offer_id')) {
                $table->dropForeign(['chosen_offer_id']);
            }
        });
        Schema::dropIfExists('loan_offers');
    }
};
