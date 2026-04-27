<?php

namespace App\Services\Maps;

use Illuminate\Support\Facades\Http;

class GoogleMapsService
{
    public function reverseGeocode(float $lat, float $lng): ?array
    {
        $apiKey = config('services.google_maps.api_key');
        if (!$apiKey) {
            return null;
        }

        try {
            $response = Http::get('https://maps.googleapis.com/maps/api/geocode/json', [
                'latlng'   => $lat . ',' . $lng,
                'key'      => $apiKey,
                'language' => 'ar',
            ])->throw()->json();
        } catch (\Throwable $e) {
            return null;
        }

        if (($response['status'] ?? '') !== 'OK') {
            return null;
        }

        $result = $response['results'][0] ?? null;
        if (!$result) {
            return null;
        }

        return [
            'formatted_address' => $result['formatted_address'] ?? null,
            'raw'               => $result,
        ];
    }

    public function extractLatLngFromUrl(string $url): ?array
    {
        // نمط يحتوي على @lat,lng مثل: https://www.google.com/maps/@24.774265,46.738586,15z
        if (preg_match('~@(-?\d+\.\d+),(-?\d+\.\d+)~', $url, $m)) {
            return ['lat' => (float) $m[1], 'lng' => (float) $m[2]];
        }

        // نمط q=lat,lng
        if (preg_match('~[?&]q=(-?\d+\.\d+),(-?\d+\.\d+)~', $url, $m)) {
            return ['lat' => (float) $m[1], 'lng' => (float) $m[2]];
        }

        return null;
    }
}
