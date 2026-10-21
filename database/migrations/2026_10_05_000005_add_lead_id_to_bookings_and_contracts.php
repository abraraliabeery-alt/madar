<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'lead_id')) {
                $table->foreignId('lead_id')->nullable()->after('facility_id')->constrained('leads')->nullOnDelete();
            }
        });

        Schema::table('contracts', function (Blueprint $table) {
            if (!Schema::hasColumn('contracts', 'lead_id')) {
                $table->foreignId('lead_id')->nullable()->after('tenant_id')->constrained('leads')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('lead_id');
        });
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropConstrainedForeignId('lead_id');
        });
    }
};
