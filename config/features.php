<?php

return [
    'facility_home_v2' => env('FACILITY_HOME_V2', true),
    'facility_product_location_v2' => env('FACILITY_PRODUCT_LOCATION_V2', false),
    'facility_product_create_v2' => env('FACILITY_PRODUCT_CREATE_V2', false),
    'facility_product_map_picker' => env('FACILITY_PRODUCT_MAP_PICKER', false),
    'facility_product_validation_v2' => env('FACILITY_PRODUCT_VALIDATION_V2', false),
    'facility_product_voice_assist' => env('FACILITY_PRODUCT_VOICE_ASSIST', false),
    // New feature flags
    'public_investment_properties' => env('PUBLIC_INVESTMENT_PROPERTIES', true),
    'facility_dashboard_v1' => env('FACILITY_DASHBOARD_V1', false),
    'facility_crm_v1' => env('FACILITY_CRM_V1', false),
    'facility_listings_qs' => env('FACILITY_LISTINGS_QS', false),
    'facility_analytics_v1' => env('FACILITY_ANALYTICS_V1', false),
    'facility_integrations_v1' => env('FACILITY_INTEGRATIONS_V1', false),
    'auto_reminders' => env('FEATURE_AUTO_REMINDERS', false),
    'sales' => env('FEATURE_SALES', false),
    'facility_lifecycle_widgets' => env('FACILITY_LIFECYCLE_WIDGETS', false),
    'facility_nba_widget' => env('FACILITY_NBA_WIDGET', false),
    'facility_ui_v2' => env('FACILITY_UI_V2', false),
    // HR feature flags
    'hr_core' => env('HR_CORE', false),
    'hr_attendance' => env('HR_ATTENDANCE', false),
    'hr_leave' => env('HR_LEAVE', false),
    'hr_payroll_lite' => env('HR_PAYROLL_LITE', false),
    'hr_performance' => env('HR_PERFORMANCE', false),

    'contracting_rebrand' => true,
];

