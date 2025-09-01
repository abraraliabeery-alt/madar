<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Status;
use App\Models\StatusTranslation;

class StatusTranslationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = Status::all();
        
        $translations = [
            'available' => [
                'ar' => 'متاح',
                'en' => 'Available'
            ],
            'rented' => [
                'ar' => 'مؤجر',
                'en' => 'Rented'
            ],
            'sold' => [
                'ar' => 'مباع',
                'en' => 'Sold'
            ],
            'under_maintenance' => [
                'ar' => 'تحت الصيانة',
                'en' => 'Under Maintenance'
            ],
            'reserved' => [
                'ar' => 'محجوز',
                'en' => 'Reserved'
            ]
        ];

        foreach ($statuses as $status) {
            if (isset($translations[$status->name])) {
                foreach ($translations[$status->name] as $locale => $name) {
                    StatusTranslation::updateOrCreate(
                        [
                            'status_id' => $status->id,
                            'locale' => $locale
                        ],
                        [
                            'name' => $name
                        ]
                    );
                }
            }
        }
    }
}
