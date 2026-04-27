<?php

namespace App\Http\Controllers\Admin\FacilitySite;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\FacilitySetting;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class SettingAdminController extends Controller
{
    public function edit(Request $request, Facility $facility)
    {
        $setting = $facility->setting ?: new FacilitySetting(['facility_id' => $facility->id]);
        return view('admin.facility_site.settings.edit', [
            'facility' => $facility,
            'setting' => $setting,
        ]);
    }

    public function update(Request $request, Facility $facility)
    {
        $data = $request->validate([
            // Facility basics
            'name' => ['nullable','string','max:255'],
            'phone' => ['nullable','string','max:255'],
            'email' => ['nullable','email','max:255'],
            'website' => ['nullable','string','max:255'],
            'primary_color' => ['nullable','string','max:50'],
            'secondary_color' => ['nullable','string','max:50'],
            'logo' => ['nullable','image','max:2048'],
            'favicon' => ['nullable','image','max:1024'],
            // SEO
            'seo_title' => ['nullable','string','max:255'],
            'seo_description' => ['nullable','string'],
            // Social links
            'facebook' => ['nullable','string','max:255'],
            'twitter' => ['nullable','string','max:255'],
            'instagram' => ['nullable','string','max:255'],
            'linkedin' => ['nullable','string','max:255'],
            'whatsapp' => ['nullable','string','max:255'],
            // Landing options
            'hero_image' => ['nullable','image','max:4096'],
            'cta_title' => ['nullable','string','max:255'],
            'cta_subtitle' => ['nullable','string','max:500'],
            'show_kpis' => ['nullable','boolean'],
            'show_clients' => ['nullable','boolean'],
            'show_services' => ['nullable','boolean'],
            'show_contact' => ['nullable','boolean'],
        ]);

        // Update Facility basics
        $facility->fill(Arr::only($data, ['name','phone','email','website','primary_color','secondary_color']));
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('facility/logo', 'public');
            $facility->logo_path = $path;
        }
        $facility->save();

        // Settings record
        $setting = $facility->setting ?: new FacilitySetting(['facility_id' => $facility->id]);
        $setting->seo_title = $data['seo_title'] ?? $setting->seo_title;
        $setting->seo_description = $data['seo_description'] ?? $setting->seo_description;
        $setting->primary_color = $data['primary_color'] ?? $setting->primary_color;
        $setting->secondary_color = $data['secondary_color'] ?? $setting->secondary_color;
        if ($request->hasFile('favicon')) {
            $setting->favicon_path = $request->file('favicon')->store('facility/favicon', 'public');
        }
        // Social links as JSON
        $setting->social_links = [
            'facebook' => $data['facebook'] ?? Arr::get($setting->social_links, 'facebook'),
            'twitter' => $data['twitter'] ?? Arr::get($setting->social_links, 'twitter'),
            'instagram' => $data['instagram'] ?? Arr::get($setting->social_links, 'instagram'),
            'linkedin' => $data['linkedin'] ?? Arr::get($setting->social_links, 'linkedin'),
            'whatsapp' => $data['whatsapp'] ?? Arr::get($setting->social_links, 'whatsapp'),
        ];
        // Options JSON (landing toggles, hero, cta)
        $options = $setting->options ?? [];
        if ($request->hasFile('hero_image')) {
            $options['hero_image'] = $request->file('hero_image')->store('facility/hero', 'public');
        }
        $options['cta_title'] = $data['cta_title'] ?? Arr::get($options, 'cta_title');
        $options['cta_subtitle'] = $data['cta_subtitle'] ?? Arr::get($options, 'cta_subtitle');
        $options['show_kpis'] = (bool)($data['show_kpis'] ?? Arr::get($options, 'show_kpis', true));
        $options['show_clients'] = (bool)($data['show_clients'] ?? Arr::get($options, 'show_clients', true));
        $options['show_services'] = (bool)($data['show_services'] ?? Arr::get($options, 'show_services', true));
        $options['show_contact'] = (bool)($data['show_contact'] ?? Arr::get($options, 'show_contact', true));
        $setting->options = $options;

        // Keep logo in settings as well for landing compatibility if needed
        if (!$setting->logo_path && $facility->getRawOriginal('logo_path')) {
            $setting->logo_path = $facility->getRawOriginal('logo_path');
        }

        $setting->save();

        return back()->with('status', 'تم حفظ الإعدادات بنجاح');
    }
}
