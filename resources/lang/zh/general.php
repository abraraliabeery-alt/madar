<?php

return [
    /*
    |--------------------------------------------------------------------------
    | General Public Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used in the general public pages
    | for various messages, labels, and interface elements.
    |
    */

    'close' => '关闭',

    'home' => [
        'title' => '承包平台 — 发布项目并获取投标',
        'subtitle' => '发布项目，接收承包商投标，并在一个平台对比报价。',
        'search_placeholder' => '搜索项目、招标、承包商或区域…',
        'search_button' => '搜索',
        'search_on_map' => '地图搜索',
        'quick_search' => '快速搜索',
        'advanced_search' => '高级搜索',
        'map_search' => '地图搜索',
        'open_advanced_search' => '打开高级搜索',
        'open_map' => '打开地图',
        'our_services_title' => '我们的服务',

        'featured_categories' => '精选分类',
        'latest_properties' => '最新项目',
        'featured_cities' => '精选城市',
        'view_all_properties' => '查看所有项目',
        'view_all_cities' => '查看所有城市',
        'cta_title' => '想发布项目或招标吗？',
        'cta_subtitle' => '立即创建并轻松接收承包商投标',
        'register_now' => '立即注册',
        'contact_us' => '联系我们',

        'services' => [
            'project_platform' => [
                'title' => '项目平台',
                'description' => '发布您的项目或浏览项目并提交执行报价。',
            ],
            'contractors_directory' => [
                'title' => '承包商与公司目录',
                'description' => '浏览承包商/机构并联系合适的执行方。',
            ],
            'browse_projects' => [
                'title' => '浏览项目',
                'description' => '按分类、城市与位置特征浏览项目。',
            ],
            'analysis_center' => [
                'title' => '分析中心',
                'description' => '可行性分析与改进建议，符合沙特规范。',
            ],
        ],

        'stats' => [
            'total_projects' => '项目总数',
            'companies_and_facilities' => '公司与承包商',
            'categories' => '分类',
            'featured_projects' => '精选项目',
        ],
    ],

    'view_toggle' => [
        'display' => '显示：',
        'grid' => '网格视图',
        'row' => '行视图',
        'small_grid' => '小网格',
        'large_grid' => '大网格',
        'list' => '列表视图',
    ],

    'status' => [
        'featured' => '精选',
        'verified' => '已验证',
        'price_on_request' => '价格面议',
        'property' => '项目',
        'properties' => '项目',
    ],

    'actions' => [
        'load_more' => '加载更多',
        'view_details' => '查看详情',
        'browse_category' => '浏览分类',
        'browse_properties' => '浏览项目',
    ],

    'currency' => [
        'sar' => \App\Helpers\LanguageHelper::getSaudiRiyalSymbol(),
        'usd' => 'USD',
        'eur' => 'EUR',
    ],
];
