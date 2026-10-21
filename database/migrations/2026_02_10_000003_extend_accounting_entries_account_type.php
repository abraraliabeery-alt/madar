<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE accounting_entries MODIFY account_type ENUM('revenue','receivable','commission','liability','expense','asset','cash','equity') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE accounting_entries MODIFY account_type ENUM('revenue','receivable','commission','liability','expense') NOT NULL");
    }
};
