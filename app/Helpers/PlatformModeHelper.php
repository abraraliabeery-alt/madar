<?php

namespace App\Helpers;

use App\Models\Setting;

class PlatformModeHelper
{
    public const MODE_REAL_ESTATE = 'real_estate';
    public const MODE_CONTRACTING = 'contracting';
    public const MODE_LIFECYCLE = 'lifecycle';

    public static function getMode(): string
    {
        $mode = (string) Setting::getValue('platform_mode', static::MODE_LIFECYCLE);

        if (!in_array($mode, [static::MODE_REAL_ESTATE, static::MODE_CONTRACTING, static::MODE_LIFECYCLE], true)) {
            return static::MODE_LIFECYCLE;
        }

        return $mode;
    }

    public static function isRealEstateOnly(): bool
    {
        return static::getMode() === static::MODE_REAL_ESTATE;
    }

    public static function isContractingOnly(): bool
    {
        return static::getMode() === static::MODE_CONTRACTING;
    }

    public static function isLifecycle(): bool
    {
        return static::getMode() === static::MODE_LIFECYCLE;
    }

    public static function allowsRealEstate(): bool
    {
        return in_array(static::getMode(), [static::MODE_REAL_ESTATE, static::MODE_LIFECYCLE], true);
    }

    public static function allowsContracting(): bool
    {
        return in_array(static::getMode(), [static::MODE_CONTRACTING, static::MODE_LIFECYCLE], true);
    }
}
