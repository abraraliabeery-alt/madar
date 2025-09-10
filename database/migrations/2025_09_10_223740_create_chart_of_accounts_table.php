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
        Schema::create('chart_of_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('account_code', 20)->unique(); // كود الحساب
            $table->string('account_name_ar'); // اسم الحساب بالعربية
            $table->string('account_name_en'); // اسم الحساب بالإنجليزية
            $table->enum('account_type', [
                'asset',           // الأصول
                'liability',       // الخصوم
                'equity',          // حقوق الملكية
                'revenue',         // الإيرادات
                'expense',         // المصروفات
                'cost_of_sales'    // تكلفة المبيعات
            ]);
            $table->enum('account_category', [
                'current_asset',      // الأصول المتداولة
                'fixed_asset',        // الأصول الثابتة
                'current_liability',  // الخصوم المتداولة
                'long_term_liability', // الخصوم طويلة الأجل
                'equity',             // حقوق الملكية
                'operating_revenue',   // إيرادات التشغيل
                'other_revenue',       // إيرادات أخرى
                'operating_expense',   // مصروفات التشغيل
                'administrative_expense', // مصروفات إدارية
                'financial_expense',   // مصروفات مالية
                'cost_of_sales'        // تكلفة المبيعات
            ]);
            $table->enum('normal_balance', ['debit', 'credit']); // الرصيد الطبيعي
            $table->unsignedBigInteger('parent_account_id')->nullable(); // الحساب الأب
            $table->integer('level')->default(1); // مستوى الحساب في التسلسل الهرمي
            $table->boolean('is_active')->default(true); // نشط
            $table->boolean('is_system')->default(false); // حساب نظام (لا يمكن حذفه)
            $table->text('description')->nullable(); // وصف الحساب
            $table->string('currency', 3)->default('SAR'); // العملة
            $table->decimal('opening_balance', 15, 2)->default(0); // الرصيد الافتتاحي
            $table->decimal('current_balance', 15, 2)->default(0); // الرصيد الحالي
            $table->unsignedBigInteger('facility_id'); // المنشأة
            $table->unsignedBigInteger('created_by'); // منشئ الحساب
            $table->timestamps();

            $table->foreign('parent_account_id')->references('id')->on('chart_of_accounts')->onDelete('cascade');
            $table->foreign('facility_id')->references('id')->on('facilities')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            
            $table->index(['facility_id', 'account_type']);
            $table->index(['facility_id', 'account_category']);
            $table->index(['parent_account_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chart_of_accounts');
    }
};