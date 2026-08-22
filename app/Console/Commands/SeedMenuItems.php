<?php

namespace App\Console\Commands;

use App\Models\MenuItem;
use Illuminate\Console\Command;

class SeedMenuItems extends Command
{
    protected $signature = 'menus:seed';

    protected $description = 'Seed default menu items into menu_items table';

    public function handle(): int
    {
        $defaults = [
            ['panel' => 'public', 'key' => 'home', 'label_key' => 'layout.navigation.home', 'route_name' => 'public.home', 'icon' => null, 'sort_order' => 10],
            ['panel' => 'public', 'key' => 'products', 'label_key' => 'public.navigation.products', 'route_name' => 'public.products.index', 'icon' => null, 'sort_order' => 20, 'visibility' => ['modes' => ['real_estate', 'lifecycle']]],
            ['panel' => 'public', 'key' => 'products_map', 'label_key' => 'public.search.map_search', 'route_name' => 'public.products.map', 'icon' => null, 'sort_order' => 30, 'visibility' => ['modes' => ['real_estate', 'lifecycle']]],
            ['panel' => 'public', 'key' => 'facilities', 'label_key' => 'layout.navigation.facilities', 'route_name' => 'public.facilities.index', 'icon' => null, 'sort_order' => 40, 'visibility' => ['modes' => ['real_estate', 'lifecycle']]],
            ['panel' => 'public', 'key' => 'projects', 'label_key' => 'layout.navigation.projects', 'route_name' => 'public.execution.marketplace', 'icon' => null, 'sort_order' => 50, 'visibility' => ['modes' => ['contracting', 'lifecycle']]],
            ['panel' => 'public', 'key' => 'suppliers', 'label_key' => 'layout.navigation.suppliers', 'route_name' => 'public.suppliers', 'icon' => null, 'sort_order' => 60],
            ['panel' => 'public', 'key' => 'factories', 'label_key' => 'layout.navigation.factories', 'route_name' => 'public.factories', 'icon' => null, 'sort_order' => 70],

            ['panel' => 'admin', 'key' => 'dashboard', 'label_key' => 'admin.dashboard.title', 'route_name' => 'admin.dashboard', 'icon' => 'fas fa-home', 'sort_order' => 10],
            ['panel' => 'admin', 'key' => 'users', 'label_key' => 'admin.users.title', 'route_name' => 'admin.users.index', 'icon' => 'fas fa-users', 'sort_order' => 20],
            ['panel' => 'admin', 'key' => 'roles', 'label_key' => 'admin.roles.title', 'route_name' => 'admin.roles.index', 'icon' => 'fas fa-user-shield', 'sort_order' => 21],
            ['panel' => 'admin', 'key' => 'permissions', 'label_key' => 'admin.permissions.title', 'route_name' => 'admin.permissions.index', 'icon' => 'fas fa-user-cog', 'sort_order' => 22],
            ['panel' => 'admin', 'key' => 'facilities', 'label_key' => 'admin.facilities.title', 'route_name' => 'admin.facilities.index', 'icon' => 'fas fa-building', 'sort_order' => 30],
            ['panel' => 'admin', 'key' => 'categories', 'label_key' => 'admin.categories.title', 'route_name' => 'admin.categories.index', 'icon' => 'fas fa-th-large', 'sort_order' => 35],
            ['panel' => 'admin', 'key' => 'project_categories', 'label_key' => 'admin.project_categories.title', 'label_override' => 'إدارة تصنيفات المشاريع', 'route_name' => 'admin.project-categories.index', 'icon' => 'fas fa-layer-group', 'sort_order' => 36],
            ['panel' => 'admin', 'key' => 'features', 'label_key' => 'admin.features.title', 'route_name' => 'admin.features.index', 'icon' => 'fas fa-star', 'sort_order' => 37],
            ['panel' => 'admin', 'key' => 'attributes', 'label_key' => 'admin.attributes.title', 'route_name' => 'admin.attributes.index', 'icon' => 'fas fa-tags', 'sort_order' => 38],
            ['panel' => 'admin', 'key' => 'products', 'label_key' => 'admin.products.title', 'route_name' => 'admin.products.index', 'icon' => 'fas fa-box', 'sort_order' => 40, 'visibility' => ['modes' => ['real_estate', 'lifecycle']]],
            ['panel' => 'admin', 'key' => 'bookings', 'label_key' => 'admin.bookings.title', 'route_name' => 'admin.bookings.index', 'icon' => 'fas fa-calendar-check', 'sort_order' => 50],
            ['panel' => 'admin', 'key' => 'product_requests', 'label_key' => 'admin.product_requests.title', 'route_name' => 'admin.product-requests.index', 'icon' => 'fas fa-clipboard-list', 'sort_order' => 51],
            ['panel' => 'admin', 'key' => 'marketing_product_requests', 'label_key' => 'admin.product_requests.marketing_title', 'route_name' => 'admin.marketing-product-requests.index', 'icon' => 'fas fa-bullhorn', 'sort_order' => 52],
            ['panel' => 'admin', 'key' => 'contracts', 'label_key' => 'admin.contracts.title', 'route_name' => 'admin.contracts.index', 'icon' => 'fas fa-file-contract', 'sort_order' => 60],
            ['panel' => 'admin', 'key' => 'faqs', 'label_key' => 'admin.faqs.title', 'route_name' => 'admin.faqs.index', 'icon' => 'fas fa-question-circle', 'sort_order' => 62],
            ['panel' => 'admin', 'key' => 'notifications', 'label_key' => 'layout.notifications.title', 'route_name' => 'admin.notifications', 'icon' => 'fas fa-bell', 'sort_order' => 70],
            ['panel' => 'admin', 'key' => 'statistics', 'label_key' => 'admin.statistics.title', 'label_override' => 'الإحصائيات', 'route_name' => 'admin.statistics', 'icon' => 'fas fa-chart-bar', 'sort_order' => 75],
            ['panel' => 'admin', 'key' => 'menus', 'label_key' => 'admin.settings.title', 'route_name' => 'admin.menus.index', 'icon' => 'fas fa-list', 'sort_order' => 80],
            ['panel' => 'admin', 'key' => 'settings', 'label_key' => 'admin.settings.title', 'route_name' => 'admin.settings', 'icon' => 'fas fa-cog', 'sort_order' => 90],

            ['panel' => 'facility', 'key' => 'dashboard', 'label_key' => 'facility.dashboard.title', 'route_name' => 'facility.dashboard', 'icon' => 'fas fa-home', 'sort_order' => 10],
            ['panel' => 'facility', 'key' => 'projects', 'label_key' => 'facility.projects.title', 'route_name' => 'facility.projects.index', 'icon' => 'fas fa-diagram-project', 'sort_order' => 20, 'visibility' => ['modes' => ['contracting', 'lifecycle']]],
            ['panel' => 'facility', 'key' => 'execution_requests', 'label_key' => 'facility.execution_requests.title', 'route_name' => 'facility.execution-requests.workspace', 'icon' => 'fas fa-gavel', 'sort_order' => 30, 'visibility' => ['modes' => ['contracting', 'lifecycle']]],
            ['panel' => 'facility', 'key' => 'tasks', 'label_key' => 'facility.tasks.title', 'route_name' => 'facility.tasks.index', 'icon' => 'fas fa-list-check', 'sort_order' => 40],
            ['panel' => 'facility', 'key' => 'accounting', 'label_key' => 'facility.accounting.title', 'route_name' => 'facility.accounting.dashboard', 'icon' => 'fas fa-calculator', 'sort_order' => 50],
            ['panel' => 'facility', 'key' => 'financial', 'label_key' => 'facility.financial.title', 'route_name' => 'facility.financial.dashboard', 'icon' => 'fas fa-coins', 'sort_order' => 60],
            ['panel' => 'facility', 'key' => 'users', 'label_key' => 'facility.users.title', 'route_name' => 'facility.users.index', 'icon' => 'fas fa-users', 'sort_order' => 70],
            ['panel' => 'facility', 'key' => 'reports', 'label_key' => 'facility.reports.title', 'route_name' => 'facility.reports', 'icon' => 'fas fa-chart-bar', 'sort_order' => 80],
            ['panel' => 'facility', 'key' => 'settings', 'label_key' => 'facility.settings.title', 'route_name' => 'facility.edit', 'icon' => 'fas fa-cog', 'sort_order' => 90],

            ['panel' => 'client', 'key' => 'dashboard', 'label_key' => 'client.navigation.dashboard', 'route_name' => 'client.dashboard', 'icon' => 'fas fa-home', 'sort_order' => 10],
            ['panel' => 'client', 'key' => 'projects', 'label_key' => 'client.navigation.create_project', 'route_name' => 'client.projects.create', 'icon' => 'fas fa-diagram-project', 'sort_order' => 20, 'visibility' => ['modes' => ['contracting', 'lifecycle']]],
            ['panel' => 'client', 'key' => 'bookings', 'label_key' => 'client.navigation.bookings', 'route_name' => 'client.bookings.index', 'icon' => 'fas fa-calendar-check', 'sort_order' => 30],
            ['panel' => 'client', 'key' => 'contracts', 'label_key' => 'client.navigation.contracts', 'route_name' => 'client.contracts.index', 'icon' => 'fas fa-file-contract', 'sort_order' => 40],
            ['panel' => 'client', 'key' => 'profile', 'label_key' => 'layout.user_menu.profile', 'route_name' => 'client.profile', 'icon' => 'fas fa-user', 'sort_order' => 50],
        ];

        foreach ($defaults as $row) {
            MenuItem::updateOrCreate(
                ['panel' => $row['panel'], 'key' => $row['key']],
                [
                    'label_key' => $row['label_key'],
                    'label_override' => null,
                    'route_name' => $row['route_name'] ?? null,
                    'url' => $row['url'] ?? null,
                    'icon' => $row['icon'] ?? null,
                    'sort_order' => $row['sort_order'] ?? 0,
                    'enabled' => true,
                    'visibility' => $row['visibility'] ?? null,
                ]
            );
        }

        $this->info('Seeded menu items: ' . count($defaults));

        return self::SUCCESS;
    }
}
