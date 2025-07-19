<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // User Management
            ['name' => 'users.view', 'display_name' => 'عرض المستخدمين'],
            ['name' => 'users.create', 'display_name' => 'إنشاء مستخدم'],
            ['name' => 'users.edit', 'display_name' => 'تعديل مستخدم'],
            ['name' => 'users.delete', 'display_name' => 'حذف مستخدم'],

            // Facility Management
            ['name' => 'facilities.view', 'display_name' => 'عرض المنشآت'],
            ['name' => 'facilities.create', 'display_name' => 'إنشاء منشأة'],
            ['name' => 'facilities.edit', 'display_name' => 'تعديل منشأة'],
            ['name' => 'facilities.delete', 'display_name' => 'حذف منشأة'],

            // Product Management
            ['name' => 'products.view', 'display_name' => 'عرض المنتجات'],
            ['name' => 'products.create', 'display_name' => 'إنشاء منتج'],
            ['name' => 'products.edit', 'display_name' => 'تعديل منتج'],
            ['name' => 'products.delete', 'display_name' => 'حذف منتج'],

            // Booking Management
            ['name' => 'bookings.view', 'display_name' => 'عرض الحجوزات'],
            ['name' => 'bookings.create', 'display_name' => 'إنشاء حجز'],
            ['name' => 'bookings.edit', 'display_name' => 'تعديل حجز'],
            ['name' => 'bookings.delete', 'display_name' => 'حذف حجز'],

            // Contract Management
            ['name' => 'contracts.view', 'display_name' => 'عرض العقود'],
            ['name' => 'contracts.create', 'display_name' => 'إنشاء عقد'],
            ['name' => 'contracts.edit', 'display_name' => 'تعديل عقد'],
            ['name' => 'contracts.delete', 'display_name' => 'حذف عقد'],

            // Category Management
            ['name' => 'categories.view', 'display_name' => 'عرض الفئات'],
            ['name' => 'categories.create', 'display_name' => 'إنشاء فئة'],
            ['name' => 'categories.edit', 'display_name' => 'تعديل فئة'],
            ['name' => 'categories.delete', 'display_name' => 'حذف فئة'],

            // Feature Management
            ['name' => 'features.view', 'display_name' => 'عرض المميزات'],
            ['name' => 'features.create', 'display_name' => 'إنشاء مميزة'],
            ['name' => 'features.edit', 'display_name' => 'تعديل مميزة'],
            ['name' => 'features.delete', 'display_name' => 'حذف مميزة'],

            // Role Management
            ['name' => 'roles.view', 'display_name' => 'عرض الأدوار'],
            ['name' => 'roles.create', 'display_name' => 'إنشاء دور'],
            ['name' => 'roles.edit', 'display_name' => 'تعديل دور'],
            ['name' => 'roles.delete', 'display_name' => 'حذف دور'],

            // System Settings
            ['name' => 'settings.view', 'display_name' => 'عرض الإعدادات'],
            ['name' => 'settings.edit', 'display_name' => 'تعديل الإعدادات'],

            // Reports
            ['name' => 'reports.view', 'display_name' => 'عرض التقارير'],
            ['name' => 'reports.export', 'display_name' => 'تصدير التقارير'],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }
    }
}
