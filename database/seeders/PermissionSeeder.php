<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\PermissionTranslation;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // User Management
            [
                'name' => 'users.view',
                'translations' => [
                    'ar' => ['name' => 'users.view', 'display_name' => 'عرض المستخدمين'],
                    'en' => ['name' => 'users.view', 'display_name' => 'View Users'],
                ]
            ],
            [
                'name' => 'users.create',
                'translations' => [
                    'ar' => ['name' => 'users.create', 'display_name' => 'إنشاء مستخدم'],
                    'en' => ['name' => 'users.create', 'display_name' => 'Create User'],
                ]
            ],
            [
                'name' => 'users.edit',
                'translations' => [
                    'ar' => ['name' => 'users.edit', 'display_name' => 'تعديل مستخدم'],
                    'en' => ['name' => 'users.edit', 'display_name' => 'Edit User'],
                ]
            ],
            [
                'name' => 'users.delete',
                'translations' => [
                    'ar' => ['name' => 'users.delete', 'display_name' => 'حذف مستخدم'],
                    'en' => ['name' => 'users.delete', 'display_name' => 'Delete User'],
                ]
            ],

            // Facility Management
            [
                'name' => 'facilities.view',
                'translations' => [
                    'ar' => ['name' => 'facilities.view', 'display_name' => 'عرض المنشآت'],
                    'en' => ['name' => 'facilities.view', 'display_name' => 'View Facilities'],
                ]
            ],
            [
                'name' => 'facilities.create',
                'translations' => [
                    'ar' => ['name' => 'facilities.create', 'display_name' => 'إنشاء منشأة'],
                    'en' => ['name' => 'facilities.create', 'display_name' => 'Create Facility'],
                ]
            ],
            [
                'name' => 'facilities.edit',
                'translations' => [
                    'ar' => ['name' => 'facilities.edit', 'display_name' => 'تعديل منشأة'],
                    'en' => ['name' => 'facilities.edit', 'display_name' => 'Edit Facility'],
                ]
            ],
            [
                'name' => 'facilities.delete',
                'translations' => [
                    'ar' => ['name' => 'facilities.delete', 'display_name' => 'حذف منشأة'],
                    'en' => ['name' => 'facilities.delete', 'display_name' => 'Delete Facility'],
                ]
            ],

            // Product Management
            [
                'name' => 'products.view',
                'translations' => [
                    'ar' => ['name' => 'products.view', 'display_name' => 'عرض المنتجات'],
                    'en' => ['name' => 'products.view', 'display_name' => 'View Products'],
                ]
            ],
            [
                'name' => 'products.create',
                'translations' => [
                    'ar' => ['name' => 'products.create', 'display_name' => 'إنشاء منتج'],
                    'en' => ['name' => 'products.create', 'display_name' => 'Create Product'],
                ]
            ],
            [
                'name' => 'products.edit',
                'translations' => [
                    'ar' => ['name' => 'products.edit', 'display_name' => 'تعديل منتج'],
                    'en' => ['name' => 'products.edit', 'display_name' => 'Edit Product'],
                ]
            ],
            [
                'name' => 'products.delete',
                'translations' => [
                    'ar' => ['name' => 'products.delete', 'display_name' => 'حذف منتج'],
                    'en' => ['name' => 'products.delete', 'display_name' => 'Delete Product'],
                ]
            ],

            // Booking Management
            [
                'name' => 'bookings.view',
                'translations' => [
                    'ar' => ['name' => 'bookings.view', 'display_name' => 'عرض الحجوزات'],
                    'en' => ['name' => 'bookings.view', 'display_name' => 'View Bookings'],
                ]
            ],
            [
                'name' => 'bookings.create',
                'translations' => [
                    'ar' => ['name' => 'bookings.create', 'display_name' => 'إنشاء حجز'],
                    'en' => ['name' => 'bookings.create', 'display_name' => 'Create Booking'],
                ]
            ],
            [
                'name' => 'bookings.edit',
                'translations' => [
                    'ar' => ['name' => 'bookings.edit', 'display_name' => 'تعديل حجز'],
                    'en' => ['name' => 'bookings.edit', 'display_name' => 'Edit Booking'],
                ]
            ],
            [
                'name' => 'bookings.delete',
                'translations' => [
                    'ar' => ['name' => 'bookings.delete', 'display_name' => 'حذف حجز'],
                    'en' => ['name' => 'bookings.delete', 'display_name' => 'Delete Booking'],
                ]
            ],

            // Contract Management
            [
                'name' => 'contracts.view',
                'translations' => [
                    'ar' => ['name' => 'contracts.view', 'display_name' => 'عرض العقود'],
                    'en' => ['name' => 'contracts.view', 'display_name' => 'View Contracts'],
                ]
            ],
            [
                'name' => 'contracts.create',
                'translations' => [
                    'ar' => ['name' => 'contracts.create', 'display_name' => 'إنشاء عقد'],
                    'en' => ['name' => 'contracts.create', 'display_name' => 'Create Contract'],
                ]
            ],
            [
                'name' => 'contracts.edit',
                'translations' => [
                    'ar' => ['name' => 'contracts.edit', 'display_name' => 'تعديل عقد'],
                    'en' => ['name' => 'contracts.edit', 'display_name' => 'Edit Contract'],
                ]
            ],
            [
                'name' => 'contracts.delete',
                'translations' => [
                    'ar' => ['name' => 'contracts.delete', 'display_name' => 'حذف عقد'],
                    'en' => ['name' => 'contracts.delete', 'display_name' => 'Delete Contract'],
                ]
            ],

            // Category Management
            [
                'name' => 'categories.view',
                'translations' => [
                    'ar' => ['name' => 'categories.view', 'display_name' => 'عرض الفئات'],
                    'en' => ['name' => 'categories.view', 'display_name' => 'View Categories'],
                ]
            ],
            [
                'name' => 'categories.create',
                'translations' => [
                    'ar' => ['name' => 'categories.create', 'display_name' => 'إنشاء فئة'],
                    'en' => ['name' => 'categories.create', 'display_name' => 'Create Category'],
                ]
            ],
            [
                'name' => 'categories.edit',
                'translations' => [
                    'ar' => ['name' => 'categories.edit', 'display_name' => 'تعديل فئة'],
                    'en' => ['name' => 'categories.edit', 'display_name' => 'Edit Category'],
                ]
            ],
            [
                'name' => 'categories.delete',
                'translations' => [
                    'ar' => ['name' => 'categories.delete', 'display_name' => 'حذف فئة'],
                    'en' => ['name' => 'categories.delete', 'display_name' => 'Delete Category'],
                ]
            ],

            // Feature Management
            [
                'name' => 'features.view',
                'translations' => [
                    'ar' => ['name' => 'features.view', 'display_name' => 'عرض المميزات'],
                    'en' => ['name' => 'features.view', 'display_name' => 'View Features'],
                ]
            ],
            [
                'name' => 'features.create',
                'translations' => [
                    'ar' => ['name' => 'features.create', 'display_name' => 'إنشاء مميزة'],
                    'en' => ['name' => 'features.create', 'display_name' => 'Create Feature'],
                ]
            ],
            [
                'name' => 'features.edit',
                'translations' => [
                    'ar' => ['name' => 'features.edit', 'display_name' => 'تعديل مميزة'],
                    'en' => ['name' => 'features.edit', 'display_name' => 'Edit Feature'],
                ]
            ],
            [
                'name' => 'features.delete',
                'translations' => [
                    'ar' => ['name' => 'features.delete', 'display_name' => 'حذف مميزة'],
                    'en' => ['name' => 'features.delete', 'display_name' => 'Delete Feature'],
                ]
            ],

            // Role Management
            [
                'name' => 'roles.view',
                'translations' => [
                    'ar' => ['name' => 'roles.view', 'display_name' => 'عرض الأدوار'],
                    'en' => ['name' => 'roles.view', 'display_name' => 'View Roles'],
                ]
            ],
            [
                'name' => 'roles.create',
                'translations' => [
                    'ar' => ['name' => 'roles.create', 'display_name' => 'إنشاء دور'],
                    'en' => ['name' => 'roles.create', 'display_name' => 'Create Role'],
                ]
            ],
            [
                'name' => 'roles.edit',
                'translations' => [
                    'ar' => ['name' => 'roles.edit', 'display_name' => 'تعديل دور'],
                    'en' => ['name' => 'roles.edit', 'display_name' => 'Edit Role'],
                ]
            ],
            [
                'name' => 'roles.delete',
                'translations' => [
                    'ar' => ['name' => 'roles.delete', 'display_name' => 'حذف دور'],
                    'en' => ['name' => 'roles.delete', 'display_name' => 'Delete Role'],
                ]
            ],

            // System Settings
            [
                'name' => 'settings.view',
                'translations' => [
                    'ar' => ['name' => 'settings.view', 'display_name' => 'عرض الإعدادات'],
                    'en' => ['name' => 'settings.view', 'display_name' => 'View Settings'],
                ]
            ],
            [
                'name' => 'settings.edit',
                'translations' => [
                    'ar' => ['name' => 'settings.edit', 'display_name' => 'تعديل الإعدادات'],
                    'en' => ['name' => 'settings.edit', 'display_name' => 'Edit Settings'],
                ]
            ],

            // Reports
            [
                'name' => 'reports.view',
                'translations' => [
                    'ar' => ['name' => 'reports.view', 'display_name' => 'عرض التقارير'],
                    'en' => ['name' => 'reports.view', 'display_name' => 'View Reports'],
                ]
            ],
            [
                'name' => 'reports.export',
                'translations' => [
                    'ar' => ['name' => 'reports.export', 'display_name' => 'تصدير التقارير'],
                    'en' => ['name' => 'reports.export', 'display_name' => 'Export Reports'],
                ]
            ],
        ];

        foreach ($permissions as $permissionData) {
            // Create permission
            $permission = Permission::create([
                'is_active' => true,
                'guard_name' => 'web',
            ]);

            // Create translations
            foreach ($permissionData['translations'] as $locale => $translationData) {
                PermissionTranslation::create([
                    'permission_id' => $permission->id,
                    'locale' => $locale,
                    'name' => $translationData['name'],
                    'display_name' => $translationData['display_name'],
                ]);
            }
        }
    }
}
