<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\RoleTranslation;

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
                'name' => 'bank_employee',
                'display_name' => 'مسوّق بنك',
                'description' => 'مسوّق تابع لبنك محدد',
                'is_active' => true,
            ],
            [
                'name' => 'moderator',
                'display_name' => 'مشرف',
                'description' => 'مشرف للمحتوى',
                'is_active' => true,
            ],
        ];

        foreach ($roles as $roleData) {
            // Extract display_name and description for translation
            $displayName = $roleData['display_name'];
            $description = $roleData['description'];
            unset($roleData['display_name']); // Remove from main role data
            unset($roleData['description']); // Remove from main role data
            
            $role = Role::updateOrCreate(
                ['name' => $roleData['name']],
                $roleData
            );

            // Create translation for Arabic
            RoleTranslation::updateOrCreate(
                [
                    'role_id' => $role->id,
                    'locale' => 'ar'
                ],
                [
                    'display_name' => $displayName,
                    'description' => $description,
                ]
            );

            // Create translation for English
            RoleTranslation::updateOrCreate(
                [
                    'role_id' => $role->id,
                    'locale' => 'en'
                ],
                [
                    'display_name' => $displayName,
                    'description' => $description,
                ]
            );
        }
    }
}
