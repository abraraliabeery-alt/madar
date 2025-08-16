# Language System Setup and Usage

This document explains how to use the multi-language system implemented in your Laravel application.

## Overview

The language system supports:
- **English (en)** - Default language, LTR
- **Arabic (ar)** - RTL language support
- Automatic language detection from URL, session, and cache
- Language switching with URL preservation
- RTL/LTR layout support
- Translation fallbacks

## Files Structure

```
resources/lang/
├── en/                    # English translations
│   ├── validation.php     # Validation messages
│   ├── auth.php          # Authentication messages
│   ├── pagination.php    # Pagination messages
│   ├── passwords.php     # Password-related messages
│   ├── admin.php         # Admin panel messages
│   ├── facility.php      # Facility panel messages
│   ├── client.php        # Client panel messages
│   └── public.php        # Public-facing messages
└── ar/                    # Arabic translations
    ├── validation.php     # Arabic validation messages
    ├── auth.php          # Arabic authentication messages
    ├── pagination.php    # Arabic pagination messages
    ├── passwords.php     # Arabic password messages
    └── public.php        # Arabic public messages

app/
├── Services/
│   └── LanguageService.php    # Core language service
├── Http/
│   ├── Controllers/
│   │   └── LanguageController.php  # Language switching controller
│   └── Middleware/
│       └── SetLocale.php           # Locale setting middleware
└── Helpers/
    └── LanguageHelper.php          # Language helper functions

resources/views/
└── components/
    └── language-switcher.blade.php # Language switcher component
```

## Configuration

### 1. Environment Variables

Add these to your `.env` file:

```env
APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US
```

### 2. Middleware Registration

The `SetLocale` middleware is automatically applied to all routes through the `AppServiceProvider`.

## Usage

### 1. Basic Translation

```php
// In controllers
use App\Helpers\LanguageHelper;

$message = LanguageHelper::trans('auth.login.title');
$message = LanguageHelper::trans('validation.required', ['attribute' => 'email']);
```

### 2. Blade Templates

```blade
{{-- Basic translation --}}
@lang('public.home.title')

{{-- With parameters --}}
@lang('validation.required', ['attribute' => 'email'])

{{-- Choice translation --}}
@langChoice('pagination.results', $total)

{{-- Language switcher component --}}
<x-language-switcher />

{{-- Inline language switcher --}}
<x-language-switcher :dropdown="false" />

{{-- Custom styling --}}
<x-language-switcher class="my-custom-class" :show-flags="false" />
```

### 3. Blade Directives

```blade
{{-- RTL/LTR support --}}
<html dir="@direction">

{{-- Text alignment --}}
<div class="@textAlign">Content</div>

{{-- Float positioning --}}
<div class="@float('left')">Left content</div>

{{-- Margins and padding --}}
<div class="@margin('left')">Content</div>
<div class="@padding('right')">Content</div>

{{-- Flexbox utilities --}}
<div class="@flexDirection">Content</div>
<div class="@justifyContent('start')">Content</div>
```

### 4. Controllers

```php
use App\Services\LanguageService;

class YourController extends Controller
{
    protected $languageService;

    public function __construct(LanguageService $languageService)
    {
        $this->languageService = $languageService;
    }

    public function index()
    {
        $currentLanguage = $this->languageService->getCurrentLanguage();
        $isRTL = $this->languageService->isRTL();
        
        return view('your-view', compact('currentLanguage', 'isRTL'));
    }
}
```

### 5. Routes

```php
// Language switching route
Route::get('/language/{language}', [LanguageController::class, 'switch'])
    ->name('language.switch');

// Language info API
Route::get('/language-info', [LanguageController::class, 'info'])
    ->name('language.info');
```

## Language Switching

### 1. URL Structure

- **English (default)**: `/your-page`
- **Arabic**: `/ar/your-page`

### 2. Language Switcher

The language switcher automatically:
- Detects current language
- Generates correct URLs for each language
- Preserves current page when switching
- Shows flags and language names
- Supports dropdown and inline layouts

### 3. JavaScript Integration

```javascript
// Switch language via AJAX
function switchLanguage(language) {
    fetch(`/language/${language}`)
        .then(response => {
            if (response.redirected) {
                window.location.href = response.url;
            }
        });
}

// Get language info
fetch('/language-info')
    .then(response => response.json())
    .then(data => {
        console.log('Current language:', data.current);
        console.log('Is RTL:', data.isRTL);
    });
```

## RTL Support

### 1. CSS Classes

The system provides utility classes for RTL support:

```css
/* Text alignment */
.text-right-rtl { text-align: right; }
.text-left-rtl { text-align: left; }

/* Float positioning */
.float-right-rtl { float: right; }
.float-left-rtl { float: left; }

/* Margins and padding */
.me-auto-rtl { margin-left: auto; }
.ms-auto-rtl { margin-right: auto; }
```

### 2. Layout Considerations

```blade
<html dir="@direction">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@lang('public.home.title')</title>
    
    {{-- RTL CSS for Arabic --}}
    @if($isRTL)
        <link rel="stylesheet" href="{{ asset('css/rtl.css') }}">
    @endif
</head>
<body>
    <div class="container @textAlign">
        {{-- Your content --}}
    </div>
</body>
</html>
```

## Adding New Languages

### 1. Create Language Files

```php
// resources/lang/fr/validation.php
return [
    'required' => 'Le champ :attribute est obligatoire.',
    'email' => 'Le champ :attribute doit être une adresse email valide.',
    // ... more translations
];
```

### 2. Update Language Service

```php
// app/Services/LanguageService.php
protected $availableLanguages = [
    'en' => [
        'name' => 'English',
        'native' => 'English',
        'flag' => '🇺🇸',
        'rtl' => false,
    ],
    'ar' => [
        'name' => 'Arabic',
        'native' => 'العربية',
        'flag' => '🇸🇦',
        'rtl' => true,
    ],
    'fr' => [
        'name' => 'French',
        'native' => 'Français',
        'flag' => '🇫🇷',
        'rtl' => false,
    ],
];
```

## Best Practices

### 1. Translation Keys

- Use descriptive, hierarchical keys: `admin.users.create.title`
- Keep keys consistent across languages
- Use parameters for dynamic content: `validation.required`

### 2. Fallbacks

- Always provide fallback translations in the default language
- Use the `LanguageHelper::trans()` method for automatic fallbacks
- Test with missing translations

### 3. RTL Layout

- Test layouts in both LTR and RTL modes
- Use the provided utility classes for positioning
- Consider text direction in your designs

### 4. Performance

- Language data is cached for performance
- Use view composers sparingly
- Consider lazy loading for large translation files

## Troubleshooting

### 1. Language Not Switching

- Check if the `SetLocale` middleware is applied
- Verify language codes in `LanguageService`
- Check browser cache and cookies

### 2. RTL Not Working

- Ensure `isRTL()` method returns correct value
- Check if RTL CSS is loaded
- Verify HTML `dir` attribute is set

### 3. Missing Translations

- Check if translation files exist
- Verify translation keys match exactly
- Use fallback locale for missing translations

### 4. URL Issues

- Check route definitions
- Verify language prefix handling
- Test with different URL structures

## Examples

### 1. Complete View Example

```blade
@extends('layouts.app')

@section('content')
<div class="container @textAlign">
    <h1>@lang('public.home.title')</h1>
    <p>@lang('public.home.subtitle')</p>
    
    <div class="row @justifyContent('center')">
        <div class="col-md-6">
            <div class="card @margin('left')">
                <div class="card-body">
                    <h5 class="card-title">@lang('public.facilities.title')</h5>
                    <p class="card-text">@lang('public.facilities.description')</p>
                    <a href="@lang('public.facilities.search')" class="btn btn-primary">
                        @lang('public.facilities.search')
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Language switcher --}}
    <div class="mt-4">
        <x-language-switcher />
    </div>
</div>
@endsection
```

### 2. Controller Example

```php
class HomeController extends Controller
{
    public function index()
    {
        $data = [
            'title' => __('public.home.title'),
            'subtitle' => __('public.home.subtitle'),
            'isRTL' => app(LanguageHelper::class)->isRTL(),
            'currentLanguage' => app(LanguageHelper::class)->getCurrentLanguage(),
        ];
        
        return view('home', $data);
    }
}
```

This language system provides a robust foundation for multi-language applications with comprehensive RTL support and easy-to-use utilities.
