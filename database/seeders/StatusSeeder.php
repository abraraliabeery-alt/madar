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
                'display_name' => 'مؤجر',
                'color' => 'warning',
                'icon' => 'fas fa-key',
                'is_active' => true,
                'order' => 2,
            ],
            [
                'name' => 'sold',
                'display_name' => 'مباع',
                'color' => 'danger',
                'icon' => 'fas fa-times-circle',
                'is_active' => true,
                'order' => 3,
            ],
            [
                'name' => 'under_maintenance',
                'display_name' => 'تحت الصيانة',
                'color' => 'info',
                'icon' => 'fas fa-tools',
                'is_active' => true,
                'order' => 4,
            ],
            [
                'name' => 'reserved',
                'display_name' => 'محجوز',
                'color' => 'primary',
                'icon' => 'fas fa-clock',
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
