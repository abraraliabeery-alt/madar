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
            'secondary_text_color' => ['nullable', 'regex:/^#[A-Fa-f0-9]{6}$/'],

            // Typography
            'font_family' => ['nullable', Rule::in(['figtree', 'inter', 'poppins', 'roboto', 'open-sans', 'lato'])],

            // Logo
            'logo' => ['nullable', 'image', 'max:2048', 'mimes:png,jpg,jpeg,svg'], // 2MB max
            'remove_logo' => ['nullable', 'boolean'],

            // Hero Section
            'hero_background_type' => ['nullable', Rule::in(['gradient', 'color', 'image'])],
            'hero_background_value' => ['nullable', 'string', 'max:500'],
            'hero_overlay_opacity' => ['nullable', 'integer', 'min:0', 'max:100'],
            'hero_background_image' => ['nullable', 'image', 'max:2048'], // 2MB max to match PHP upload_max_filesize

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

            // Facility site sections visibility
            'sections' => ['nullable', 'array'],
            'sections.*' => ['nullable', 'boolean'],

            // Facility site section variants
            'variants' => ['nullable', 'array'],
            'variants.*' => ['nullable', 'integer', 'min:1', 'max:4'],

            // Facility site content
            'content' => ['nullable', 'array'],
            'content.services' => ['nullable', 'array'],
            'content.services.enabled' => ['nullable', 'array'],
            'content.services.enabled.*' => ['nullable', 'boolean'],
        ]);

        // Handle hero background image upload
        if ($request->hasFile('hero_background_image')) {
            try {
                // Check if file upload was successful
                if (!$request->file('hero_background_image')->isValid()) {
                    throw new \Exception('File upload failed: ' . $request->file('hero_background_image')->getErrorMessage());
                }

                // Check file size (in bytes)
                $fileSize = $request->file('hero_background_image')->getSize();
                if ($fileSize > 2 * 1024 * 1024) { // 2MB in bytes
                    throw new \Exception('File size exceeds 2MB limit. Current size: ' . round($fileSize / 1024 / 1024, 2) . 'MB');
                }

                // Check file type
                $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                $mimeType = $request->file('hero_background_image')->getMimeType();
                if (!in_array($mimeType, $allowedTypes)) {
                    throw new \Exception('Invalid file type. Allowed types: JPG, PNG, GIF. Current type: ' . $mimeType);
                }

                // Debug information
                \Log::info('Hero background image upload started', [
                    'file_name' => $request->file('hero_background_image')->getClientOriginalName(),
                    'file_size' => $fileSize,
                    'file_mime' => $mimeType,
                    'file_extension' => $request->file('hero_background_image')->getClientOriginalExtension(),
                    'is_valid' => $request->file('hero_background_image')->isValid(),
                    'error' => $request->file('hero_background_image')->getErrorMessage(),
                ]);

                // Delete old hero background if it exists and is an image
                if ($facility->hero_background_type === 'image' && $facility->hero_background_value) {
                    $oldImagePath = str_replace(asset('storage/'), '', $facility->hero_background_value);
                    if (Storage::disk('public')->exists($oldImagePath)) {
                        Storage::disk('public')->delete($oldImagePath);
                    }
                }

                // Ensure the directory exists
                $directory = 'facility-customization/hero';
                if (!Storage::disk('public')->exists($directory)) {
                    Storage::disk('public')->makeDirectory($directory);
                }

                $heroImagePath = $request->file('hero_background_image')->store($directory, 'public');

                // Verify the file was actually stored
                if (!$heroImagePath || !Storage::disk('public')->exists($heroImagePath)) {
                    throw new \Exception('File was not stored successfully');
                }

                $validated['hero_background_type'] = 'image';
                $validated['hero_background_value'] = asset($heroImagePath);
            } catch (\Exception $e) {
                \Log::error('Hero background image upload failed', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'facility_id' => $facility->id,
                ]);

                return back()
                    ->withInput()
                    ->withErrors(['hero_background_image' => 'Failed to upload image: ' . $e->getMessage()]);
            }
        } else {
            // Handle case when no hero image is uploaded
            // Preserve existing hero image settings if no new image is provided
            if ($request->filled('hero_background_type') && $request->hero_background_type !== 'image') {
                // If changing to non-image type, remove the old image
                if ($facility->hero_background_type === 'image' && $facility->hero_background_value) {
                    try {
                        $oldImagePath = str_replace(asset('storage/'), '', $facility->hero_background_value);
                        if (Storage::disk('public')->exists($oldImagePath)) {
                            Storage::disk('public')->delete($oldImagePath);
                        }
                        // Clear the image value when changing to non-image type
                        $validated['hero_background_value'] = null;
                    } catch (\Exception $e) {
                        \Log::error('Failed to remove old hero background image', [
                            'error' => $e->getMessage(),
                            'facility_id' => $facility->id,
                        ]);
                    }
                }
            } elseif ($facility->hero_background_type === 'image' && $request->hero_background_type == 'image') {
                // If no hero background type is specified, preserve existing settings
                unset($validated['hero_background_type']);
                unset($validated['hero_background_value']);
            }
        }

        // Handle logo upload
        if ($request->hasFile('logo')) {
            try {
                // Check if file upload was successful
                if (!$request->file('logo')->isValid()) {
                    throw new \Exception('Logo upload failed: ' . $request->file('logo')->getErrorMessage());
                }

                // Check file size (in bytes)
                $fileSize = $request->file('logo')->getSize();
                if ($fileSize > 2 * 1024 * 1024) { // 2MB in bytes
                    throw new \Exception('Logo file size exceeds 2MB limit. Current size: ' . round($fileSize / 1024 / 1024, 2) . 'MB');
                }

                // Check file type
                $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/svg+xml'];
                $mimeType = $request->file('logo')->getMimeType();
                if (!in_array($mimeType, $allowedTypes)) {
                    throw new \Exception('Invalid logo file type. Allowed types: PNG, JPG, SVG. Current type: ' . $mimeType);
                }

                // Delete old logo if it exists
                if ($facility->logo_path) {
                    $oldLogoPath = $facility->logo_path;
                    if (Storage::disk('public')->exists($oldLogoPath)) {
                        Storage::disk('public')->delete($oldLogoPath);
                    }
                }

                // Ensure the directory exists
                $directory = 'facility-customization/logos';
                if (!Storage::disk('public')->exists($directory)) {
                    Storage::disk('public')->makeDirectory($directory);
                }

                $logoPath = $request->file('logo')->store($directory, 'public');

                // Verify the file was actually stored
                if (!$logoPath || !Storage::disk('public')->exists($logoPath)) {
                    throw new \Exception('Logo was not stored successfully');
                }

                $validated['logo_path'] =  asset($logoPath);

            } catch (\Exception $e) {
                \Log::error('Logo upload failed', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'facility_id' => $facility->id,
                ]);

                return back()
                    ->withInput()
                    ->withErrors(['logo' => 'Failed to upload logo: ' . $e->getMessage()]);
            }
        }

        // Handle logo removal
        if ($request->boolean('remove_logo') && $facility->logo_path) {
            try {
                $oldLogoPath = $facility->logo_path;
                if (Storage::disk('public')->exists($oldLogoPath)) {
                    Storage::disk('public')->delete($oldLogoPath);
                }
                $validated['logo_path'] = null;
            } catch (\Exception $e) {
                \Log::error('Logo removal failed', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'facility_id' => $facility->id,
                ]);
            }
        }

        $sections = $validated['sections'] ?? null;
        $variants = $validated['variants'] ?? null;
        $content = $validated['content'] ?? null;
        unset($validated['sections']);
        unset($validated['variants']);
        unset($validated['content']);

        $facility->update($validated);

        if (is_array($sections)) {
            $customizationSettings = $facility->customization_settings ?? [];
            $existingSections = data_get($customizationSettings, 'sections', []);

            $normalizedSections = [];
            foreach ($sections as $key => $value) {
                $normalizedSections[$key] = (bool) $value;
            }

            $customizationSettings['sections'] = array_merge($existingSections, $normalizedSections);
            $facility->customization_settings = $customizationSettings;
            $facility->save();
        }

        if (is_array($variants)) {
            $customizationSettings = $facility->customization_settings ?? [];
            $existingVariants = data_get($customizationSettings, 'variants', []);

            $normalizedVariants = [];
            foreach ($variants as $key => $value) {
                if ($value === null || $value === '') {
                    continue;
                }
                $normalizedVariants[$key] = max(1, min(4, (int) $value));
            }

            $customizationSettings['variants'] = array_merge($existingVariants, $normalizedVariants);
            $facility->customization_settings = $customizationSettings;
            $facility->save();
        }

        if (is_array($content)) {
            $customizationSettings = $facility->customization_settings ?? [];
            $existingContent = data_get($customizationSettings, 'content', []);

            $customizationSettings['content'] = array_replace_recursive($existingContent, $content);
            $facility->customization_settings = $customizationSettings;
            $facility->save();
        }


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
        if ($request->filled('secondary_text_color')) {
            $customization['colors']['secondary_text'] = $request->secondary_text_color;
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
                'secondary_text_color',
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
            'facility_raw' => $facility,
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
            return back()->withErrors(['preset' => __('facilities.customization.invalid_preset')]);
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
     * Test file upload functionality
     */
    public function testUpload(Request $request)
    {
        try {
            if (!$request->hasFile('test_file')) {
                return response()->json(['error' => 'No file provided'], 400);
            }

            $file = $request->file('test_file');

            $result = [
                'file_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize(),
                'file_mime' => $file->getMimeType(),
                'file_extension' => $file->getClientOriginalExtension(),
                'is_valid' => $file->isValid(),
                'error' => $file->getErrorMessage(),
                'php_limits' => [
                    'upload_max_filesize' => ini_get('upload_max_filesize'),
                    'post_max_size' => ini_get('post_max_size'),
                    'max_file_uploads' => ini_get('max_file_uploads'),
                    'memory_limit' => ini_get('memory_limit'),
                ],
                'storage_info' => [
                    'public_disk_exists' => Storage::disk('public')->exists('facility-customization/hero'),
                    'public_disk_writable' => is_writable(storage_path('app/public/facility-customization/hero')),
                ]
            ];

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get available color presets
     */
    private function getColorPresets()
    {
        return [
            'blue' => [
                'name' => __('facilities.customization.color_preset_names.blue'),
                'primary' => '#2563eb',
                'secondary' => '#1e40af',
                'accent' => '#0ea5e9',
            ],
            'green' => [
                'name' => __('facilities.customization.color_preset_names.green'),
                'primary' => '#059669',
                'secondary' => '#047857',
                'accent' => '#10b981',
            ],
            'purple' => [
                'name' => __('facilities.customization.color_preset_names.purple'),
                'primary' => '#7c3aed',
                'secondary' => '#6d28d9',
                'accent' => '#8b5cf6',
            ],
            'orange' => [
                'name' => __('facilities.customization.color_preset_names.orange'),
                'primary' => '#ea580c',
                'secondary' => '#c2410c',
                'accent' => '#f97316',
            ],
            'red' => [
                'name' => __('facilities.customization.color_preset_names.red'),
                'primary' => '#dc2626',
                'secondary' => '#b91c1c',
                'accent' => '#ef4444',
            ],
            'teal' => [
                'name' => __('facilities.customization.color_preset_names.teal'),
                'primary' => '#0d9488',
                'secondary' => '#0f766e',
                'accent' => '#14b8a6',
            ],
            'indigo' => [
                'name' => __('facilities.customization.color_preset_names.indigo'),
                'primary' => '#4f46e5',
                'secondary' => '#4338ca',
                'accent' => '#6366f1',
            ],
            'pink' => [
                'name' => __('facilities.customization.color_preset_names.pink'),
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
            'figtree' => __('facilities.customization.fonts.figtree'),
            'inter' => __('facilities.customization.fonts.inter'),
            'poppins' => __('facilities.customization.fonts.poppins'),
            'roboto' => __('facilities.customization.fonts.roboto'),
            'open-sans' => __('facilities.customization.fonts.open_sans'),
            'lato' => __('facilities.customization.fonts.lato'),
        ];
    }

    /**
     * Get layout options
     */
    private function getLayoutOptions()
    {
        return [
            'layout_styles' => [
                'modern' => __('facilities.customization.layout_styles.modern'),
                'classic' => __('facilities.customization.layout_styles.classic'),
                'minimal' => __('facilities.customization.layout_styles.minimal'),
                'corporate' => __('facilities.customization.layout_styles.corporate'),
                'elegant' => __('facilities.customization.layout_styles.elegant'),
                'bold' => __('facilities.customization.layout_styles.bold'),
            ],
            'button_styles' => [
                'rounded' => __('facilities.customization.button_styles.rounded'),
                'square' => __('facilities.customization.button_styles.square'),
                'pill' => __('facilities.customization.button_styles.pill'),
            ],
            'logo_positions' => [
                'left' => __('facilities.customization.logo_positions.left'),
                'center' => __('facilities.customization.logo_positions.center'),
                'right' => __('facilities.customization.logo_positions.right'),
            ],
            'navigation_styles' => [
                'standard' => __('facilities.customization.navigation_styles.standard'),
                'transparent' => __('facilities.customization.navigation_styles.transparent'),
                'boxed' => __('facilities.customization.navigation_styles.boxed'),
                'centered' => __('facilities.customization.navigation_styles.centered'),
            ],
            'content_layouts' => [
                'full-width' => __('facilities.customization.content_layouts.full-width'),
                'boxed' => __('facilities.customization.content_layouts.boxed'),
                'wide' => __('facilities.customization.content_layouts.wide'),
            ],
            'section_spacings' => [
                'compact' => __('facilities.customization.section_spacings.compact'),
                'normal' => __('facilities.customization.section_spacings.normal'),
                'relaxed' => __('facilities.customization.section_spacings.relaxed'),
                'spacious' => __('facilities.customization.section_spacings.spacious'),
            ],
            'card_styles' => [
                'modern' => __('facilities.customization.card_styles.modern'),
                'flat' => __('facilities.customization.card_styles.flat'),
                'outlined' => __('facilities.customization.card_styles.outlined'),
                'elevated' => __('facilities.customization.card_styles.elevated'),
            ],
            'footer_styles' => [
                'simple' => __('facilities.customization.footer_styles.simple'),
                'detailed' => __('facilities.customization.footer_styles.detailed'),
                'minimal' => __('facilities.customization.footer_styles.minimal'),
            ],
        ];
    }
}
