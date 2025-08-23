<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create city_translations table
        Schema::create('city_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('city_id');
            $table->string('locale');
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
            $table->unique(['city_id', 'locale']);
        });

        // Migrate existing data from cities table
        $cities = DB::table('cities')->get();
        
        foreach ($cities as $city) {
            // Create Arabic translation (default)
            DB::table('city_translations')->insert([
                'city_id' => $city->id,
                'locale' => 'ar',
                'name' => $city->name,
                'description' => $city->description,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            // Create English translation if name_en exists
            if (!empty($city->name_en)) {
                DB::table('city_translations')->insert([
                    'city_id' => $city->id,
                    'locale' => 'en',
                    'name' => $city->name_en,
                    'description' => $city->description, // You might want to add description_en field if needed
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Remove name_en from cities table if it exists
        if (Schema::hasColumn('cities', 'name_en')) {
            Schema::table('cities', function (Blueprint $table) {
                $table->dropColumn('name_en');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add name_en back to cities table
        Schema::table('cities', function (Blueprint $table) {
            $table->string('name_en')->nullable()->after('name');
        });

        // Migrate data back from translations table
        $translations = DB::table('city_translations')->where('locale', 'en')->get();
        
        foreach ($translations as $translation) {
            DB::table('cities')
                ->where('id', $translation->city_id)
                ->update(['name_en' => $translation->name]);
        }

        // Drop city_translations table
        Schema::dropIfExists('city_translations');
    }
};
