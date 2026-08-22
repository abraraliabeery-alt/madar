<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_requests', function (Blueprint $table) {
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete()->after('status');
            $table->text('admin_notes')->nullable()->after('assigned_to');
            $table->string('priority', 20)->default('normal')->after('admin_notes');
            $table->timestamp('contacted_at')->nullable()->after('priority');
            $table->timestamp('closed_at')->nullable()->after('contacted_at');
            $table->string('source', 50)->default('website')->after('closed_at');
        });

        Schema::table('marketing_product_requests', function (Blueprint $table) {
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete()->after('status');
            $table->text('admin_notes')->nullable()->after('assigned_to');
            $table->string('priority', 20)->default('normal')->after('admin_notes');
            $table->timestamp('contacted_at')->nullable()->after('priority');
            $table->timestamp('closed_at')->nullable()->after('contacted_at');
            $table->string('source', 50)->default('website')->after('closed_at');
        });
    }

    public function down(): void
    {
        Schema::table('product_requests', function (Blueprint $table) {
            $table->dropForeign(['assigned_to']);
            $table->dropColumn(['assigned_to', 'admin_notes', 'priority', 'contacted_at', 'closed_at', 'source']);
        });

        Schema::table('marketing_product_requests', function (Blueprint $table) {
            $table->dropForeign(['assigned_to']);
            $table->dropColumn(['assigned_to', 'admin_notes', 'priority', 'contacted_at', 'closed_at', 'source']);
        });
    }
};
