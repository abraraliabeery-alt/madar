<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commission_parties', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contract_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('name')->nullable();
            $table->string('role')->nullable(); // referrer, marketer, buyer_agent, seller_agent, other
            $table->string('commission_type')->default('percentage'); // percentage or fixed
            $table->decimal('commission_value', 10, 4)->default(0); // نسبة (0-1) أو مبلغ
            $table->decimal('calculated_amount', 12, 2)->nullable();
            $table->string('status')->default('pending'); // pending, paid, cancelled
            $table->timestamps();

            $table->foreign('contract_id')->references('id')->on('contracts')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commission_parties');
    }
};
