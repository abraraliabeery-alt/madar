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
}
