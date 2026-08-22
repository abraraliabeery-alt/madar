<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // listing_type and rent_period are now handled by offers/attributes
    }

    public function down(): void
    {
        // columns no longer created
    }
};
