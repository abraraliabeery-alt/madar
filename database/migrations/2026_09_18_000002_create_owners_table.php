<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('owners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('facility_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('national_id')->nullable();
            $table->boolean('is_company')->default(false);
            $table->string('contact_person')->nullable();
            $table->text('address')->nullable();
            $table->decimal('commission_rate', 5, 2)->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['facility_id', 'is_active']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('owner_id')->nullable()->after('parent_id')->constrained('owners')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropConstrainedForeignId('owner_id');
        });
        Schema::dropIfExists('owners');
    }
};
