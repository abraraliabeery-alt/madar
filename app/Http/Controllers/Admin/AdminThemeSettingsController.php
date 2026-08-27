<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class AdminThemeSettingsController extends Controller
{
    public function edit()
    {
        $settings = $this->loadSettings();

        return view('admin.theme.settings', [
            'light' => $settings['light'] ?? $this->defaultLight(),
            'dark' => $settings['dark'] ?? $this->defaultDark(),
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'light.brand_brown' => 'required|string|max:7',
            'light.brand_bg' => 'required|string|max:7',
            'light.brand_fg' => 'required|string|max:7',
            'light.brand_border' => 'required|string|max:7',
            'light.brand_muted' => 'required|string|max:7',
            'dark.brand_brown' => 'required|string|max:7',
            'dark.brand_bg' => 'required|string|max:7',
            'dark.brand_fg' => 'required|string|max:7',
            'dark.brand_border' => 'required|string|max:7',
            'dark.brand_muted' => 'required|string|max:7',
        ]);

        $settings = [
            'light' => $validated['light'],
            'dark' => $validated['dark'],
        ];

        Setting::setValue('platform.theme.settings', json_encode($settings));

        $this->generateThemeCss($settings);

        return redirect()->back()->with('success', 'تم حفظ إعدادات الهوية بنجاح');
    }

    private function loadSettings(): array
    {
        $json = Setting::getValue('platform.theme.settings', null);

        if (! $json) {
            return [];
        }

        return json_decode($json, true) ?? [];
    }

    private function defaultLight(): array
    {
        return [
            'brand_brown' => '#126b61',
            'brand_bg' => '#f6f8f8',
            'brand_fg' => '#172524',
            'brand_border' => 'rgba(18,107,97,.18)',
            'brand_muted' => '#647472',
        ];
    }

    private function defaultDark(): array
    {
        return [
            'brand_brown' => '#55c8b8',
            'brand_bg' => '#0d1717',
            'brand_fg' => '#edf7f5',
            'brand_border' => 'rgba(85,200,184,.22)',
            'brand_muted' => '#9bb0ad',
        ];
    }

    private function generateThemeCss(array $settings): void
    {
        $light = $settings['light'] ?? $this->defaultLight();
        $dark = $settings['dark'] ?? $this->defaultDark();

        $lightRgb = $this->hexToRgb($light['brand_brown']);
        $darkRgb = $this->hexToRgb($dark['brand_brown']);

        $css = ":root{
  --brand-brown:{$dark['brand_brown']};
  --brand-brown-rgb:{$darkRgb};
  --brand-bg:{$dark['brand_bg']};
  --brand-fg:{$dark['brand_fg']};
  --brand-border:{$dark['brand_border']};
  --brand-muted:{$dark['brand_muted']};
  --brand-shadow:0 .25rem 1rem rgba(0,0,0,.35);
}

html[data-theme=\"light\"]{
  --brand-brown:{$light['brand_brown']};
  --brand-brown-rgb:{$lightRgb};
  --brand-bg:{$light['brand_bg']};
  --brand-fg:{$light['brand_fg']};
  --brand-border:{$light['brand_border']};
  --brand-muted:{$light['brand_muted']};
  --brand-shadow:0 .25rem 1rem rgba({$lightRgb},.09);
}";

        file_put_contents(public_path('theme.css'), $css);
    }

    private function hexToRgb(string $hex): string
    {
        $hex = ltrim($hex, '#');

        if (strlen($hex) === 3) {
            $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        }

        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        return "{$r},{$g},{$b}";
    }
}
