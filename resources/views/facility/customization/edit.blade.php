@extends('layouts.app')

@section('title', __('facilities.customization.page_title'))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    {{ __('facilities.customization.page_title') }}
                </h1>
                <p class="mt-2 text-gray-600">
                    {{ __('facilities.customization.page_subtitle') }}
                </p>
            </div>
            <div class="flex space-x-4">
                <a href="{{ route('facility.customization.preview', $facility) }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:ring-2 focus:ring-blue-500"
                   target="_blank">
                    <i class="fas fa-eye mr-2"></i>
                    {{ __('facilities.customization.preview') }}
                </a>
                <form action="{{ route('facility.customization.reset', $facility) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-red-300 rounded-lg shadow-sm text-red-700 bg-white hover:bg-red-50 focus:ring-2 focus:ring-red-500"
                            onclick="return confirm('{{ __('facilities.customization.confirm_reset') }}')">
                        <i class="fas fa-undo mr-2"></i>
                        {{ __('facilities.customization.reset_to_default') }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle h-5 w-5 text-green-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle h-5 w-5 text-red-400"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">{{ __('validation.errors_occurred') }}</h3>
                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ route('facility.customization.update', $facility) }}" method="POST" enctype="multipart/form-data" id="customizationForm">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Settings Panel -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Color Scheme Section -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-semibold text-gray-900">
                            <i class="fas fa-palette mr-2 text-blue-600"></i>
                            {{ __('facilities.customization.color_scheme') }}
                        </h2>
                        <button type="button" onclick="toggleColorPresets()" 
                                class="text-sm text-blue-600 hover:text-blue-800">
                            {{ __('facilities.customization.color_presets') }}
                        </button>
                    </div>

                    <!-- Color Presets (Hidden by default) -->
                    <div id="colorPresets" class="hidden mb-6 p-4 bg-gray-50 rounded-lg">
                        <h3 class="text-sm font-medium text-gray-900 mb-3">{{ __('facilities.customization.choose_preset') }}</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            @foreach($presets as $key => $preset)
                                <button type="button" 
                                        onclick="applyPreset('{{ $key }}', '{{ $preset['primary'] }}', '{{ $preset['secondary'] }}', '{{ $preset['accent'] }}')"
                                        class="p-3 border border-gray-200 rounded-lg hover:border-gray-300 focus:ring-2 focus:ring-blue-500 group">
                                    <div class="flex space-x-1 mb-2">
                                        <div class="w-4 h-4 rounded" style="background-color: {{ $preset['primary'] }}"></div>
                                        <div class="w-4 h-4 rounded" style="background-color: {{ $preset['secondary'] }}"></div>
                                        <div class="w-4 h-4 rounded" style="background-color: {{ $preset['accent'] }}"></div>
                                    </div>
                                    <span class="text-xs text-gray-700 group-hover:text-gray-900">{{ $preset['name'] }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="primary_color" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('facilities.customization.primary_color') }}
                            </label>
                            <div class="flex space-x-2">
                                <input type="color" 
                                       id="primary_color" 
                                       name="primary_color" 
                                       value="{{ old('primary_color', $facility->primary_color ?? '#2563eb') }}"
                                       class="w-12 h-10 border border-gray-300 rounded cursor-pointer">
                                <input type="text" 
                                       id="primary_color_text"
                                       value="{{ old('primary_color', $facility->primary_color ?? '#2563eb') }}"
                                       class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       pattern="^#[A-Fa-f0-9]{6}$"
                                       placeholder="#2563eb">
                            </div>
                        </div>

                        <div>
                            <label for="secondary_color" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('facilities.customization.secondary_color') }}
                            </label>
                            <div class="flex space-x-2">
                                <input type="color" 
                                       id="secondary_color" 
                                       name="secondary_color" 
                                       value="{{ old('secondary_color', $facility->secondary_color ?? '#1e40af') }}"
                                       class="w-12 h-10 border border-gray-300 rounded cursor-pointer">
                                <input type="text" 
                                       id="secondary_color_text"
                                       value="{{ old('secondary_color', $facility->secondary_color ?? '#1e40af') }}"
                                       class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       pattern="^#[A-Fa-f0-9]{6}$"
                                       placeholder="#1e40af">
                            </div>
                        </div>

                        <div>
                            <label for="accent_color" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('facilities.customization.accent_color') }}
                            </label>
                            <div class="flex space-x-2">
                                <input type="color" 
                                       id="accent_color" 
                                       name="accent_color" 
                                       value="{{ old('accent_color', $facility->accent_color ?? '#f59e0b') }}"
                                       class="w-12 h-10 border border-gray-300 rounded cursor-pointer">
                                <input type="text" 
                                       id="accent_color_text"
                                       value="{{ old('accent_color', $facility->accent_color ?? '#f59e0b') }}"
                                       class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       pattern="^#[A-Fa-f0-9]{6}$"
                                       placeholder="#f59e0b">
                            </div>
                        </div>

                        <div>
                            <label for="background_color" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('facilities.customization.background_color') }}
                            </label>
                            <div class="flex space-x-2">
                                <input type="color" 
                                       id="background_color" 
                                       name="background_color" 
                                       value="{{ old('background_color', $facility->background_color ?? '#ffffff') }}"
                                       class="w-12 h-10 border border-gray-300 rounded cursor-pointer">
                                <input type="text" 
                                       id="background_color_text"
                                       value="{{ old('background_color', $facility->background_color ?? '#ffffff') }}"
                                       class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       pattern="^#[A-Fa-f0-9]{6}$"
                                       placeholder="#ffffff">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Typography Section -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">
                        <i class="fas fa-font mr-2 text-purple-600"></i>
                        {{ __('facilities.customization.typography') }}
                    </h2>

                    <div>
                        <label for="font_family" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('facilities.customization.font_family') }}
                        </label>
                        <select id="font_family" name="font_family" 
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @foreach($fontOptions as $value => $label)
                                <option value="{{ $value }}" 
                                        {{ old('font_family', $facility->font_family ?? 'figtree') === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Hero Section -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">
                        <i class="fas fa-image mr-2 text-green-600"></i>
                        {{ __('facilities.customization.hero_section') }}
                    </h2>

                    <div class="space-y-4">
                        <div>
                            <label for="hero_background_type" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('facilities.customization.hero_background_type') }}
                            </label>
                            <select id="hero_background_type" name="hero_background_type" onchange="toggleHeroBackground()"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="gradient" {{ old('hero_background_type', $facility->hero_background_type ?? 'gradient') === 'gradient' ? 'selected' : '' }}>
                                    {{ __('facilities.customization.gradient') }}
                                </option>
                                <option value="color" {{ old('hero_background_type', $facility->hero_background_type) === 'color' ? 'selected' : '' }}>
                                    {{ __('facilities.customization.solid_color') }}
                                </option>
                                <option value="image" {{ old('hero_background_type', $facility->hero_background_type) === 'image' ? 'selected' : '' }}>
                                    {{ __('facilities.customization.background_image') }}
                                </option>
                            </select>
                        </div>

                        <div id="hero_color_input" class="hidden">
                            <label for="hero_background_value" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('facilities.customization.hero_background_color') }}
                            </label>
                            <input type="color" 
                                   id="hero_background_value" 
                                   name="hero_background_value" 
                                   value="{{ old('hero_background_value', $facility->hero_background_value) }}"
                                   class="w-full h-12 border border-gray-300 rounded cursor-pointer">
                        </div>

                        <div id="hero_image_input" class="hidden">
                            <label for="hero_background_image" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('facilities.customization.hero_background_image') }}
                            </label>
                            <input type="file" 
                                   id="hero_background_image" 
                                   name="hero_background_image"
                                   accept="image/*"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @if($facility->hero_background_type === 'image' && $facility->hero_background_value)
                                <div class="mt-2">
                                    <img src="{{ $facility->hero_background_value }}" alt="Current hero background" class="w-32 h-20 object-cover rounded">
                                </div>
                            @endif
                        </div>

                        <div>
                            <label for="hero_overlay_opacity" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('facilities.customization.overlay_opacity') }}: <span id="opacity_value">{{ old('hero_overlay_opacity', $facility->hero_overlay_opacity ?? '20') }}%</span>
                            </label>
                            <input type="range" 
                                   id="hero_overlay_opacity" 
                                   name="hero_overlay_opacity"
                                   min="0" max="100" step="5"
                                   value="{{ old('hero_overlay_opacity', $facility->hero_overlay_opacity ?? '20') }}"
                                   oninput="document.getElementById('opacity_value').textContent = this.value + '%'"
                                   class="block w-full">
                        </div>
                    </div>
                </div>

                <!-- Layout & Design -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">
                        <i class="fas fa-layout mr-2 text-orange-600"></i>
                        {{ __('facilities.customization.layout_design') }}
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="layout_style" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('facilities.customization.layout_style') }}
                            </label>
                            <select id="layout_style" name="layout_style" 
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @foreach($layoutOptions['layout_styles'] as $value => $label)
                                    <option value="{{ $value }}" 
                                            {{ old('layout_style', $facility->layout_style ?? 'modern') === $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="button_style" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('facilities.customization.button_style') }}
                            </label>
                            <select id="button_style" name="button_style" 
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @foreach($layoutOptions['button_styles'] as $value => $label)
                                    <option value="{{ $value }}" 
                                            {{ old('button_style', $facility->button_style ?? 'rounded') === $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="logo_position" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('facilities.customization.logo_position') }}
                            </label>
                            <select id="logo_position" name="logo_position" 
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @foreach($layoutOptions['logo_positions'] as $value => $label)
                                    <option value="{{ $value }}" 
                                            {{ old('logo_position', $facility->logo_position ?? 'left') === $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Animation Settings -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('facilities.customization.animation_effects') }}</h3>
                        <div class="space-y-4">
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       id="enable_animations" 
                                       name="enable_animations" 
                                       value="1"
                                       {{ old('enable_animations', $facility->enable_animations ?? true) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700">{{ __('facilities.customization.enable_animations') }}</span>
                            </label>
                            
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       id="enable_parallax" 
                                       name="enable_parallax" 
                                       value="1"
                                       {{ old('enable_parallax', $facility->enable_parallax ?? true) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700">{{ __('facilities.customization.enable_parallax') }}</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Custom CSS -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">
                        <i class="fas fa-code mr-2 text-red-600"></i>
                        {{ __('facilities.customization.custom_css') }}
                    </h2>

                    <div>
                        <label for="custom_css" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('facilities.customization.additional_css') }}
                            <span class="text-gray-500">({{ __('facilities.customization.optional') }})</span>
                        </label>
                        <textarea id="custom_css" 
                                  name="custom_css" 
                                  rows="8"
                                  class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent font-mono text-sm"
                                  placeholder=".custom-class { color: #333; }">{{ old('custom_css', $facility->custom_css) }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">{{ __('facilities.customization.css_warning') }}</p>
                    </div>
                </div>

            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                
                <!-- Save Actions -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('facilities.customization.actions') }}</h3>
                    <div class="space-y-3">
                        <button type="submit" 
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-lg transition-colors focus:ring-2 focus:ring-blue-500">
                            <i class="fas fa-save mr-2"></i>
                            {{ __('facilities.customization.save_changes') }}
                        </button>
                        
                        <button type="button" 
                                onclick="previewChanges()"
                                class="w-full bg-gray-600 hover:bg-gray-700 text-white font-medium py-3 px-4 rounded-lg transition-colors focus:ring-2 focus:ring-gray-500">
                            <i class="fas fa-eye mr-2"></i>
                            {{ __('facilities.customization.preview_changes') }}
                        </button>
                    </div>
                </div>

                <!-- Social Media -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-share-alt mr-2 text-blue-600"></i>
                        {{ __('facilities.customization.social_media') }}
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <label for="facebook_url" class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fab fa-facebook-f mr-1"></i>
                                {{ __('facilities.customization.facebook') }}
                            </label>
                            <input type="url" 
                                   id="facebook_url" 
                                   name="facebook_url" 
                                   value="{{ old('facebook_url', $facility->facebook_url) }}"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                                   placeholder="https://facebook.com/...">
                        </div>

                        <div>
                            <label for="instagram_url" class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fab fa-instagram mr-1"></i>
                                {{ __('facilities.customization.instagram') }}
                            </label>
                            <input type="url" 
                                   id="instagram_url" 
                                   name="instagram_url" 
                                   value="{{ old('instagram_url', $facility->instagram_url) }}"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                                   placeholder="https://instagram.com/...">
                        </div>

                        <div>
                            <label for="twitter_url" class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fab fa-twitter mr-1"></i>
                                {{ __('facilities.customization.twitter') }}
                            </label>
                            <input type="url" 
                                   id="twitter_url" 
                                   name="twitter_url" 
                                   value="{{ old('twitter_url', $facility->twitter_url) }}"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                                   placeholder="https://twitter.com/...">
                        </div>

                        <div>
                            <label for="linkedin_url" class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fab fa-linkedin-in mr-1"></i>
                                {{ __('facilities.customization.linkedin') }}
                            </label>
                            <input type="url" 
                                   id="linkedin_url" 
                                   name="linkedin_url" 
                                   value="{{ old('linkedin_url', $facility->linkedin_url) }}"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                                   placeholder="https://linkedin.com/...">
                        </div>
                    </div>
                </div>

                <!-- SEO Settings -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-search mr-2 text-green-600"></i>
                        {{ __('facilities.customization.seo_settings') }}
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('facilities.customization.meta_description') }}
                            </label>
                            <textarea id="meta_description" 
                                      name="meta_description" 
                                      rows="3"
                                      maxlength="160"
                                      class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                                      placeholder="{{ __('facilities.customization.meta_description_placeholder') }}">{{ old('meta_description', $facility->meta_description) }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">{{ __('facilities.customization.max_160_chars') }}</p>
                        </div>

                        <div>
                            <label for="meta_keywords" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('facilities.customization.meta_keywords') }}
                            </label>
                            <input type="text" 
                                   id="meta_keywords" 
                                   name="meta_keywords" 
                                   value="{{ old('meta_keywords', $facility->meta_keywords) }}"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                                   placeholder="{{ __('facilities.customization.keywords_placeholder') }}">
                        </div>
                    </div>
                </div>

                <!-- Current Preview -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('facilities.customization.current_colors') }}</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">{{ __('facilities.customization.primary') }}</span>
                            <div class="w-8 h-8 rounded border border-gray-200" style="background-color: {{ $facility->primary_color ?? '#2563eb' }}"></div>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">{{ __('facilities.customization.secondary') }}</span>
                            <div class="w-8 h-8 rounded border border-gray-200" style="background-color: {{ $facility->secondary_color ?? '#1e40af' }}"></div>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">{{ __('facilities.customization.accent') }}</span>
                            <div class="w-8 h-8 rounded border border-gray-200" style="background-color: {{ $facility->accent_color ?? '#f59e0b' }}"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </form>
</div>

<!-- JavaScript -->
<script>
// Color picker synchronization
document.addEventListener('DOMContentLoaded', function() {
    setupColorPickers();
    toggleHeroBackground();
});

function setupColorPickers() {
    const colorInputs = ['primary_color', 'secondary_color', 'accent_color', 'background_color'];
    
    colorInputs.forEach(function(colorId) {
        const colorPicker = document.getElementById(colorId);
        const textInput = document.getElementById(colorId + '_text');
        
        if (colorPicker && textInput) {
            colorPicker.addEventListener('input', function() {
                textInput.value = this.value;
                textInput.name = colorId;
            });
            
            textInput.addEventListener('input', function() {
                if (/^#[A-Fa-f0-9]{6}$/.test(this.value)) {
                    colorPicker.value = this.value;
                }
                this.name = colorId;
            });
        }
    });
}

function toggleColorPresets() {
    const presets = document.getElementById('colorPresets');
    presets.classList.toggle('hidden');
}

function applyPreset(presetKey, primary, secondary, accent) {
    document.getElementById('primary_color').value = primary;
    document.getElementById('primary_color_text').value = primary;
    document.getElementById('secondary_color').value = secondary;
    document.getElementById('secondary_color_text').value = secondary;
    document.getElementById('accent_color').value = accent;
    document.getElementById('accent_color_text').value = accent;
    
    toggleColorPresets();
}

function toggleHeroBackground() {
    const type = document.getElementById('hero_background_type').value;
    const colorInput = document.getElementById('hero_color_input');
    const imageInput = document.getElementById('hero_image_input');
    
    colorInput.classList.toggle('hidden', type !== 'color');
    imageInput.classList.toggle('hidden', type !== 'image');
}

function previewChanges() {
    const form = document.getElementById('customizationForm');
    const formData = new FormData(form);
    const queryString = new URLSearchParams(formData).toString();
    
    window.open(
        '{{ route("facility.customization.preview", $facility) }}?' + queryString, 
        '_blank'
    );
}
</script>
@endsection
