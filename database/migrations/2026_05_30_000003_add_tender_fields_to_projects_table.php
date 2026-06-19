<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('status')->default('draft')->after('attachments');
            $table->date('bid_deadline')->nullable()->after('status');
            $table->date('qa_deadline')->nullable()->after('bid_deadline');
            $table->date('site_visit_date')->nullable()->after('qa_deadline');
            $table->foreignId('awarded_execution_bid_id')->nullable()->after('site_visit_date')
                ->constrained('execution_bids')->nullOnDelete();

            $table->index(['status', 'bid_deadline']);
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropIndex(['status', 'bid_deadline']);

            $table->dropForeign(['awarded_execution_bid_id']);
            $table->dropColumn([
                'status',
                'bid_deadline',
                'qa_deadline',
                'site_visit_date',
                'awarded_execution_bid_id',
            ]);
        });
    }
};
