<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bank;
use App\Models\BankTranslation;

class BankSeeder extends Seeder
{
    public function run(): void
    {
        $locales = array_keys((array) config('locales.available'));

        $banks = [
            ['name' => 'البنك الأهلي السعودي'],
            ['name' => 'مصرف الراجحي'],
            ['name' => 'بنك الرياض'],
        ];

        $created = 0;

        foreach ($banks as $data) {
            $bank = Bank::create([
                'name' => $data['name'],
                'logo' => null,
            ]);

            foreach ($locales as $locale) {
                BankTranslation::create([
                    'bank_id' => $bank->id,
                    'locale' => $locale,
                    'name' => $data['name'],
                ]);
            }

            $created++;
        }

        $this->command?->info("Seeded {$created} banks.");
    }
}
