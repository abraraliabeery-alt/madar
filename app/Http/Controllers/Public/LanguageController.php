<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class LanguageController extends Controller
{
    /**
     * Change application locale
     */
    public function change($locale)
    {
        $availableLocales = array_keys((array) config('locales.available', []));

        if (in_array($locale, $availableLocales)) {
            // Set the application locale
            App::setLocale($locale);
            
            // Store in session
            Session::put('locale', $locale);
            
            // Log the change for debugging
            Log::info('Language changed', [
                'from' => App::getLocale(),
                'to' => $locale,
                'session_locale' => Session::get('locale')
            ]);
            
            // Also set the locale in the config
            config(['app.locale' => $locale]);
        }

        return redirect()->back();
    }
}
