<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'title' => 'تمييز الإعلانات',
                'title_en' => 'Featured Ads',
                'slug' => 'featured-ads',
                'type' => 'link',
                'url' => '/products/featured',
                'sort_order' => 1,
            ],
            [
                'title' => 'مدونة',
                'title_en' => 'Blog',
                'slug' => 'blog',
                'type' => 'link',
                'url' => '/blog',
                'sort_order' => 2,
            ],
            [
                'title' => 'شروط الاستخدام',
                'title_en' => 'Terms of Service',
                'slug' => 'terms',
                'type' => 'footer',
                'sort_order' => 3,
            ],
            [
                'title' => 'سياسة الخصوصية',
                'title_en' => 'Privacy Policy',
                'slug' => 'privacy',
                'type' => 'footer',
                'sort_order' => 4,
            ],
            [
                'title' => 'سياسة ملفات تعريف الارتباط',
                'title_en' => 'Cookie Policy',
                'slug' => 'cookies',
                'type' => 'footer',
                'sort_order' => 5,
            ],
            [
                'title' => 'سياسة الإعلانات',
                'title_en' => 'Advertising Policy',
                'slug' => 'advertising',
                'type' => 'footer',
                'sort_order' => 6,
            ],
            [
                'title' => 'كيف يعمل الموقع',
                'title_en' => 'How It Works',
                'slug' => 'how-it-works',
                'type' => 'link',
                'url' => '/how-it-works',
                'sort_order' => 7,
            ],
            [
                'title' => 'الأسئلة الشائعة',
                'title_en' => 'FAQ',
                'slug' => 'faq',
                'type' => 'link',
                'url' => '/faq',
                'sort_order' => 8,
            ],
        ];

        foreach ($pages as $page) {
            Page::updateOrCreate(
                ['slug' => $page['slug']],
                $page
            );
        }
    }
}
