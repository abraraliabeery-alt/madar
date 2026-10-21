<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class FacilityMenuSeeder extends Seeder
{
    public function run(): void
    {
        $all = ['real_estate', 'contracting', 'lifecycle'];
        $re = ['real_estate', 'lifecycle'];
        $co = ['contracting', 'lifecycle'];

        $items = [
            // Main
            ['key' => 'dashboard', 'label_key' => 'facility.dashboard.title', 'route_name' => 'facility.dashboard', 'icon' => 'fas fa-home', 'sort_order' => 10, 'group' => 'main', 'modes' => $all],
            ['key' => 'profile', 'label_key' => 'facility.sidebar.profile', 'route_name' => 'facility.profile', 'icon' => 'fas fa-user', 'sort_order' => 15, 'group' => 'main', 'modes' => $all],

            // Real Estate
            ['key' => 'products', 'label_key' => 'facility.sidebar.products', 'route_name' => 'facility.products.index', 'icon' => 'fas fa-box', 'sort_order' => 20, 'group' => 'real_estate', 'modes' => $re],
            ['key' => 'products_vacant', 'label_key' => 'facility.sidebar.products_vacant', 'route_name' => 'facility.products.vacant', 'icon' => 'fas fa-door-open', 'sort_order' => 21, 'group' => 'real_estate', 'modes' => $re],
            ['key' => 'products_occupancy_map', 'label_key' => 'facility.sidebar.products_occupancy_map', 'route_name' => 'facility.products.occupancy-map', 'icon' => 'fas fa-border-all', 'sort_order' => 22, 'group' => 'real_estate', 'modes' => $re],
            ['key' => 'products_timeline', 'label_key' => 'facility.sidebar.products_timeline', 'route_name' => 'facility.products.timeline', 'icon' => 'fas fa-chart-gantt', 'sort_order' => 22.5, 'group' => 'real_estate', 'modes' => $re],
            ['key' => 'owners', 'label_key' => 'facility.sidebar.owners', 'route_name' => 'facility.owners.index', 'icon' => 'fas fa-user-tie', 'sort_order' => 23, 'group' => 'real_estate', 'modes' => $re],
            ['key' => 'tenants', 'label_key' => 'facility.sidebar.tenants', 'route_name' => 'facility.tenants.index', 'icon' => 'fas fa-user-check', 'sort_order' => 24, 'group' => 'real_estate', 'modes' => $re],
            ['key' => 'maintenance', 'label_key' => 'facility.sidebar.maintenance', 'route_name' => 'facility.maintenance.index', 'icon' => 'fas fa-screwdriver-wrench', 'sort_order' => 25, 'group' => 'real_estate', 'modes' => $re],
            ['key' => 'leads', 'label_key' => 'facility.sidebar.leads', 'route_name' => 'facility.leads.index', 'icon' => 'fas fa-user-plus', 'sort_order' => 26, 'group' => 'real_estate', 'modes' => $re],
            ['key' => 'bookings', 'label_key' => 'facility.sidebar.bookings', 'route_name' => 'facility.bookings.index', 'icon' => 'fas fa-calendar-check', 'sort_order' => 27, 'group' => 'real_estate', 'modes' => $re],
            ['key' => 'bookings_calendar', 'label_key' => 'facility.sidebar.bookings_calendar', 'route_name' => 'facility.bookings.calendar', 'icon' => 'fas fa-calendar-days', 'sort_order' => 28, 'group' => 'real_estate', 'modes' => $re],
            ['key' => 'bookings_statistics', 'label_key' => 'facility.sidebar.bookings_statistics', 'route_name' => 'facility.bookings.statistics', 'icon' => 'fas fa-chart-line', 'sort_order' => 29, 'group' => 'real_estate', 'modes' => $re],
            ['key' => 'offers', 'label_key' => 'facility.sidebar.offers', 'route_name' => 'facility.offers.index', 'icon' => 'fas fa-tag', 'sort_order' => 30, 'group' => 'real_estate', 'modes' => $re],
            ['key' => 'offers_statistics', 'label_key' => 'facility.sidebar.offers_statistics', 'route_name' => 'facility.offers.statistics', 'icon' => 'fas fa-chart-line', 'sort_order' => 31, 'group' => 'real_estate', 'modes' => $re],

            // Contracting
            ['key' => 'products', 'label_key' => 'facility.sidebar.products', 'route_name' => 'facility.products.index', 'icon' => 'fas fa-box', 'sort_order' => 40, 'group' => 'contracting', 'modes' => $co],
            ['key' => 'execution_requests', 'label_key' => 'facility.execution_requests.title', 'route_name' => 'facility.execution-requests.workspace', 'icon' => 'fas fa-gavel', 'sort_order' => 45, 'group' => 'contracting', 'modes' => $co],
            ['key' => 'execution_requests_list', 'label_key' => 'facility.sidebar.execution_requests_list', 'route_name' => 'facility.execution-requests.index', 'icon' => 'fas fa-list', 'sort_order' => 47, 'group' => 'contracting', 'modes' => $co],
            ['key' => 'quotes', 'label_key' => 'facility.quotes.title', 'route_name' => 'facility.quotes.index', 'icon' => 'fas fa-file-invoice-dollar', 'sort_order' => 50, 'group' => 'contracting', 'modes' => $co],
            ['key' => 'tenders', 'label_key' => 'facility.tenders.title', 'route_name' => 'facility.tenders.index', 'icon' => 'fas fa-magnifying-glass-chart', 'sort_order' => 55, 'group' => 'contracting', 'modes' => $co],

            // Financial
            ['key' => 'financial', 'label_key' => 'facility.financial.title', 'route_name' => 'facility.financial.dashboard', 'icon' => 'fas fa-coins', 'sort_order' => 80, 'group' => 'financial', 'modes' => $all],
            ['key' => 'contracts', 'label_key' => 'facility.sidebar.contracts', 'route_name' => 'facility.contracts.index', 'icon' => 'fas fa-file-signature', 'sort_order' => 82, 'group' => 'financial', 'modes' => $all],
            ['key' => 'contracts_statistics', 'label_key' => 'facility.sidebar.contracts_statistics', 'route_name' => 'facility.contracts.statistics', 'icon' => 'fas fa-chart-line', 'sort_order' => 83, 'group' => 'financial', 'modes' => $all],
            ['key' => 'invoices', 'label_key' => 'facility.sidebar.invoices', 'route_name' => 'facility.invoices.index', 'icon' => 'fas fa-file-invoice', 'sort_order' => 84, 'group' => 'financial', 'modes' => $all],
            ['key' => 'invoices_statistics', 'label_key' => 'facility.sidebar.invoices_statistics', 'route_name' => 'facility.invoices.statistics', 'icon' => 'fas fa-chart-line', 'sort_order' => 85, 'group' => 'financial', 'modes' => $all],
            ['key' => 'invoices_generate', 'label_key' => 'facility.sidebar.invoices_generate', 'route_name' => 'facility.invoices.generate', 'icon' => 'fas fa-file-circle-plus', 'sort_order' => 86, 'group' => 'financial', 'modes' => $all],
            ['key' => 'payments', 'label_key' => 'facility.sidebar.payments', 'route_name' => 'facility.payments.index', 'icon' => 'fas fa-money-bill-wave', 'sort_order' => 87, 'group' => 'financial', 'modes' => $all],
            ['key' => 'payments_statistics', 'label_key' => 'facility.sidebar.payments_statistics', 'route_name' => 'facility.payments.statistics', 'icon' => 'fas fa-chart-line', 'sort_order' => 88, 'group' => 'financial', 'modes' => $all],
            ['key' => 'financial_revenue', 'label_key' => 'facility.sidebar.financial_revenue', 'route_name' => 'facility.financial.revenue', 'icon' => 'fas fa-arrow-trend-up', 'sort_order' => 89, 'group' => 'financial', 'modes' => $all],
            ['key' => 'financial_receivables', 'label_key' => 'facility.sidebar.financial_receivables', 'route_name' => 'facility.financial.receivables', 'icon' => 'fas fa-hand-holding-dollar', 'sort_order' => 90, 'group' => 'financial', 'modes' => $all],
            ['key' => 'financial_commissions', 'label_key' => 'facility.sidebar.financial_commissions', 'route_name' => 'facility.financial.commissions', 'icon' => 'fas fa-percent', 'sort_order' => 91, 'group' => 'financial', 'modes' => $all],
            ['key' => 'financial_monthly', 'label_key' => 'facility.sidebar.financial_monthly', 'route_name' => 'facility.financial.monthly', 'icon' => 'fas fa-calendar-week', 'sort_order' => 92, 'group' => 'financial', 'modes' => $all],
            ['key' => 'financial_yearly', 'label_key' => 'facility.sidebar.financial_yearly', 'route_name' => 'facility.financial.yearly', 'icon' => 'fas fa-calendar-days', 'sort_order' => 93, 'group' => 'financial', 'modes' => $all],

            // Accounting
            ['key' => 'accounting', 'label_key' => 'facility.accounting.title', 'route_name' => 'facility.accounting.dashboard', 'icon' => 'fas fa-calculator', 'sort_order' => 95, 'group' => 'accounting', 'modes' => $all],
            ['key' => 'accounting_entries', 'label_key' => 'facility.sidebar.accounting_entries', 'route_name' => 'facility.accounting.entries.index', 'icon' => 'fas fa-book', 'sort_order' => 97, 'group' => 'accounting', 'modes' => $all],
            ['key' => 'chart_of_accounts', 'label_key' => 'facility.sidebar.chart_of_accounts', 'route_name' => 'facility.accounting.chart-of-accounts.index', 'icon' => 'fas fa-list-tree', 'sort_order' => 99, 'group' => 'accounting', 'modes' => $all],
            ['key' => 'accounting_periods', 'label_key' => 'facility.sidebar.accounting_periods', 'route_name' => 'facility.accounting.periods.index', 'icon' => 'fas fa-clock', 'sort_order' => 101, 'group' => 'accounting', 'modes' => $all],
            ['key' => 'tax_rates', 'label_key' => 'facility.sidebar.tax_rates', 'route_name' => 'facility.accounting.tax-rates.index', 'icon' => 'fas fa-percent', 'sort_order' => 103, 'group' => 'accounting', 'modes' => $all],
            ['key' => 'budgets', 'label_key' => 'facility.sidebar.budgets', 'route_name' => 'facility.accounting.budgets.index', 'icon' => 'fas fa-wallet', 'sort_order' => 105, 'group' => 'accounting', 'modes' => $all],
            ['key' => 'accounting_reports', 'label_key' => 'facility.sidebar.accounting_reports', 'route_name' => 'facility.accounting.reports.index', 'icon' => 'fas fa-file-invoice', 'sort_order' => 107, 'group' => 'accounting', 'modes' => $all],

            // Reports
            ['key' => 'reports', 'label_key' => 'facility.reports.title', 'route_name' => 'facility.reports', 'icon' => 'fas fa-chart-bar', 'sort_order' => 110, 'group' => 'reports', 'modes' => $all],
            ['key' => 'reports_bookings', 'label_key' => 'facility.sidebar.reports_bookings', 'route_name' => 'facility.reports.bookings', 'icon' => 'fas fa-file-lines', 'sort_order' => 112, 'group' => 'reports', 'modes' => $all],
            ['key' => 'reports_products', 'label_key' => 'facility.sidebar.reports_products', 'route_name' => 'facility.reports.products', 'icon' => 'fas fa-file-lines', 'sort_order' => 114, 'group' => 'reports', 'modes' => $all],
            ['key' => 'reports_revenue', 'label_key' => 'facility.sidebar.reports_revenue', 'route_name' => 'facility.reports.revenue', 'icon' => 'fas fa-file-lines', 'sort_order' => 116, 'group' => 'reports', 'modes' => $all],
            ['key' => 'reports_occupancy', 'label_key' => 'facility.sidebar.reports_occupancy', 'route_name' => 'facility.reports.rentals.occupancy', 'icon' => 'fas fa-bed', 'sort_order' => 118, 'group' => 'reports', 'modes' => $re],
            ['key' => 'reports_collections', 'label_key' => 'facility.sidebar.reports_collections', 'route_name' => 'facility.reports.rentals.collections', 'icon' => 'fas fa-money-check-dollar', 'sort_order' => 120, 'group' => 'reports', 'modes' => $re],
            ['key' => 'reports_overdue', 'label_key' => 'facility.sidebar.reports_overdue', 'route_name' => 'facility.reports.rentals.overdue', 'icon' => 'fas fa-clock', 'sort_order' => 121, 'group' => 'reports', 'modes' => $re],
            ['key' => 'reports_executive', 'label_key' => 'facility.sidebar.reports_executive', 'route_name' => 'facility.reports.executive', 'icon' => 'fas fa-gauge-high', 'sort_order' => 122, 'group' => 'reports', 'modes' => $re],
            ['key' => 'reports_portfolio', 'label_key' => 'facility.sidebar.reports_portfolio', 'route_name' => 'facility.reports.portfolio', 'icon' => 'fas fa-briefcase', 'sort_order' => 123, 'group' => 'reports', 'modes' => $re],

            // Administration
            ['key' => 'tasks', 'label_key' => 'facility.tasks.title', 'route_name' => 'facility.tasks.index', 'icon' => 'fas fa-list-check', 'sort_order' => 125, 'group' => 'administration', 'modes' => $all],
            ['key' => 'users', 'label_key' => 'facility.users.title', 'route_name' => 'facility.users.index', 'icon' => 'fas fa-users', 'sort_order' => 127, 'group' => 'administration', 'modes' => $all],
            ['key' => 'users_statistics', 'label_key' => 'facility.sidebar.users_statistics', 'route_name' => 'facility.users.statistics', 'icon' => 'fas fa-chart-pie', 'sort_order' => 129, 'group' => 'administration', 'modes' => $all],
            ['key' => 'employee_dashboard', 'label_key' => 'facility.sidebar.employee_dashboard', 'route_name' => 'facility.users.employee-dashboard', 'icon' => 'fas fa-id-badge', 'sort_order' => 131, 'group' => 'administration', 'modes' => $all],
            ['key' => 'appointments', 'label_key' => 'facility.sidebar.appointments', 'route_name' => 'facility.appointments.index', 'icon' => 'fas fa-calendar', 'sort_order' => 133, 'group' => 'administration', 'modes' => $all],
            ['key' => 'statistics', 'label_key' => 'facility.sidebar.statistics', 'route_name' => 'facility.statistics', 'icon' => 'fas fa-chart-pie', 'sort_order' => 135, 'group' => 'administration', 'modes' => $all],
            ['key' => 'smart_broker', 'label_key' => 'facility.sidebar.smart_broker', 'route_name' => 'facility.smart-broker.index', 'icon' => 'fas fa-robot', 'sort_order' => 137, 'group' => 'administration', 'modes' => $all],
            ['key' => 'customization', 'label_key' => 'facility.sidebar.customization', 'route_name' => 'facility.customization.edit', 'icon' => 'fas fa-palette', 'sort_order' => 139, 'group' => 'administration', 'modes' => $all],
            ['key' => 'settings', 'label_key' => 'facility.settings.title', 'route_name' => 'facility.settings', 'icon' => 'fas fa-cog', 'sort_order' => 141, 'group' => 'administration', 'modes' => $all],
            ['key' => 'notifications', 'label_key' => 'facility.sidebar.notifications', 'route_name' => 'facility.notifications', 'icon' => 'fas fa-bell', 'sort_order' => 143, 'group' => 'administration', 'modes' => $all],
            ['key' => 'change_password', 'label_key' => 'facility.sidebar.change_password', 'route_name' => 'facility.change-password', 'icon' => 'fas fa-lock', 'sort_order' => 145, 'group' => 'administration', 'modes' => $all],
        ];

        $subKeys = [
            'bookings_calendar', 'bookings_statistics', 'offers_statistics',
            'execution_requests_list', 'contracts_statistics', 'invoices_statistics',
            'invoices_generate', 'payments_statistics',
            'financial_revenue', 'financial_receivables', 'financial_commissions',
            'financial_monthly', 'financial_yearly',
            'accounting_entries', 'chart_of_accounts', 'accounting_periods',
            'tax_rates', 'budgets', 'accounting_reports',
            'reports_bookings', 'reports_products', 'reports_revenue',
            'reports_occupancy', 'reports_collections', 'reports_overdue', 'reports_executive', 'reports_portfolio',
            'products_vacant', 'products_occupancy_map', 'products_timeline',
            'users_statistics', 'employee_dashboard',
        ];

        foreach ($items as $item) {
            MenuItem::updateOrCreate(
                ['panel' => 'facility', 'key' => $item['key']],
                [
                    'label_key' => $item['label_key'],
                    'icon' => $item['icon'],
                    'route_name' => $item['route_name'],
                    'url' => null,
                    'sort_order' => $item['sort_order'],
                    'enabled' => true,
                    'visibility' => [
                        'modes' => $item['modes'],
                        'group' => $item['group'],
                        'sub' => in_array($item['key'], $subKeys, true),
                    ],
                ]
            );
        }
    }
}
