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
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->string('budget_name'); // اسم الميزانية
            $table->text('description')->nullable(); // وصف الميزانية
            $table->enum('budget_type', [
                'annual',        // سنوية
                'quarterly',     // ربع سنوية
                'monthly',       // شهرية
                'project',       // مشروع
                'department'     // قسم
            ]);
            $table->enum('status', [
                'draft',         // مسودة
                'approved',      // معتمدة
                'active',        // نشطة
                'completed',     // مكتملة
                'cancelled'      // ملغية
            ])->default('draft');
            $table->date('start_date'); // تاريخ البداية
            $table->date('end_date'); // تاريخ النهاية
            $table->decimal('total_budget', 15, 2)->default(0); // إجمالي الميزانية
            $table->decimal('allocated_amount', 15, 2)->default(0); // المبلغ المخصص
            $table->decimal('spent_amount', 15, 2)->default(0); // المبلغ المنفق
            $table->decimal('remaining_amount', 15, 2)->default(0); // المبلغ المتبقي
            $table->json('budget_items')->nullable(); // عناصر الميزانية
            $table->text('notes')->nullable(); // ملاحظات
            $table->unsignedBigInteger('facility_id'); // المنشأة
            $table->unsignedBigInteger('created_by'); // منشئ الميزانية
            $table->unsignedBigInteger('approved_by')->nullable(); // معتمد من
            $table->timestamp('approved_at')->nullable(); // تاريخ الاعتماد
            $table->timestamps();

            $table->foreign('facility_id')->references('id')->on('facilities')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
            
            $table->index(['facility_id', 'status']);
            $table->index(['facility_id', 'budget_type']);
            $table->index(['start_date', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budgets');
    }
};