<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('zatca_uuid')->nullable()->after('gateway_invoice_id');
            $table->text('zatca_qr')->nullable()->after('zatca_uuid');
            $table->string('zatca_status')->nullable()->after('zatca_qr'); // submitted, failed
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['zatca_uuid', 'zatca_qr', 'zatca_status']);
        });
    }
};
