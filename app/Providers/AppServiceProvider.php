<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\Services\LanguageService;
use App\Helpers\LanguageHelper;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(LanguageService::class, function ($app) {
            return new LanguageService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register Blade directives for language helpers
        Blade::directive('lang', function ($expression) {
            return "<?php echo App\Helpers\LanguageHelper::trans($expression); ?>";
        });

        Blade::directive('langChoice', function ($expression) {
            return "<?php echo App\Helpers\LanguageHelper::transChoice($expression); ?>";
        });

        Blade::directive('isRTL', function () {
            return "<?php echo App\Helpers\LanguageHelper::isRTL() ? 'rtl' : 'ltr'; ?>";
        });

        Blade::directive('direction', function () {
            return "<?php echo App\Helpers\LanguageHelper::getDirection(); ?>";
        });

        Blade::directive('textAlign', function ($side = 'left') {
            return "<?php echo App\Helpers\LanguageHelper::getTextAlign(); ?>";
        });

        Blade::directive('float', function ($side = 'left') {
            return "<?php echo App\Helpers\LanguageHelper::getFloat($side); ?>";
        });

        Blade::directive('margin', function ($side = 'left') {
            return "<?php echo App\Helpers\LanguageHelper::getMargin($side); ?>";
        });

        Blade::directive('padding', function ($side = 'left') {
            return "<?php echo App\Helpers\LanguageHelper::getPadding($side); ?>";
        });

        Blade::directive('border', function ($side = 'left') {
            return "<?php echo App\Helpers\LanguageHelper::getBorder($side); ?>";
        });

        Blade::directive('position', function ($side = 'left') {
            return "<?php echo App\Helpers\LanguageHelper::getPosition($side); ?>";
        });

        Blade::directive('transform', function ($direction = 'left') {
            return "<?php echo App\Helpers\LanguageHelper::getTransform($direction); ?>";
        });

        Blade::directive('flexDirection', function () {
            return "<?php echo App\Helpers\LanguageHelper::getFlexDirection(); ?>";
        });

        Blade::directive('justifyContent', function ($side = 'start') {
            return "<?php echo App\Helpers\LanguageHelper::getJustifyContent($side); ?>";
        });

        Blade::directive('alignItems', function ($side = 'start') {
            return "<?php echo App\Helpers\LanguageHelper::getAlignItems($side); ?>";
        });

        Blade::directive('order', function ($position = 'first') {
            return "<?php echo App\Helpers\LanguageHelper::getOrder($position); ?>";
        });

        // Share language data with all views
        view()->composer('*', function ($view) {
            $languageService = app(LanguageService::class);
            $view->with('currentLanguage', $languageService->getCurrentLanguage());
            $view->with('currentLanguageData', $languageService->getCurrentLanguageData());
            $view->with('isRTL', $languageService->isRTL());
            $view->with('languageSwitcher', $languageService->getLanguageSwitcherData());
        });
    }
}
