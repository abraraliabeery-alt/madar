<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->foreignId('city_id')->nullable()->after('client_user_id')->constrained('cities')->nullOnDelete();
            $table->foreignId('neighborhood_id')->nullable()->after('city_id')->constrained('neighborhoods')->nullOnDelete();
            $table->foreignId('street_id')->nullable()->after('neighborhood_id')->constrained('streets')->nullOnDelete();

            $table->string('address')->nullable()->after('street_id');

            $table->string('request_type')->nullable()->after('address');
            $table->string('scope_of_work')->nullable()->after('request_type');
            $table->string('finishing_level')->nullable()->after('scope_of_work');

            $table->decimal('land_area', 10, 2)->nullable()->after('finishing_level');
            $table->decimal('built_area', 10, 2)->nullable()->after('land_area');

            $table->unsignedInteger('floors_count')->nullable()->after('built_area');
            $table->unsignedInteger('rooms_count')->nullable()->after('floors_count');
            $table->unsignedInteger('bathrooms_count')->nullable()->after('rooms_count');

            $table->decimal('budget_min', 12, 2)->nullable()->after('bathrooms_count');
            $table->decimal('budget_max', 12, 2)->nullable()->after('budget_min');

            $table->date('start_date')->nullable()->after('budget_max');
            $table->unsignedInteger('duration_days')->nullable()->after('start_date');

            $table->text('requirements')->nullable()->after('duration_days');
            $table->json('attachments')->nullable()->after('requirements');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropConstrainedForeignId('street_id');
            $table->dropConstrainedForeignId('neighborhood_id');
            $table->dropConstrainedForeignId('city_id');

            $table->dropColumn([
                'address',
                'request_type',
                'scope_of_work',
                'finishing_level',
                'land_area',
                'built_area',
                'floors_count',
                'rooms_count',
                'bathrooms_count',
                'budget_min',
                'budget_max',
                'start_date',
                'duration_days',
                'requirements',
                'attachments',
            ]);
        });
    }
};
