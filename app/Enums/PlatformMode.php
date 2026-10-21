<?php

namespace App\Enums;

enum PlatformMode: string
{
    case RealEstate = 'real_estate';
    case Contracting = 'contracting';
    case Lifecycle = 'lifecycle';

    /**
     * Arabic label for the mode.
     */
    public function label(): string
    {
        return match ($this) {
            self::RealEstate => 'عقارات',
            self::Contracting => 'مقاولات',
            self::Lifecycle => 'عقارات + مقاولات',
        };
    }

    /**
     * Which category platform_mode values are visible when the site is in this mode.
     */
    public function allowedCategoryValues(): array
    {
        return match ($this) {
            self::RealEstate => [self::RealEstate->value, self::Lifecycle->value],
            self::Contracting => [self::Contracting->value, self::Lifecycle->value],
            self::Lifecycle => [self::RealEstate->value, self::Contracting->value, self::Lifecycle->value],
        };
    }

    /**
     * Label used in the platform settings page.
     */
    public function settingsLabel(): string
    {
        return match ($this) {
            self::RealEstate => 'منصة عقارات',
            self::Contracting => 'منصة مقاولات',
            self::Lifecycle => 'دورة حياة العقار (عقارات + مقاولات)',
        };
    }

    /**
     * Try to resolve a string value into an enum, falling back to lifecycle.
     */
    public static function fromString(string $value, ?self $default = null): self
    {
        return self::tryFrom($value) ?? $default ?? self::Lifecycle;
    }

    /**
     * Get the current platform mode from settings.
     */
    public static function current(): self
    {
        $mode = (string) \App\Models\Setting::getValue('platform_mode', self::Lifecycle->value);

        return self::fromString($mode);
    }
}
