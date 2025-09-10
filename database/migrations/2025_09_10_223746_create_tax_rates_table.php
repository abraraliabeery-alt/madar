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
        Schema::create('tax_rates', function (Blueprint $table) {
            $table->id();
            $table->string('tax_name'); // اسم الضريبة
            $table->string('tax_code', 20)->unique(); // كود الضريبة
            $table->decimal('rate', 5, 4); // معدل الضريبة (مثال: 0.15 = 15%)
            $table->enum('tax_type', [
                'vat',           // ضريبة القيمة المضافة
                'income_tax',    // ضريبة الدخل
                'withholding',   // ضريبة الاستقطاع
                'stamp_tax',     // ضريبة الدمغة
                'other'          // أخرى
            ]);
            $table->enum('calculation_method', [
                'percentage',    // نسبة مئوية
                'fixed_amount'   // مبلغ ثابت
            ])->default('percentage');
            $table->decimal('fixed_amount', 10, 2)->nullable(); // المبلغ الثابت
            $table->boolean('is_inclusive')->default(false); // شاملة أم لا
            $table->boolean('is_active')->default(true); // نشط
            $table->date('effective_from'); // ساري من
            $table->date('effective_to')->nullable(); // ساري حتى
            $table->text('description')->nullable(); // وصف الضريبة
            $table->json('applicable_accounts')->nullable(); // الحسابات المطبقة عليها
            $table->unsignedBigInteger('facility_id'); // المنشأة
            $table->unsignedBigInteger('created_by'); // منشئ الضريبة
            $table->timestamps();

            $table->foreign('facility_id')->references('id')->on('facilities')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            
            $table->index(['facility_id', 'is_active']);
            $table->index(['tax_type', 'is_active']);
            $table->index(['effective_from', 'effective_to']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax_rates');
    }
};