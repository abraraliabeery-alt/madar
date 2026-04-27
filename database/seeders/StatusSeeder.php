<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Status;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            [
                'name' => 'available',
                'display_name' => 'متاح',
                'color' => 'success',
                'icon' => 'fas fa-check-circle',
                'is_active' => true,
                'order' => 1,
            ],
            [
                'name' => 'rented',
                'display_name' => 'قيد التنفيذ',
                'color' => 'warning',
                'icon' => 'fas fa-person-digging',
                'is_active' => true,
                'order' => 2,
            ],
            [
                'name' => 'sold',
                'display_name' => 'مكتمل',
                'color' => 'danger',
                'icon' => 'fas fa-flag-checkered',
                'is_active' => true,
                'order' => 3,
            ],
            [
                'name' => 'under_maintenance',
                'display_name' => 'موقوف مؤقتاً',
                'color' => 'info',
                'icon' => 'fas fa-tools',
                'is_active' => true,
                'order' => 4,
            ],
            [
                'name' => 'reserved',
                'display_name' => 'قيد المراجعة',
                'color' => 'primary',
                'icon' => 'fas fa-hourglass-half',
                'is_active' => true,
                'order' => 5,
            ],
        ];

        foreach ($statuses as $status) {
            Status::updateOrCreate(
                ['name' => $status['name']],
                $status
            );
        }
    }
}
