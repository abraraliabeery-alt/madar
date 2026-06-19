<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\SearchController;
use App\Http\Controllers\Public\ContactController;
use App\Http\Controllers\Public\ProductController;
use App\Http\Controllers\Public\FacilityController;
use App\Http\Controllers\Public\CategoryController;
use App\Http\Controllers\Public\FacilityCategoryController;
use App\Http\Controllers\Public\FeatureController;
use App\Http\Controllers\Public\LanguageController;
use App\Http\Controllers\Public\SitemapController;
use App\Http\Controllers\Public\RssController;
use App\Http\Controllers\Public\StaticController;
use App\Http\Controllers\AI\LandStudyController;
use App\Http\Controllers\Public\CityController;
use App\Http\Controllers\Public\NewsletterController;
use App\Http\Controllers\Public\ErrorController;
use App\Http\Controllers\Public\BookingController;
use App\Http\Controllers\FacilitySite\SiteController;
use App\Http\Controllers\Admin\AdminPlanLotController;

// Public Routes - لا تحتاج تسجيل دخول
Route::group([], function () {

    // Home Page
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/about', [HomeController::class, 'about'])->name('about');
    Route::get('/services', [HomeController::class, 'services'])->name('services');
    Route::get('/team', [HomeController::class, 'team'])->name('team');
    Route::get('/faq', [HomeController::class, 'faq'])->name('faq');
    Route::get('/sitemap', [HomeController::class, 'sitemap'])->name('sitemap');

    // Investment Properties Page (feature-flagged)
    if (config('features.public_investment_properties')) {
        Route::get('/investment-properties', [HomeController::class, 'investmentProperties'])
            ->name('investment-properties');
    }

    // Search Routes
    Route::get('/search', [SearchController::class, 'index'])->name('search');
    Route::get('/search/products', [SearchController::class, 'products'])->name('search.products');
    Route::get('/search/facilities', [SearchController::class, 'facilities'])->name('search.facilities');
    Route::get('/search/advanced', [SearchController::class, 'advanced'])->name('search.advanced');
    Route::get('/search/map', [SearchController::class, 'map'])->name('search.map');
    Route::get('/search/quick', [SearchController::class, 'quickSearch'])->name('search.quick');

    Route::get('/plans/ajlan', [SearchController::class, 'ajlanPlan'])
        ->name('plans.ajlan');

    Route::get('/plans/ajlan/osm-roads', [SearchController::class, 'ajlanOsmRoads'])
        ->name('plans.ajlan.osm_roads');

    Route::get('/plans/ajlan/lots/{lot}', [SearchController::class, 'ajlanLotShow'])
        ->name('plans.ajlan.lots.show');

    Route::view('/plans/ajlan/auto-detected-parcels', 'public.plans.plans')
        ->name('plans.ajlan.auto_detected_parcels');

    Route::prefix('/plans/{slug}')->group(function () {
        Route::get('/lots-manage', [AdminPlanLotController::class, 'publicIndex']);
        Route::post('/lots-manage/import', [AdminPlanLotController::class, 'import']);
        Route::post('/lots-manage/import-from-extraction', [AdminPlanLotController::class, 'importFromExtractionView']);
        Route::post('/lots-manage/{lot}', [AdminPlanLotController::class, 'update']);
    });

    // Contact Routes
    Route::get('/contact', [ContactController::class, 'index'])->name('contact');
    Route::post('/contact', [ContactController::class, 'sendMessage'])->name('contact.send');
    // Alias name expected by landing template
    Route::post('/contact-home', [ContactController::class, 'sendMessage'])->name('contact.home.store');
    Route::get('/contact/quote', [ContactController::class, 'quote'])->name('contact.quote');
    Route::post('/contact/quote', [ContactController::class, 'requestQuote'])->name('contact.quote.send');
    Route::get('/contact/feedback', [ContactController::class, 'feedback'])->name('contact.feedback');
    Route::post('/contact/feedback', [ContactController::class, 'sendFeedback'])->name('contact.feedback.send');
    Route::get('/contact/branches', [ContactController::class, 'branches'])->name('contact.branches');
    Route::get('/contact/location', [ContactController::class, 'location'])->name('contact.location');

    // Product Routes
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/featured', [ProductController::class, 'featured'])->name('products.featured');
    Route::get('/products/latest', [ProductController::class, 'latest'])->name('products.latest');
    Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');
    Route::get('/products/map', [ProductController::class, 'map'])->name('products.map');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
    Route::get('/categories/{category}/products', [ProductController::class, 'byCategory'])->name('products.by-category');
    Route::get('/facilities/{facility}/products', [ProductController::class, 'byFacility'])->name('products.by-facility');

    // Facility Routes
    Route::middleware(['facility.mode'])->group(function () {
        Route::get('/facilities', [FacilityController::class, 'index'])->name('facilities.index');
        Route::get('/facilities/featured', [FacilityController::class, 'featured'])->name('facilities.featured');
        Route::get('/facilities/search', [FacilityController::class, 'search'])->name('facilities.search');
        Route::get('/facilities/map', [FacilityController::class, 'map'])->name('facilities.map');
        // Single public facility page: redirect legacy public facility profile to the Facility Site
        Route::get('/facilities/{facility}', function ($facility) {
            return redirect()->route('public.facility.site.home', $facility);
        })->name('facilities.show');
        // Legacy public forms for appointment and quote => redirect to site contact section
        Route::get('/facilities/{facility}/appointment', function ($facility) {
            return redirect()->to(route('public.facility.site.home', $facility) . '#contact');
        })->name('facilities.appointment.form');
        Route::get('/facilities/{facility}/quote', function ($facility) {
            return redirect()->to(route('public.facility.site.home', $facility) . '#contact');
        })->name('facilities.quote.form');
        Route::get('/facility-categories/{category}/facilities', [FacilityController::class, 'byCategory'])->name('facilities.by-category');
    });

    // Category Routes
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');

    // Facility Category Routes
    Route::get('/facility-categories', [FacilityCategoryController::class, 'index'])->name('facility-categories.index');
    Route::get('/facility-categories/{category}', [FacilityCategoryController::class, 'show'])->name('facility-categories.show');

    // Feature Routes
    Route::get('/features', [FeatureController::class, 'index'])->name('features.index');
    Route::get('/features/{feature}', [FeatureController::class, 'show'])->name('features.show');

    // Cities Routes
    Route::get('/cities', [CityController::class, 'index'])->name('cities.index');
    Route::get('/cities/{city}', [CityController::class, 'show'])->name('cities.show');
    Route::get('/cities/{city}/products', [CityController::class, 'products'])->name('cities.products');
    Route::get('/cities/{city}/facilities', [CityController::class, 'facilities'])->name('cities.facilities');

    // Booking Routes (require authentication)
    Route::middleware('auth')->group(function () {
        Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
        Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
        Route::get('/bookings/{id}/success', [BookingController::class, 'success'])->name('bookings.success');
        Route::get('/bookings/{id}', [BookingController::class, 'show'])->name('bookings.show');
        Route::post('/bookings/{id}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
        Route::post('/bookings/{id}/reschedule', [BookingController::class, 'reschedule'])->name('bookings.reschedule');
    });

    // User Actions (require authentication)
    Route::middleware('auth')->group(function () {

        // Product Actions
        Route::post('/products/{product}/comment', [ProductController::class, 'addComment'])->name('products.comment');
        Route::post('/products/{product}/favorite', [ProductController::class, 'addToFavorites'])->name('products.favorite.add');
        Route::delete('/products/{product}/favorite', [ProductController::class, 'removeFromFavorites'])->name('products.favorite.remove');

        // Facility Actions
        Route::post('/facilities/{facility}/rate', [FacilityController::class, 'rate'])->name('facilities.rate');
        Route::post('/facilities/{facility}/favorite', [FacilityController::class, 'addToFavorites'])->name('facilities.favorite.add');
        Route::delete('/facilities/{facility}/favorite', [FacilityController::class, 'removeFromFavorites'])->name('facilities.favorite.remove');
        Route::post('/facilities/{facility}/quote', [FacilityController::class, 'requestQuote'])->name('facilities.quote');
        Route::post('/facilities/{facility}/appointment', [FacilityController::class, 'bookAppointment'])->name('facilities.appointment');
    });

    // Language Routes
    Route::get('/language/{locale}', [LanguageController::class, 'change'])->name('language.change');

    // Sitemap Routes
    Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap.xml');
    Route::get('/sitemap-products.xml', [SitemapController::class, 'products'])->name('sitemap.products');
    Route::get('/sitemap-facilities.xml', [SitemapController::class, 'facilities'])->name('sitemap.facilities');
    Route::get('/sitemap-categories.xml', [SitemapController::class, 'categories'])->name('sitemap.categories');

    // RSS Feeds
    Route::get('/rss/products', [RssController::class, 'products'])->name('rss.products');
    Route::get('/rss/facilities', [RssController::class, 'facilities'])->name('rss.facilities');
    Route::get('/rss/news', [RssController::class, 'news'])->name('rss.news');

    // Static Pages
    Route::get('/how-it-works', [StaticController::class, 'howItWorks'])->name('how-it-works');
    Route::get('/suppliers', [StaticController::class, 'suppliers'])->name('suppliers');
    Route::get('/factories', [StaticController::class, 'factories'])->name('factories');
    Route::get('/pricing', [StaticController::class, 'pricing'])->name('pricing');
    Route::get('/testimonials', [StaticController::class, 'testimonials'])->name('testimonials');
    Route::get('/blog', [StaticController::class, 'blog'])->name('blog');
    Route::get('/blog/{post}', [StaticController::class, 'blogPost'])->name('blog.post');
    Route::get('/news', [StaticController::class, 'news'])->name('news');
    Route::get('/news/{article}', [StaticController::class, 'newsArticle'])->name('news.article');
    Route::get('/careers', [StaticController::class, 'careers'])->name('careers');
    Route::get('/careers/{job}', [StaticController::class, 'jobDetails'])->name('careers.job');
    Route::post('/careers/{job}/apply', [StaticController::class, 'applyForJob'])->name('careers.apply');
    
    // Footer Pages
    Route::get('/terms', [StaticController::class, 'terms'])->name('terms');
    Route::get('/privacy', [StaticController::class, 'privacy'])->name('privacy');
    Route::get('/cookies', [StaticController::class, 'cookies'])->name('cookies');
    Route::get('/advertising', [StaticController::class, 'advertising'])->name('advertising');

    // Newsletter
    Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');
    Route::post('/newsletter/unsubscribe', [NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');

    // Public AI land study (no auth)
    Route::get('/investment-studies', [LandStudyController::class, 'form'])->name('public.investment-studies.form');
    Route::post('/investment-studies', [LandStudyController::class, 'submit'])->name('public.investment-studies.submit');

    // Error Pages
    Route::get('/404', [ErrorController::class, 'notFound'])->name('404');
    Route::get('/500', [ErrorController::class, 'serverError'])->name('500');
    Route::get('/maintenance', [ErrorController::class, 'maintenance'])->name('maintenance');

    // Facility public site (initial integration)
    Route::get('/site/{facility}', [SiteController::class, 'home'])->name('facility.site.home');
    Route::prefix('/site/{facility}')
        ->name('facility.site.')
        ->group(function(){
            Route::get('/services', [\App\Http\Controllers\FacilitySite\ServiceController::class, 'index'])->name('services.index');
            Route::get('/services/{slug}', [\App\Http\Controllers\FacilitySite\ServiceController::class, 'show'])->name('services.show');
            Route::get('/projects', [\App\Http\Controllers\FacilitySite\ProjectController::class, 'index'])->name('projects.index');
            Route::get('/projects/{slug}', [\App\Http\Controllers\FacilitySite\ProjectController::class, 'show'])->name('projects.show');
            Route::get('/partners', [\App\Http\Controllers\FacilitySite\PartnerController::class, 'index'])->name('partners.index');
            Route::get('/partners/{slug}', [\App\Http\Controllers\FacilitySite\PartnerController::class, 'show'])->name('partners.show');
            Route::get('/faqs', [\App\Http\Controllers\FacilitySite\FaqController::class, 'index'])->name('faqs.index');
            Route::get('/gallery', [\App\Http\Controllers\FacilitySite\GalleryController::class, 'index'])->name('gallery.index');
            Route::get('/gallery/{slug}', [\App\Http\Controllers\FacilitySite\GalleryController::class, 'show'])->name('gallery.show');
            Route::get('/page/{slug}', [\App\Http\Controllers\FacilitySite\PageController::class, 'show'])->name('pages.show');
            // Landing contact form (compatibility with touralbina template)
            Route::post('/contact', [\App\Http\Controllers\Public\ContactController::class, 'sendMessage'])->name('contact.home.store');
            // Tenders (example + pdf)
            Route::get('/tenders/example', [\App\Http\Controllers\FacilitySite\TenderController::class, 'example'])->name('tenders.example');
            Route::get('/tenders/{tender}/pdf/preview', [\App\Http\Controllers\FacilitySite\TenderController::class, 'previewPdf'])->name('tenders.pdf.preview');
            Route::get('/tenders/{tender}/pdf/download', [\App\Http\Controllers\FacilitySite\TenderController::class, 'downloadPdf'])->name('tenders.pdf.download');
        });

    // Dynamic Routes - This should be the LAST route to catch any remaining public.* routes
    Route::get('/{slug}', function ($slug) {
        return \App\Services\DynamicRouteService::handleRoute('public.' . $slug);
    })->name('dynamic.public')->where('slug', '.*');
});
