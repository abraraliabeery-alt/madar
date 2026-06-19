<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Facility;
use App\Models\Category;
use App\Models\CategoryTranslation;
use App\Models\Status;
use App\Models\Feature;
use App\Models\City;
use App\Models\Page;
use App\Models\ExecutionRequest;

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

        $featuredFacilities = Facility::with(['facilityCategory'])
            ->where('is_featured', true)
            ->where('is_active', true)
            ->latest()
            ->take(6)
            ->get();

        $categories = Category::withCount(['products' => function ($query) {
            $query->where('is_active', true);
        }])->take(8)->get();

        $searchCategories = Category::where('is_active', true)->get();
        $searchFeatures = Feature::where('is_active', true)
            ->with('translations')
            ->get();

        $latestProducts = Product::with(['facility', 'category'])
            ->where('is_active', true)
            ->latest()
            ->take(12)
            ->get();

        $latestExecutionRequests = ExecutionRequest::query()
            ->with(['translations'])
            ->where('status', 'open')
            ->latest()
            ->take(8)
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
            'searchCategories',
            'searchFeatures',
            'latestProducts',
            'latestExecutionRequests',
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
                'title' => 'طرح المشاريع والمنافسات',
                'description' => 'أنشئ منافستك وحدد نطاق الأعمال والميزانية والمدة',
                'icon' => 'fas fa-gavel',
                'features' => [
                    'إنشاء منافسة وربطها بالفئة والمدينة',
                    'تحديد الميزانية والمدة والموعد النهائي',
                    'نشر تفاصيل النطاق والمتطلبات',
                    'تحديثات وإشعارات للمنافسات'
                ]
            ],
            [
                'title' => 'استقبال العروض والمقارنة',
                'description' => 'استقبل عروض المقاولين وقارن السعر والمدة والضمان',
                'icon' => 'fas fa-file-invoice-dollar',
                'features' => [
                    'جمع العروض في صفحة واحدة',
                    'مقارنة سعر إجمالي ومدد التنفيذ',
                    'ملاحظات ومرفقات لكل عرض',
                    'تتبع حالات العروض'
                ]
            ],
            [
                'title' => 'التأهيل والاعتماد',
                'description' => 'تأهيل مبدئي والتحقق من جاهزية المقاول قبل الترسية',
                'icon' => 'fas fa-user-check',
                'features' => [
                    'متطلبات تأهيل قابلة للتخصيص',
                    'سجل اعتمادات وخبرات المقاول',
                    'قوائم مختصرة للمنافسات',
                    'شفافية في قرارات التأهيل'
                ]
            ],
            [
                'title' => 'الترسية والعقود والمتابعة',
                'description' => 'ترسية واضحة ثم متابعة التنفيذ عبر مراحل ومحاضر',
                'icon' => 'fas fa-file-contract',
                'features' => [
                    'توثيق قرار الترسية',
                    'بنود وملاحق العقود',
                    'متابعة مراحل التنفيذ',
                    'إدارة التغييرات والتسليمات'
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
                'description' => 'خبرة 15 عام في إدارة المشاريع',
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
                'description' => 'متخصصة في تطوير الأعمال والشراكات',
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
                'description' => 'خبرة في تشغيل منصات المشاريع والمنافسات',
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
                'description' => 'متخصصة في التسويق الرقمي للمنصات والخدمات',
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
                'question' => 'كيف يمكنني البحث عن مشروع أو منافسة؟',
                'answer' => 'يمكنك استخدام البحث في الصفحة الرئيسية أو تصفح الفئات للعثور على المشاريع والمنافسات المناسبة.'
            ],
            [
                'question' => 'ما هي رسوم الخدمة؟',
                'answer' => 'تختلف الرسوم حسب نوع الخدمة وحجم المشروع. يمكنك التواصل معنا للحصول على عرض سعر مفصل.'
            ],
            [
                'question' => 'كيف يمكنني تقديم عرض؟',
                'answer' => 'يمكنك تقديم عرض من خلال صفحة المشروع وإرفاق السعر والمدة والملاحظات، ثم متابعة حالة التأهيل والترسية.'
            ],
            [
                'question' => 'هل تقدمون خدمات التمويل؟',
                'answer' => 'نعم، نتعاون مع عدة بنوك ومؤسسات مالية لتقديم أفضل عروض التمويل لعملائنا.'
            ],
            [
                'question' => 'كيف يمكنني إضافة مشروع أو منافسة؟',
                'answer' => 'يمكنك التسجيل كجهة/منشأة ثم إضافة مشروعك من خلال لوحة التحكم، وتحديد المتطلبات والمدة والميزانية.'
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

    public function investmentProperties(Request $request)
    {
        $searchCategories = Category::where('is_active', true)->get();
        $searchFeatures = Feature::where('is_active', true)
            ->with('translations')
            ->get();

        $investmentCategoryId = CategoryTranslation::query()
            ->where(function ($q) {
                $q->where(function ($q) {
                    $q->where('name', 'like', '%أراضي%')
                        ->where('name', 'like', '%استثمار%');
                })->orWhere(function ($q) {
                    $q->where('name', 'like', '%اراضي%')
                        ->where('name', 'like', '%استثمار%');
                });
            })
            ->value('category_id');

        // Collect root + all descendants (not only direct children)
        $investmentCategoryIds = collect();
        if ($investmentCategoryId) {
            $investmentCategoryIds = collect([$investmentCategoryId]);

            $queue = collect([$investmentCategoryId]);
            while ($queue->isNotEmpty()) {
                $children = Category::whereIn('parent_id', $queue)->pluck('id');
                $new = $children->diff($investmentCategoryIds);
                if ($new->isEmpty()) {
                    break;
                }
                $investmentCategoryIds = $investmentCategoryIds->merge($new);
                $queue = $new;
            }
        }

        // Also include the specific category id=7 (requested for this page)
        if (Category::where('id', 7)->where('is_active', true)->exists()) {
            $investmentCategoryIds = $investmentCategoryIds->merge([7])->unique()->values();
        }

        // Hard stop: do not show unrelated products if the investment category wasn't found
        if ($investmentCategoryIds->isEmpty()) {
            $featuredInvestmentProperties = collect();
            $latestInvestmentProperties = collect();
            $stats = [
                'properties' => 0,
                'developers' => 0,
                'offers' => 0,
            ];

            return view('public.investment-properties', compact(
                'searchCategories',
                'searchFeatures',
                'featuredInvestmentProperties',
                'latestInvestmentProperties',
                'stats'
            ));
        }

        $baseInvestmentProductsQuery = Product::query()
            ->where('is_active', true)
            ->where('is_verified', true)
            ->withActiveOffers()
            ->whereIn('category_id', $investmentCategoryIds);

        $featuredInvestmentProperties = (clone $baseInvestmentProductsQuery)
            ->with(['facility', 'category', 'offers'])
            ->where('is_featured', true)
            ->latest()
            ->take(8)
            ->get();

        $latestInvestmentProperties = (clone $baseInvestmentProductsQuery)
            ->with(['facility', 'category', 'offers'])
            ->latest()
            ->take(12)
            ->get();

        $investmentProductIds = (clone $baseInvestmentProductsQuery)->pluck('id');
        $investmentFacilityIds = Product::whereIn('id', $investmentProductIds)->pluck('facility_id')->filter()->unique();

        $stats = [
            'properties' => $investmentProductIds->count(),
            'developers' => Facility::whereIn('id', $investmentFacilityIds)->where('is_active', true)->where('is_verified', true)->count(),
            'offers' => \App\Models\Offer::whereIn('product_id', $investmentProductIds)->where('is_active', true)->count(),
        ];

        return view('public.investment-properties', compact(
            'searchCategories',
            'searchFeatures',
            'featuredInvestmentProperties',
            'latestInvestmentProperties',
            'stats'
        ));
    }
}
