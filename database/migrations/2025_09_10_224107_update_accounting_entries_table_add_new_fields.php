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
        Schema::table('accounting_entries', function (Blueprint $table) {
            // إضافة الحقول الجديدة
            $table->unsignedBigInteger('account_id')->nullable()->after('entry_type');
            $table->unsignedBigInteger('period_id')->nullable()->after('facility_id');
            $table->unsignedBigInteger('tax_rate_id')->nullable()->after('period_id');
            $table->decimal('tax_amount', 10, 2)->default(0)->after('tax_rate_id');
            $table->boolean('is_reversed')->default(false)->after('entry_date');
            $table->unsignedBigInteger('reversed_by')->nullable()->after('is_reversed');
            $table->timestamp('reversed_at')->nullable()->after('reversed_by');
            $table->text('reversal_reason')->nullable()->after('reversed_at');

            // إضافة المفاتيح الخارجية
            $table->foreign('account_id')->references('id')->on('chart_of_accounts')->onDelete('cascade');
            $table->foreign('period_id')->references('id')->on('accounting_periods')->onDelete('cascade');
            $table->foreign('tax_rate_id')->references('id')->on('tax_rates')->onDelete('set null');
            $table->foreign('reversed_by')->references('id')->on('users')->onDelete('set null');

            // إضافة الفهارس
            $table->index(['account_id']);
            $table->index(['period_id']);
            $table->index(['is_reversed']);
            $table->index(['facility_id', 'period_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accounting_entries', function (Blueprint $table) {
            // حذف المفاتيح الخارجية
            $table->dropForeign(['account_id']);
            $table->dropForeign(['period_id']);
            $table->dropForeign(['tax_rate_id']);
            $table->dropForeign(['reversed_by']);

            // حذف الفهارس
            $table->dropIndex(['account_id']);
            $table->dropIndex(['period_id']);
            $table->dropIndex(['is_reversed']);
            $table->dropIndex(['facility_id', 'period_id']);

            // حذف الأعمدة
            $table->dropColumn([
                'account_id',
                'period_id',
                'tax_rate_id',
                'tax_amount',
                'is_reversed',
                'reversed_by',
                'reversed_at',
                'reversal_reason'
            ]);
        });
    }
};