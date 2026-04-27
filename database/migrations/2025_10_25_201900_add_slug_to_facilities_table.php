<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Models\Facility;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('facilities', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('name');
            $table->index('slug');
        });

        // Backfill existing rows with unique slugs based on name
        Facility::query()->orderBy('id')->chunk(100, function ($facilities) {
            foreach ($facilities as $facility) {
                if (!$facility->slug) {
                    $base = Str::slug($facility->name ?: ('facility-'.$facility->id));
                    $slug = $base ?: ('facility-'.$facility->id);
                    $i = 1;
                    while (Facility::where('slug', $slug)->where('id', '!=', $facility->id)->exists()) {
                        $slug = $base.'-'.($i++);
                    }
                    $facility->slug = $slug;
                    $facility->saveQuietly();
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('facilities', function (Blueprint $table) {
            if (Schema::hasColumn('facilities', 'slug')) {
                $table->dropIndex(['slug']);
                $table->dropUnique(['slug']);
                $table->dropColumn('slug');
            }
        });
    }
};
