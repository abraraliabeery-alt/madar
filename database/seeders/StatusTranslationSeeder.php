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
                'ar' => 'قيد التنفيذ',
                'en' => 'In Progress'
            ],
            'sold' => [
                'ar' => 'مكتمل',
                'en' => 'Completed'
            ],
            'under_maintenance' => [
                'ar' => 'موقوف مؤقتاً',
                'en' => 'On Hold'
            ],
            'reserved' => [
                'ar' => 'قيد المراجعة',
                'en' => 'Under Review'
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
