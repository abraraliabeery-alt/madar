<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use App\Models\Package;
use App\Models\PackageTranslation;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        $locales = array_keys((array) config('locales.available'));

        $canSeedTranslations = Schema::hasTable('package_translations')
            && Schema::hasColumn('package_translations', 'package_id')
            && Schema::hasColumn('package_translations', 'locale')
            && Schema::hasColumn('package_translations', 'name');

        $packages = [
            'مجاني',
            'أساسي',
            'محترف',
        ];

        $created = 0;

        foreach ($packages as $name) {
            $package = Package::create();

            if ($canSeedTranslations) {
                foreach ($locales as $locale) {
                    PackageTranslation::create([
                        'package_id' => $package->id,
                        'locale' => $locale,
                        'name' => $name,
                    ]);
                }
            }

            $created++;
        }

        $this->command?->info("Seeded {$created} packages." . ($canSeedTranslations ? '' : ' (translations skipped)'));
    }
}
