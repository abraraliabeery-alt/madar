<?php

namespace App\Helpers;

use App\Models\Facility;

class FacilityHelper
{
    /**
     * Get the current facility mode
     */
    public static function getMode(): string
    {
        return config('app.facility_mode', 'multi');
    }

    /**
     * Check if application is in single facility mode
     */
    public static function isSingleMode(): bool
    {
        return self::getMode() === 'single';
    }

    /**
     * Check if application is in multi facility mode
     */
    public static function isMultiMode(): bool
    {
        return self::getMode() === 'multi';
    }

    /**
     * Get the single facility for single mode
     */
    public static function getSingleFacility(): ?Facility
    {
        if (!self::isSingleMode()) {
            return null;
        }

        return Facility::find(config('app.single_facility_id'));
    }

    /**
     * Get facility ID based on mode
     */
    public static function getFacilityId($facilityId = null): ?int
    {
        if (self::isSingleMode()) {
            return config('app.single_facility_id');
        }

        return $facilityId;
    }

    /**
     * Lighten a hex color by percentage
     */
    public static function lightenColor($hexColor, $percent): string
    {
        // Remove # if present
        $hex = ltrim($hexColor, '#');
        
        // Convert to RGB
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        
        // Calculate lighter values
        $r = min(255, $r + (255 - $r) * ($percent / 100));
        $g = min(255, $g + (255 - $g) * ($percent / 100));
        $b = min(255, $b + (255 - $b) * ($percent / 100));
        
        // Convert back to hex
        return '#' . sprintf('%02x%02x%02x', round($r), round($g), round($b));
    }

    /**
     * Darken a hex color by percentage
     */
    public static function darkenColor($hexColor, $percent): string
    {
        // Remove # if present
        $hex = ltrim($hexColor, '#');
        
        // Convert to RGB
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        
        // Calculate darker values
        $r = max(0, $r - $r * ($percent / 100));
        $g = max(0, $g - $g * ($percent / 100));
        $b = max(0, $b - $b * ($percent / 100));
        
        // Convert back to hex
        return '#' . sprintf('%02x%02x%02x', round($r), round($g), round($b));
    }
}
