<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loan_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // طالب التمويل (العميل)
            $table->unsignedBigInteger('product_id')->nullable(); // العقار المرتبط (اختياري)
            $table->string('status')->default('new'); // new, dispatched, competing, offers_received, selected, advising, completed, cancelled
            $table->unsignedBigInteger('assigned_advisor_id')->nullable();
            $table->unsignedBigInteger('chosen_offer_id')->nullable();
            $table->timestamp('sla_due_at')->nullable(); // مهلة الرد الأولي
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('set null');
            $table->foreign('assigned_advisor_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loan_requests');
    }
};
