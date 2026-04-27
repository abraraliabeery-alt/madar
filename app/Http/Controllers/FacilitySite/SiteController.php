<?php

namespace App\Http\Controllers\FacilitySite;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\FacilityProject;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SiteController extends Controller
{
    public function home(Request $request, $facility)
    {
        $facilityModel = Facility::query()
            ->where(function($q) use ($facility) {
                $q->where('slug', $facility)
                  ->orWhere('id', $facility)
                  ->orWhere('name', $facility)
                  ->orWhere('email', $facility);
            })
            ->where('is_active', true)
            ->firstOrFail();

        // Merge FacilitySetting if exists (fallback only)
        $setting = $facilityModel->setting;

        $brandColor = $facilityModel->primary_color
            ?? $setting?->primary_color
            ?? '#2563eb';

        $logoCandidate = $facilityModel->logo_path
            ?? $setting?->logo_path
            ?? $facilityModel->getRawOriginal('logo_path');

        $logoPath = null;
        if ($logoCandidate) {
            $logoPath = (string) $logoCandidate;
            if (!Str::startsWith($logoPath, ['http://', 'https://', '/'])) {
                $logoPath = Str::startsWith($logoPath, 'storage/') ? $logoPath : ('storage/' . ltrim($logoPath, '/'));
            }
        }

        config([
            'brand.color' => $brandColor,
            'brand.name' => $facilityModel->name,
            'brand.logo_path' => $logoPath,
            'brand.website' => $facilityModel->website,
            'brand.email' => $facilityModel->email,
            'brand.phone' => $facilityModel->phone,
            'brand.customization' => [
                'primary_color' => $facilityModel->primary_color ?? $setting?->primary_color,
                'secondary_color' => $facilityModel->secondary_color,
                'accent_color' => $facilityModel->accent_color,
                'background_color' => $facilityModel->background_color,
                'text_color' => $facilityModel->text_color,
                'secondary_text_color' => $facilityModel->secondary_text_color,
                'font_family' => $facilityModel->font_family,
                'layout_style' => $facilityModel->layout_style,
                'button_style' => $facilityModel->button_style,
                'hero_background_type' => $facilityModel->hero_background_type,
                'hero_background_value' => $facilityModel->hero_background_value,
                'hero_overlay_opacity' => $facilityModel->hero_overlay_opacity,
                'enable_animations' => $facilityModel->enable_animations,
                'enable_parallax' => $facilityModel->enable_parallax,
                'custom_css' => $facilityModel->custom_css,
            ],
        ]);

        $products = Product::query()
            ->where('facility_id', $facilityModel->id)
            ->with('translations')
            ->latest()
            ->take(12)
            ->get();

        $projects = FacilityProject::query()
            ->where('facility_id', $facilityModel->id)
            ->published()
            ->orderBy('order')
            ->orderBy('id')
            ->take(8)
            ->get();

        return view('facility_site.landing', [
            'facility' => $facilityModel,
            'products' => $products,
            'projects' => $projects,
        ]);
    }
}
