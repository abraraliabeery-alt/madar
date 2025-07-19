<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'admin',
                'display_name' => 'مدير النظام',
                'description' => 'مدير النظام مع جميع الصلاحيات',
                'is_active' => true,
            ],
            [
                'name' => 'facility',
                'display_name' => 'منشأة',
                'description' => 'مالك أو مدير منشأة',
                'is_active' => true,
            ],
            [
                'name' => 'client',
                'display_name' => 'عميل',
                'description' => 'عميل عادي',
                'is_active' => true,
            ],
            [
                'name' => 'moderator',
                'display_name' => 'مشرف',
                'description' => 'مشرف للمحتوى',
                'is_active' => true,
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['name' => $role['name']],
                $role
            );
        }
    }
}
