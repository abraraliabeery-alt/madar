<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('facility_id');
            $table->string('name');
            $table->unsignedBigInteger('manager_user_id')->nullable();
            $table->timestamps();

            $table->foreign('facility_id')->references('id')->on('facilities')->onDelete('cascade');
            $table->foreign('manager_user_id')->references('id')->on('users')->nullOnDelete();
            $table->index(['facility_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};
