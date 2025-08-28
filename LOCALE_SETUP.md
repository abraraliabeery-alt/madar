# Locale Setup for Multi-Language Support

This document explains how to set up multi-language support for the categories system.

## Environment Configuration

Add the following variables to your `.env` file:

```env
# Locale Configuration
APP_LOCALE=ar
APP_FALLBACK_LOCALE=ar
APP_FAKER_LOCALE=ar_SA

# Available Locales (comma-separated)
AVAILABLE_LOCALES=ar,en
```

## Available Locales

The system currently supports the following locales:

- **Arabic (ar)**: العربية - Default locale
- **English (en)**: English

## Configuration Files

### 1. Locales Configuration (`config/locales.php`)

This file defines all available locales with their properties:

```php
'available' => [
    'ar' => [
        'name' => 'العربية',
        'flag' => '🇸🇦',
        'direction' => 'rtl',
        'native_name' => 'العربية'
    ],
    'en' => [
        'name' => 'English',
        'flag' => '🇺🇸',
        'direction' => 'ltr',
        'native_name' => 'English'
    ],
],
```

### 2. Database Migration

The `category_translations` table stores translations:

```sql
CREATE TABLE category_translations (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id BIGINT UNSIGNED NOT NULL,
    locale VARCHAR(255) NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
    UNIQUE KEY unique_category_locale (category_id, locale)
);
```

## Features

### 1. Locale Tabs in Admin Panel

- **Create Form**: Tabs for each locale with name and description fields
- **Edit Form**: Pre-filled with existing translations
- **Show View**: Displays all available translations

### 2. Translation Management

- Create categories with multiple language support
- Edit existing translations
- View all translations for each category
- Fallback to default locale if translation is missing

### 3. Validation

- At least one translation name is required
- Locale validation against available locales
- Proper error handling for each locale

## Usage Examples

### Creating a Category with Translations

```php
// In AdminCategoryController
$category = Category::create([
    'parent_id' => null,
    'is_active' => true,
    'is_featured' => false,
    'order' => 0,
]);

// Create translations
CategoryTranslation::create([
    'category_id' => $category->id,
    'locale' => 'ar',
    'name' => 'العقارات',
    'description' => 'جميع العقارات المتاحة',
]);

CategoryTranslation::create([
    'category_id' => $category->id,
    'locale' => 'en',
    'name' => 'Real Estate',
    'description' => 'All available real estate',
]);
```

### Retrieving Translated Content

```php
// Get name in current locale
$categoryName = $category->getTranslatedName();

// Get name in specific locale
$englishName = $category->getTranslatedName('en');

// Get description in current locale
$categoryDescription = $category->getTranslatedDescription();
```

## Adding New Locales

To add a new locale:

1. **Update `config/locales.php`**:
```php
'fr' => [
    'name' => 'Français',
    'flag' => '🇫🇷',
    'direction' => 'ltr',
    'native_name' => 'Français'
],
```

2. **Update `.env`**:
```env
AVAILABLE_LOCALES=ar,en,fr
```

3. **Update LanguageController**:
```php
$availableLocales = ['ar', 'en', 'fr'];
```

## Best Practices

1. **Always provide a fallback**: Use the default locale as fallback
2. **Validate translations**: Ensure at least one translation exists
3. **Consistent naming**: Use consistent naming conventions across locales
4. **RTL Support**: Consider right-to-left languages in your UI design
5. **Performance**: Use eager loading for translations when needed

## Troubleshooting

### Common Issues

1. **Translations not showing**: Check if the locale is in the available locales list
2. **Validation errors**: Ensure at least one translation name is provided
3. **Missing translations**: Check the fallback locale configuration

### Debug Commands

```bash
# Check current locale
php artisan tinker
>>> app()->getLocale()

# Check available locales
>>> config('locales.available')

# Check fallback locale
>>> config('locales.fallback')
```

## Future Enhancements

- [ ] Add more locales (French, Spanish, etc.)
- [ ] Implement locale-specific validation rules
- [ ] Add locale switching in admin panel
- [ ] Implement translation memory/glossary
- [ ] Add bulk translation import/export
- [ ] Implement translation workflow and approval system

