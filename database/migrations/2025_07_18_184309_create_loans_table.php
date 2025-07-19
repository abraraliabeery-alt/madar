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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->string('applicant')->nullable();
            $table->string('manager')->nullable();
            $table->string('bank_emp')->nullable();
            $table->unsignedBigInteger('bank_id')->nullable();
            $table->unsignedBigInteger('facility_id')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->date('birth')->nullable();
            $table->decimal('salary', 12, 2)->nullable();
            $table->decimal('commitments', 12, 2)->nullable();
            $table->string('military')->nullable();
            $table->string('rank')->nullable();
            $table->string('employment')->nullable();
            $table->boolean('supported')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
