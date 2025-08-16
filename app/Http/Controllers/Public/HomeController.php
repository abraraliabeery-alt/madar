<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Facility;
use App\Models\Category;
use App\Models\Status;
use App\Models\City;
use App\Models\Page;

class HomeController extends Controller
{
    /**
     * عرض الصفحة الرئيسية
     */
    public function index()
    {
        $featuredProducts = Product::with(['facility', 'category'])
            ->where('is_featured', true)
            ->where('is_active', true)
            ->latest()
            ->take(8)
            ->get();

        $featuredFacilities = Facility::with(['category'])
            ->where('is_featured', true)
            ->where('is_active', true)
            ->latest()
            ->take(6)
            ->get();

        $categories = Category::withCount(['products' => function ($query) {
            $query->where('is_active', true);
        }])->take(8)->get();

        $latestProducts = Product::with(['facility', 'category'])
            ->where('is_active', true)
            ->latest()
            ->take(12)
            ->get();

        // Coming soon products: available in the future
        $comingSoonProducts = Product::with(['facility', 'category'])
            ->where('is_active', true)
            ->where('available_from', '>', now())
            ->orderBy('available_from')
            ->take(6)
            ->get();

        $stats = [
            'total_products' => Product::where('is_active', true)->count(),
            'total_facilities' => Facility::where('is_active', true)->count(),
            'total_categories' => Category::count(),
            'featured_products' => Product::where('is_featured', true)->where('is_active', true)->count(),
        ];

        // Get featured cities with products count
        $featuredCities = City::withCount(['products' => function ($query) {
            $query->where('is_active', true);
        }])
        ->where('is_featured', true)
        ->where('is_active', true)
        ->ordered()
        ->take(6)
        ->get();

        // Get footer links
        $footerLinks = Page::ofType('footer')
            ->active()
            ->ordered()
            ->get();

        // Get main navigation links
        $navLinks = Page::ofType('link')
            ->active()
            ->ordered()
            ->get();

        return view('public.home', compact(
            'featuredProducts',
            'featuredFacilities',
            'categories',
            'latestProducts',
            'stats',
            'comingSoonProducts',
            'featuredCities',
            'footerLinks',
            'navLinks'
        ));
    }

    /**
     * عرض صفحة حول الموقع
     */
    public function about()
    {
        return view('public.about');
    }

    /**
     * عرض صفحة الخدمات
     */
    public function services()
    {
        $services = [
            [
                'title' => 'بيع العقارات',
                'description' => 'نقدم خدمات بيع العقارات السكنية والتجارية',
                'icon' => 'fas fa-home',
                'features' => [
                    'تقييم العقار',
                    'إعداد العقود',
                    'متابعة البيع',
                    'خدمة ما بعد البيع'
                ]
            ],
            [
                'title' => 'تأجير العقارات',
                'description' => 'خدمات تأجير العقارات قصيرة وطويلة المدى',
                'icon' => 'fas fa-key',
                'features' => [
                    'عقود الإيجار',
                    'إدارة الممتلكات',
                    'صيانة دورية',
                    'حل النزاعات'
                ]
            ],
            [
                'title' => 'إدارة المنشآت',
                'description' => 'خدمات إدارة المنشآت العقارية',
                'icon' => 'fas fa-building',
                'features' => [
                    'إدارة الموظفين',
                    'إدارة المهام',
                    'التقارير المالية',
                    'التسويق والإعلان'
                ]
            ],
            [
                'title' => 'الاستشارات العقارية',
                'description' => 'استشارات متخصصة في مجال العقارات',
                'icon' => 'fas fa-chart-line',
                'features' => [
                    'دراسات الجدوى',
                    'تقييم الاستثمارات',
                    'تحليل السوق',
                    'التخطيط الاستراتيجي'
                ]
            ]
        ];

        return view('public.services', compact('services'));
    }

    /**
     * عرض صفحة الفريق
     */
    public function team()
    {
        $team = [
            [
                'name' => 'أحمد محمد',
                'position' => 'المدير التنفيذي',
                'description' => 'خبرة 15 عام في مجال العقارات',
                'image' => 'team/ceo.jpg',
                'social' => [
                    'linkedin' => '#',
                    'twitter' => '#',
                    'email' => 'ahmed@example.com'
                ]
            ],
            [
                'name' => 'فاطمة علي',
                'position' => 'مدير المبيعات',
                'description' => 'متخصصة في تسويق العقارات الفاخرة',
                'image' => 'team/sales.jpg',
                'social' => [
                    'linkedin' => '#',
                    'twitter' => '#',
                    'email' => 'fatima@example.com'
                ]
            ],
            [
                'name' => 'محمد حسن',
                'position' => 'مدير العمليات',
                'description' => 'خبرة في إدارة المشاريع العقارية',
                'image' => 'team/operations.jpg',
                'social' => [
                    'linkedin' => '#',
                    'twitter' => '#',
                    'email' => 'mohamed@example.com'
                ]
            ],
            [
                'name' => 'سارة أحمد',
                'position' => 'مدير التسويق',
                'description' => 'متخصصة في التسويق الرقمي للعقارات',
                'image' => 'team/marketing.jpg',
                'social' => [
                    'linkedin' => '#',
                    'twitter' => '#',
                    'email' => 'sara@example.com'
                ]
            ]
        ];

        return view('public.team', compact('team'));
    }

    /**
     * عرض صفحة الشروط والأحكام
     */
    public function terms()
    {
        return view('public.terms');
    }

    /**
     * عرض صفحة سياسة الخصوصية
     */
    public function privacy()
    {
        return view('public.privacy');
    }

    /**
     * عرض صفحة الأسئلة الشائعة
     */
    public function faq()
    {
        $faqs = [
            [
                'question' => 'كيف يمكنني البحث عن عقار؟',
                'answer' => 'يمكنك استخدام محرك البحث في الصفحة الرئيسية أو تصفح الفئات المختلفة للعثور على العقار المناسب.'
            ],
            [
                'question' => 'ما هي رسوم الخدمة؟',
                'answer' => 'تختلف الرسوم حسب نوع الخدمة وحجم العقار. يمكنك التواصل معنا للحصول على عرض سعر مفصل.'
            ],
            [
                'question' => 'كيف يمكنني حجز موعد لزيارة عقار؟',
                'answer' => 'يمكنك حجز موعد من خلال صفحة العقار أو التواصل معنا مباشرة عبر الهاتف أو البريد الإلكتروني.'
            ],
            [
                'question' => 'هل تقدمون خدمات التمويل؟',
                'answer' => 'نعم، نتعاون مع عدة بنوك ومؤسسات مالية لتقديم أفضل عروض التمويل لعملائنا.'
            ],
            [
                'question' => 'كيف يمكنني إضافة عقاري للموقع؟',
                'answer' => 'يمكنك التسجيل كمالك منشأة وإضافة عقاراتك من خلال لوحة التحكم الخاصة بك.'
            ],
            [
                'question' => 'ما هي ضمانات الخدمة؟',
                'answer' => 'نقدم ضمانات شاملة لجميع خدماتنا ونتابع مع عملائنا حتى اكتمال العملية بنجاح.'
            ]
        ];

        return view('public.faq', compact('faqs'));
    }

    /**
     * عرض صفحة خريطة الموقع
     */
    public function sitemap()
    {
        $sitemap = [
            'الرئيسية' => [
                'url' => route('home'),
                'pages' => [
                    'المنتجات المميزة' => route('products.featured'),
                    'المنشآت المميزة' => route('facilities.featured'),
                    'آخر المنتجات' => route('products.latest'),
                ]
            ],
            'المنتجات' => [
                'url' => route('products.index'),
                'pages' => [
                    'البحث' => route('search'),
                    'الفئات' => route('categories.index'),
                    'المناطق' => route('areas.index'),
                ]
            ],
            'المنشآت' => [
                'url' => route('facilities.index'),
                'pages' => [
                    'البحث' => route('facilities.search'),
                    'الفئات' => route('facility.categories.index'),
                ]
            ],
            'الخدمات' => [
                'url' => route('services'),
                'pages' => [
                    'بيع العقارات' => route('services.sale'),
                    'تأجير العقارات' => route('services.rent'),
                    'إدارة المنشآت' => route('services.management'),
                    'الاستشارات' => route('services.consulting'),
                ]
            ],
            'حول الموقع' => [
                'url' => route('about'),
                'pages' => [
                    'الفريق' => route('team'),
                    'الأسئلة الشائعة' => route('faq'),
                    'الشروط والأحكام' => route('terms'),
                    'سياسة الخصوصية' => route('privacy'),
                ]
            ],
            'التواصل' => [
                'url' => route('contact'),
                'pages' => [
                    'طلب عرض سعر' => route('contact.quote'),
                    'الشكاوى والاقتراحات' => route('contact.feedback'),
                ]
            ]
        ];

        return view('public.sitemap', compact('sitemap'));
    }
}
