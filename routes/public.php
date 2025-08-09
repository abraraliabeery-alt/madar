<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\SearchController;
use App\Http\Controllers\Public\ContactController;
use App\Http\Controllers\Public\ProductController;
use App\Http\Controllers\Public\FacilityController;
use App\Http\Controllers\Public\CategoryController;
use App\Http\Controllers\Public\FeatureController;
use App\Http\Controllers\Public\LanguageController;
use App\Http\Controllers\Public\SitemapController;
use App\Http\Controllers\Public\RssController;
use App\Http\Controllers\Public\StaticController;
use App\Http\Controllers\Public\NewsletterController;
use App\Http\Controllers\Public\ErrorController;

// Public Routes - لا تحتاج تسجيل دخول
Route::name('public.')->group(function () {

    // Home Page
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/about', [HomeController::class, 'about'])->name('about');
    Route::get('/services', [HomeController::class, 'services'])->name('services');
    Route::get('/team', [HomeController::class, 'team'])->name('team');
    Route::get('/terms', [HomeController::class, 'terms'])->name('terms');
    Route::get('/privacy', [HomeController::class, 'privacy'])->name('privacy');
    Route::get('/faq', [HomeController::class, 'faq'])->name('faq');
    Route::get('/sitemap', [HomeController::class, 'sitemap'])->name('sitemap');

    // Search Routes
    Route::get('/search', [SearchController::class, 'index'])->name('search');
    Route::get('/search/products', [SearchController::class, 'searchProducts'])->name('search.products');
    Route::get('/search/facilities', [SearchController::class, 'searchFacilities'])->name('search.facilities');
    Route::get('/search/advanced', [SearchController::class, 'advancedSearch'])->name('search.advanced');
    Route::get('/search/map', [SearchController::class, 'mapSearch'])->name('search.map');
    Route::get('/search/quick', [SearchController::class, 'quickSearch'])->name('search.quick');

    // Contact Routes
    Route::get('/contact', [ContactController::class, 'index'])->name('contact');
    Route::post('/contact', [ContactController::class, 'sendMessage'])->name('contact.send');
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
    Route::get('/facilities', [FacilityController::class, 'index'])->name('facilities.index');
    Route::get('/facilities/featured', [FacilityController::class, 'featured'])->name('facilities.featured');
    Route::get('/facilities/search', [FacilityController::class, 'search'])->name('facilities.search');
    Route::get('/facilities/map', [FacilityController::class, 'map'])->name('facilities.map');
    Route::get('/facilities/{facility}', [FacilityController::class, 'show'])->name('facilities.show');
    // Public forms for appointment and quote
    Route::get('/facilities/{facility}/appointment', [FacilityController::class, 'appointmentForm'])->name('facilities.appointment.form');
    Route::get('/facilities/{facility}/quote', [FacilityController::class, 'quoteForm'])->name('facilities.quote.form');
    Route::get('/categories/{category}/facilities', [FacilityController::class, 'byCategory'])->name('facilities.by-category');

    // Category Routes
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');

    // Feature Routes
    Route::get('/features', [FeatureController::class, 'index'])->name('features.index');
    Route::get('/features/{feature}', [FeatureController::class, 'show'])->name('features.show');

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
    Route::get('/pricing', [StaticController::class, 'pricing'])->name('pricing');
    Route::get('/testimonials', [StaticController::class, 'testimonials'])->name('testimonials');
    Route::get('/blog', [StaticController::class, 'blog'])->name('blog');
    Route::get('/blog/{post}', [StaticController::class, 'blogPost'])->name('blog.post');
    Route::get('/news', [StaticController::class, 'news'])->name('news');
    Route::get('/news/{article}', [StaticController::class, 'newsArticle'])->name('news.article');
    Route::get('/careers', [StaticController::class, 'careers'])->name('careers');
    Route::get('/careers/{job}', [StaticController::class, 'jobDetails'])->name('careers.job');
    Route::post('/careers/{job}/apply', [StaticController::class, 'applyForJob'])->name('careers.apply');

    // Newsletter
    Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');
    Route::post('/newsletter/unsubscribe', [NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');

    // Error Pages
    Route::get('/404', [ErrorController::class, 'notFound'])->name('404');
    Route::get('/500', [ErrorController::class, 'serverError'])->name('500');
    Route::get('/maintenance', [ErrorController::class, 'maintenance'])->name('maintenance');
});
