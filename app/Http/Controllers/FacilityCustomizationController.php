<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class FacilityCustomizationController extends Controller
{
    /**
     * Show the customization form for a facility
     */
    public function edit(Facility $facility)
    {
        // Check if user can customize this facility
        $this->authorize('update', $facility);
        
        $presets = $this->getColorPresets();
        $fontOptions = $this->getFontOptions();
        $layoutOptions = $this->getLayoutOptions();
        
        return view('facility.customization.edit', compact(
            'facility', 
            'presets', 
            'fontOptions', 
            'layoutOptions'
        ));
    }

    /**
     * Update facility customization settings
     */
    public function update(Request $request, Facility $facility)
    {
        $this->authorize('update', $facility);

        $validated = $request->validate([
            // Colors
            'primary_color' => ['nullable', 'regex:/^#[A-Fa-f0-9]{6}$/'],
            'secondary_color' => ['nullable', 'regex:/^#[A-Fa-f0-9]{6}$/'],
            'accent_color' => ['nullable', 'regex:/^#[A-Fa-f0-9]{6}$/'],
            'background_color' => ['nullable', 'regex:/^#[A-Fa-f0-9]{6}$/'],
            'text_color' => ['nullable', 'regex:/^#[A-Fa-f0-9]{6}$/'],
            
            // Typography
            'font_family' => ['nullable', Rule::in(['figtree', 'inter', 'poppins', 'roboto', 'open-sans', 'lato'])],
            
            // Hero Section
            'hero_background_type' => ['nullable', Rule::in(['gradient', 'color', 'image'])],
            'hero_background_value' => ['nullable', 'string', 'max:500'],
            'hero_overlay_opacity' => ['nullable', 'integer', 'min:0', 'max:100'],
            'hero_background_image' => ['nullable', 'image', 'max:5120'], // 5MB max
            
            // Layout & Design
            'layout_style' => ['nullable', Rule::in(['modern', 'classic', 'minimal', 'corporate', 'elegant', 'bold'])],
            'button_style' => ['nullable', Rule::in(['rounded', 'square', 'pill'])],
            'logo_position' => ['nullable', Rule::in(['left', 'center', 'right'])],
            'navigation_style' => ['nullable', Rule::in(['standard', 'transparent', 'boxed', 'centered'])],
            'content_layout' => ['nullable', Rule::in(['full-width', 'boxed', 'wide'])],
            'section_spacing' => ['nullable', Rule::in(['compact', 'normal', 'relaxed', 'spacious'])],
            'card_style' => ['nullable', Rule::in(['modern', 'flat', 'outlined', 'elevated'])],
            'footer_style' => ['nullable', Rule::in(['simple', 'detailed', 'minimal'])],
            
            // Effects
            'enable_animations' => ['boolean'],
            'enable_parallax' => ['boolean'],
            
            // Custom CSS
            'custom_css' => ['nullable', 'string', 'max:10000'],
            
            // Social Media
            'facebook_url' => ['nullable', 'url', 'max:255'],
            'twitter_url' => ['nullable', 'url', 'max:255'],
            'instagram_url' => ['nullable', 'url', 'max:255'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
            
            // SEO
            'meta_keywords' => ['nullable', 'string', 'max:500'],
            'meta_description' => ['nullable', 'string', 'max:160'],
        ]);

        // Handle hero background image upload
        if ($request->hasFile('hero_background_image')) {
            // Delete old hero background if it exists and is an image
            if ($facility->hero_background_type === 'image' && $facility->hero_background_value) {
                $oldImagePath = str_replace(asset('storage/'), '', $facility->hero_background_value);
                if (Storage::disk('public')->exists($oldImagePath)) {
                    Storage::disk('public')->delete($oldImagePath);
                }
            }
            
            $heroImagePath = $request->file('hero_background_image')->store('facility-customization/hero', 'public');
            $validated['hero_background_type'] = 'image';
            $validated['hero_background_value'] = asset($heroImagePath);
        }

        // Update facility with validated data
        $facility->update($validated);

        return redirect()
            ->route('facility.customization.edit', $facility)
            ->with('success', __('facilities.customization.updated_successfully'));
    }

    /**
     * Preview the customization changes
     */
    public function preview(Request $request, Facility $facility)
    {
        $this->authorize('view', $facility);
        
        // Get current customization data
        $customization = $facility->customization;
        
        // Override with preview data from request
        if ($request->filled('primary_color')) {
            $customization['colors']['primary'] = $request->primary_color;
        }
        if ($request->filled('secondary_color')) {
            $customization['colors']['secondary'] = $request->secondary_color;
        }
        if ($request->filled('accent_color')) {
            $customization['colors']['accent'] = $request->accent_color;
        }
        if ($request->filled('background_color')) {
            $customization['colors']['background'] = $request->background_color;
        }
        if ($request->filled('text_color')) {
            $customization['colors']['text'] = $request->text_color;
        }
        if ($request->filled('font_family')) {
            $customization['typography']['font_family'] = $request->font_family;
        }
        if ($request->filled('layout_style')) {
            $customization['layout']['style'] = $request->layout_style;
        }
        if ($request->filled('button_style')) {
            $customization['layout']['button_style'] = $request->button_style;
        }
        
        // Create temporary facility object with preview data
        $previewFacility = $facility->replicate();
        $previewFacility->setRawAttributes(array_merge(
            $facility->getRawOriginal(),
            $request->only([
                'primary_color',
                'secondary_color', 
                'accent_color',
                'background_color',
                'text_color',
                'font_family',
                'layout_style',
                'button_style',
                'navigation_style',
                'content_layout',
                'section_spacing',
                'card_style',
                'footer_style',
                'hero_background_type',
                'hero_overlay_opacity',
                'enable_animations',
                'enable_parallax'
            ])
        ));
        
        return view('public.facilities.show', [
            'facility' => $previewFacility,
            'products' => $facility->products()->take(6)->get(),
            'similarFacilities' => collect(),
            'isPreview' => true
        ]);
    }

    /**
     * Reset facility customization to defaults
     */
    public function reset(Facility $facility)
    {
        $this->authorize('update', $facility);
        
        // Delete hero background image if exists
        if ($facility->hero_background_type === 'image' && $facility->hero_background_value) {
            $oldImagePath = str_replace(asset('storage/'), '', $facility->hero_background_value);
            if (Storage::disk('public')->exists($oldImagePath)) {
                Storage::disk('public')->delete($oldImagePath);
            }
        }
        
        $facility->resetCustomization();
        
        return redirect()
            ->route('facility.customization.edit', $facility)
            ->with('success', __('facilities.customization.reset_successfully'));
    }

    /**
     * Apply a color preset to the facility
     */
    public function applyPreset(Request $request, Facility $facility)
    {
        $this->authorize('update', $facility);
        
        $request->validate([
            'preset' => ['required', 'string'],
        ]);
        
        $presets = $this->getColorPresets();
        $presetData = $presets[$request->preset] ?? null;
        
        if (!$presetData) {
            return back()->withErrors(['preset' => 'Invalid color preset selected.']);
        }
        
        $facility->update([
            'primary_color' => $presetData['primary'],
            'secondary_color' => $presetData['secondary'],
            'accent_color' => $presetData['accent'] ?? $presetData['primary'],
        ]);
        
        return redirect()
            ->route('facility.customization.edit', $facility)
            ->with('success', __('facilities.customization.preset_applied_successfully'));
    }

    /**
     * Get available color presets
     */
    private function getColorPresets()
    {
        return [
            'blue' => [
                'name' => 'Ocean Blue',
                'primary' => '#2563eb',
                'secondary' => '#1e40af',
                'accent' => '#0ea5e9',
            ],
            'green' => [
                'name' => 'Forest Green',
                'primary' => '#059669',
                'secondary' => '#047857',
                'accent' => '#10b981',
            ],
            'purple' => [
                'name' => 'Royal Purple',
                'primary' => '#7c3aed',
                'secondary' => '#6d28d9',
                'accent' => '#8b5cf6',
            ],
            'orange' => [
                'name' => 'Sunset Orange',
                'primary' => '#ea580c',
                'secondary' => '#c2410c',
                'accent' => '#f97316',
            ],
            'red' => [
                'name' => 'Crimson Red',
                'primary' => '#dc2626',
                'secondary' => '#b91c1c',
                'accent' => '#ef4444',
            ],
            'teal' => [
                'name' => 'Teal Elegance',
                'primary' => '#0d9488',
                'secondary' => '#0f766e',
                'accent' => '#14b8a6',
            ],
            'indigo' => [
                'name' => 'Deep Indigo',
                'primary' => '#4f46e5',
                'secondary' => '#4338ca',
                'accent' => '#6366f1',
            ],
            'pink' => [
                'name' => 'Rose Gold',
                'primary' => '#e11d48',
                'secondary' => '#be185d',
                'accent' => '#f43f5e',
            ],
        ];
    }

    /**
     * Get font options
     */
    private function getFontOptions()
    {
        return [
            'figtree' => 'Figtree (Default)',
            'inter' => 'Inter',
            'poppins' => 'Poppins',
            'roboto' => 'Roboto',
            'open-sans' => 'Open Sans',
            'lato' => 'Lato',
        ];
    }

    /**
     * Get layout options
     */
    private function getLayoutOptions()
    {
        return [
            'layout_styles' => [
                'modern' => 'Modern',
                'classic' => 'Classic', 
                'minimal' => 'Minimal',
                'corporate' => 'Corporate',
                'elegant' => 'Elegant',
                'bold' => 'Bold',
            ],
            'button_styles' => [
                'rounded' => 'Rounded',
                'square' => 'Square',
                'pill' => 'Pill (Fully Rounded)',
            ],
            'logo_positions' => [
                'left' => 'Left',
                'center' => 'Center',
                'right' => 'Right',
            ],
            'navigation_styles' => [
                'standard' => 'Standard',
                'transparent' => 'Transparent',
                'boxed' => 'Boxed',
                'centered' => 'Centered',
            ],
            'content_layouts' => [
                'full-width' => 'Full Width',
                'boxed' => 'Boxed Container',
                'wide' => 'Wide Container',
            ],
            'section_spacings' => [
                'compact' => 'Compact',
                'normal' => 'Normal',
                'relaxed' => 'Relaxed',
                'spacious' => 'Spacious',
            ],
            'card_styles' => [
                'modern' => 'Modern Shadow',
                'flat' => 'Flat Design',
                'outlined' => 'Outlined',
                'elevated' => 'Elevated',
            ],
            'footer_styles' => [
                'simple' => 'Simple',
                'detailed' => 'Detailed',
                'minimal' => 'Minimal',
            ],
        ];
    }
}
