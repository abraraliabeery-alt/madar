<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'facility_id')) {
                $table->foreignId('facility_id')->nullable()->after('product_id')->constrained()->nullOnDelete();
            }
            if (!Schema::hasColumn('bookings', 'name')) {
                $table->string('name')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('bookings', 'email')) {
                $table->string('email')->nullable();
            }
            if (!Schema::hasColumn('bookings', 'phone')) {
                $table->string('phone')->nullable();
            }
            if (!Schema::hasColumn('bookings', 'preferred_date')) {
                $table->dateTime('preferred_date')->nullable();
            }
            if (!Schema::hasColumn('bookings', 'preferred_time')) {
                $table->string('preferred_time')->nullable();
            }
            if (!Schema::hasColumn('bookings', 'message')) {
                $table->text('message')->nullable();
            }
            if (!Schema::hasColumn('bookings', 'visit_type')) {
                $table->string('visit_type')->nullable();
            }
            if (!Schema::hasColumn('bookings', 'reschedule_reason')) {
                $table->text('reschedule_reason')->nullable();
            }
            if (!Schema::hasColumn('bookings', 'cancellation_reason')) {
                $table->text('cancellation_reason')->nullable();
            }
            if (!Schema::hasColumn('bookings', 'rescheduled_at')) {
                $table->timestamp('rescheduled_at')->nullable();
            }
            if (!Schema::hasColumn('bookings', 'cancelled_at')) {
                $table->timestamp('cancelled_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('facility_id');
            $table->dropColumn([
                'name', 'email', 'phone', 'preferred_date', 'preferred_time',
                'message', 'visit_type', 'reschedule_reason', 'cancellation_reason',
                'rescheduled_at', 'cancelled_at',
            ]);
        });
    }
};
