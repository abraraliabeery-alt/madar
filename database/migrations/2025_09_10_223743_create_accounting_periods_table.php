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
        Schema::create('accounting_periods', function (Blueprint $table) {
            $table->id();
            $table->string('period_name'); // اسم الفترة
            $table->date('start_date'); // تاريخ البداية
            $table->date('end_date'); // تاريخ النهاية
            $table->enum('period_type', [
                'monthly',    // شهري
                'quarterly',  // ربع سنوي
                'yearly',     // سنوي
                'custom'      // مخصص
            ]);
            $table->enum('status', [
                'open',       // مفتوح
                'closed',     // مغلق
                'locked'      // مقفل
            ])->default('open');
            $table->boolean('is_current')->default(false); // الفترة الحالية
            $table->text('notes')->nullable(); // ملاحظات
            $table->unsignedBigInteger('facility_id'); // المنشأة
            $table->unsignedBigInteger('created_by'); // منشئ الفترة
            $table->timestamps();

            $table->foreign('facility_id')->references('id')->on('facilities')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            
            $table->index(['facility_id', 'status']);
            $table->index(['facility_id', 'is_current']);
            $table->index(['start_date', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounting_periods');
    }
};