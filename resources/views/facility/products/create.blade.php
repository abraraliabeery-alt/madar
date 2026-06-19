@extends('facility.layouts.app')

@section('title', __('facility.products.create.title'))

@section('content')
<div class="container mx-auto px-4 my-10 relative">
    <div class="w-full max-w-6xl mx-auto">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">{{ __('facility.products.create.title') }}</h1>
            <p class="text-sm text-gray-500 mt-1">{{ __('facility.products.create.basic_info') }} • {{ __('facility.products.create.location_info') }} • {{ __('facility.products.create.media') }}</p>
        </div>

        <div class="grid grid-cols-1 gap-6">
            <div>
                <div id="voice-assist" class="mb-8 bg-white rounded-md border border-gray-200 p-5">
                    <h5 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                        {{ __('facility.products.create.voice.title') }}
                    </h5>
                    <div class="flex flex-col md:flex-row gap-3 items-start">
                        <button type="button" id="voice-start" class="inline-flex items-center gap-2 bg-rose-600 hover:bg-rose-700 text-white text-sm font-medium py-2.5 px-4 rounded-md">
                            🎙️ {{ __('facility.products.create.voice.start_speaking') }}
                        </button>
                        <button type="button" id="voice-stop" class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-800 text-sm font-medium py-2.5 px-4 rounded-md">
                            ■ {{ __('facility.products.create.voice.stop') }}
                        </button>
                        <span id="voice-status" class="text-sm text-gray-500"></span>
                    </div>
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.products.create.voice.transcript') }}</label>
                        <textarea id="voice-transcript" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="{{ __('facility.products.create.voice.transcript_placeholder') }}"></textarea>
                    </div>
                    <div class="mt-3 flex items-center gap-3 flex-wrap">
                        <button type="button" id="voice-analyze" class="inline-flex items-center gap-2 bg-gray-800 hover:bg-black text-white text-sm font-medium py-2 px-4 rounded-md">
                            {{ __('facility.products.create.voice.analyze_and_fill') }}
                        </button>
                        <button type="button" id="voice-clear" class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-800 text-sm font-medium py-2 px-4 rounded-md">
                            {{ __('facility.products.create.voice.clear_transcript') }}
                        </button>
                        <button type="button" id="voice-undo" class="inline-flex items-center gap-2 bg-amber-600 hover:bg-amber-700 text-white text-sm font-medium py-2 px-4 rounded-md">
                            {{ __('facility.products.create.voice.undo_last_fill') }}
                        </button>
                        <span id="voice-analyze-status" class="text-xs text-gray-500"></span>
                    </div>
                </div>
                <!-- Offer Type (Below Voice Assist) -->
                <div id="offer-type" class="mb-8 bg-white rounded-md border border-gray-200 p-5">
                    <h5 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                        {{ __('facility.products.create.offer_type') ?? 'نوع العرض' }}
                    </h5>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="flex items-center">
                            <input type="checkbox" id="available_for_sale" name="available_for_sale" value="1"
                                   class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 focus:ring-2"
                                   {{ old('available_for_sale') ? 'checked' : '' }}>
                            <label for="available_for_sale" class="ml-2 text-sm font-medium text-gray-700">
                                {{ __('facility.products.create.for_sale') ?? 'للبيع' }}
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="available_for_rent" name="available_for_rent" value="1"
                                   class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 focus:ring-2"
                                   {{ old('available_for_rent') ? 'checked' : '' }}>
                            <label for="available_for_rent" class="ml-2 text-sm font-medium text-gray-700">
                                {{ __('facility.products.create.for_rent') ?? 'للإيجار' }}
                            </label>
                        </div>
                    </div>
                </div>
                <form method="POST" action="{{ route('facility.products.store') }}" enctype="multipart/form-data">
                    @csrf
                        
                        <!-- Basic Information -->
                        <div id="basic-info" class="mb-8 bg-white rounded-md border border-gray-200 p-5">
                            <h5 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                                {{ __('facility.products.create.basic_info') }}
                            </h5>
                            <p class="text-sm text-gray-500 mb-4">{{ __('facility.products.create.basic_info_help') }}</p>
                            
                            <!-- Translations Repeater -->
                            @include('components.translations-repeater', [
                                'locales' => config('locales.available', []),
                                'namePrefix' => 'translations',
                                'fields' => [
                                    [
                                        'type' => 'input',
                                        'key' => 'title',
                                        'label' => __('facility.products.create.name'),
                                        'requiredFirst' => true,
                                        'placeholder' => __('facility.products.create.title_placeholder'),
                                    ],
                                    [
                                        'type' => 'textarea',
                                        'key' => 'description',
                                        'label' => __('facility.products.create.description'),
                                        'rows' => 3,
                                        'placeholder' => __('facility.products.create.description_placeholder'),
                                    ],
                                ],
                                'addLabel' => __('facility.products.create.add_translation'),
                                'removeLabel' => __('facility.products.create.remove_translation'),
                                'minItems' => 1,
                            ])

                        <div id="rent-offer-fields" class="mb-8 bg-white rounded-md border border-gray-200 p-5 hidden">
                            <h5 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">تفاصيل الإيجار</h5>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <x-form-input 
                                        type="number"
                                        step="0.01"
                                        name="rent_offer[price]"
                                        :label="__('facility.products.create.price') ?? 'السعر'"
                                    />
                                </div>
                                <div>
                                    <x-form-select 
                                        name="rent_offer[period]"
                                        :label="__('facility.products.create.rent_period') ?? 'الدورية'"
                                        :options="['rent_daily' => 'يومي', 'rent_monthly' => 'شهري', 'rent_yearly' => 'سنوي']"
                                        :placeholder="__('facility.form.select') ?? 'اختر'"
                                    />
                                </div>
                                <div>
                                    <x-form-input 
                                        type="number"
                                        step="0.01"
                                        name="rent_offer[deposit]"
                                        :label="__('facility.products.create.deposit') ?? 'التأمين (اختياري)'"
                                    />
                                </div>
                                <div>
                                    <x-form-input 
                                        type="date"
                                        name="rent_offer[valid_from]"
                                        :label="__('facility.products.create.valid_from') ?? 'ساري من'"
                                    />
                                </div>
                                <div>
                                    <x-form-input 
                                        type="date"
                                        name="rent_offer[valid_to]"
                                        :label="__('facility.products.create.valid_to') ?? 'ساري إلى'"
                                    />
                                </div>
                            </div>
                        </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="md:col-span-1">
                                    <x-form-select 
                                        name="category_id"
                                        :label="__('facility.products.create.category')"
                                        :options="$categoryOptions"
                                        :placeholder="__('facility.products.create.select_category')"
                                        required="true"
                                    />
                                </div>
                                @if(!empty($userOptions))
                                    <div>
                                        <x-form-select 
                                            name="owner_user_id"
                                            :label="'المالك (اختياري)'"
                                            :options="$userOptions"
                                            :placeholder="'اختر المالك (إن وجد)'"
                                        />
                                    </div>
                                    <div>
                                        <x-form-select 
                                            name="seller_user_id"
                                            :label="'الموظف المسؤول (اختياري)'"
                                            :options="$userOptions"
                                            :placeholder="'اختر الموظف المسؤول'"
                                        />
                                    </div>
                                @endif
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                                <div>
                                    <x-form-select 
                                        name="building_id"
                                        :label="__('facility.products.create.building_id') ?? 'المبنى (اختياري)'"
                                        :options="$buildingOptions"
                                        :placeholder="__('facility.products.create.select_building') ?? 'اختر المبنى'"
                                    />
                                </div>
                                <div>
                                    <x-form-select 
                                        name="project_id"
                                        :label="__('facility.products.create.project_id') ?? 'المشروع (اختياري)'"
                                        :options="$projectOptions"
                                        :placeholder="__('facility.products.create.select_project') ?? 'اختر المشروع'"
                                    />
                                </div>
                                <div>
                                    <x-form-select 
                                        name="package_id"
                                        :label="__('facility.products.create.package_id') ?? 'الباقة (اختياري)'"
                                        :options="$packageOptions"
                                        :placeholder="__('facility.products.create.select_package') ?? 'اختر الباقة'"
                                    />
                                </div>
                            </div>
 
                            

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-form-select 
                                        name="status_id"
                                        :label="__('facility.products.create.status')"
                                        :options="$statusOptions->toArray()"
                                        :placeholder="__('facility.products.create.select_status')"
                                        required="true"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Location Information -->
                        <div id="location-info" class="mb-8 bg-white rounded-md border border-gray-200 p-5">
                            <h5 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                                {{ __('facility.products.create.location_info') }}
                            </h5>
                            <p class="text-sm text-gray-500 mb-4">{{ __('facility.products.create.location_info_help') }}</p>
                            
                            <div class="mb-4">
                                <x-form-input 
                                    name="address"
                                    :label="__('facility.products.create.address')"
                                    required="true"
                                    :placeholder="__('facility.products.create.address_placeholder')"
                                />
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-form-select 
                                        name="city_id"
                                        :label="__('facility.products.create.city')"
                                        :options="$cities->pluck('localized_name', 'id')->toArray()"
                                        :placeholder="__('facility.products.create.select_city')"
                                        required="true"
                                    />
                                </div>
                                <div>
                                    <x-form-select 
                                        name="neighborhood_id"
                                        :label="__('facility.products.create.neighborhood')"
                                        :options="[]"
                                        :placeholder="__('facility.products.create.select_neighborhood')"
                                    />
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                                <div>
                                    <x-form-select 
                                        name="street_id"
                                        :label="__('facility.products.create.street')"
                                        :options="[]"
                                        :placeholder="__('facility.products.create.select_street')"
                                    />
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <x-form-input 
                                        type="number"
                                        name="latitude"
                                        :label="__('facility.products.create.latitude')"
                                        step="any"
                                        inputmode="decimal"
                                        :placeholder="__('facility.products.create.lat_placeholder')"
                                    />
                                </div>
                                
                                <div>
                                    <x-form-input 
                                        type="number"
                                        name="longitude"
                                        :label="__('facility.products.create.longitude')"
                                        step="any"
                                        inputmode="decimal"
                                        :placeholder="__('facility.products.create.lng_placeholder')"
                                    />
                                </div>
                                
                                <div>
                                    <x-form-input 
                                        type="url"
                                        name="google_maps_url"
                                        :label="__('facility.products.create.google_maps')"
                                        :placeholder="__('facility.products.create.maps_placeholder')"
                                    />
                                </div>
                            </div>

                            <div class="flex items-center gap-2 mt-2">
                                <button type="button" id="extract-coordinates" class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-800 text-xs font-medium py-1.5 px-3 rounded-md">
                                    {{ __('facility.products.create.extract_from_url') }}
                                </button>
                                <button type="button" id="clear-coordinates" class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-800 text-xs font-medium py-1.5 px-3 rounded-md">
                                    {{ __('facility.products.create.clear_coordinates') }}
                                </button>
                                <button type="button" id="copy-coordinates" class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-800 text-xs font-medium py-1.5 px-3 rounded-md">
                                    {{ __('facility.products.create.copy_coordinates') }}
                                </button>
                                <button type="button" id="build-maps-url" class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-800 text-xs font-medium py-1.5 px-3 rounded-md">
                                    {{ __('facility.products.create.build_maps_url') }}
                                </button>
                                <button type="button" id="use-my-location" class="inline-flex items-center gap-2 bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-medium py-1.5 px-3 rounded-md">
                                    {{ __('facility.products.create.use_my_location') }}
                                </button>
                            </div>

                            <div class="mt-4">
                                <h6 class="text-sm font-semibold text-gray-700 mb-2">{{ __('facility.products.create.mini_map_title') }}</h6>
                                <div id="mini-map" class="w-full h-56 rounded-md border border-gray-200"></div>
                            </div>
                            <div class="mt-4">
                                <button type="button" id="open-map-picker" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium py-2 px-4 rounded-md">
                                    {{ __('facility.products.create.choose_from_map') }}
                                </button>
                            </div>

                            <!-- Map Picker Modal -->
                            <div id="map-picker-modal" class="fixed inset-0 bg-black/50 hidden z-50">
                                <div class="bg-white w-full max-w-3xl mx-auto mt-24 rounded-lg shadow-lg overflow-hidden">
                                    <div class="flex items-center justify-between p-4 border-b border-gray-200">
                                        <h3 class="text-base font-semibold text-gray-800">{{ __('facility.products.create.map_picker_title') }}</h3>
                                        <button type="button" id="close-map-picker" class="text-gray-500 hover:text-gray-700">✕</button>
                                    </div>
                                    <div id="map-container" class="w-full h-[420px]"></div>
                                    <div class="flex justify-end gap-3 p-4 border-t border-gray-200">
                                        <button type="button" id="cancel-map-picker" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium py-2 px-4 rounded-md">{{ __('facility.form.cancel') }}</button>
                                        <button type="button" id="apply-map-picker" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-4 rounded-md">{{ __('facility.products.create.apply_coordinates') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Image Upload -->
                        <div id="media" class="mb-8 bg-white rounded-md border border-gray-200 p-5">
                            <h5 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                                {{ __('facility.products.create.media') }}
                            </h5>
                            
                            <div class="space-y-6">
                                <!-- Video URL + live preview -->
                                <div>
                                    <x-form-input 
                                        type="url"
                                        name="video"
                                        :label="__('facility.products.create.video')"
                                    />
                                    <div id="video-preview" class="mt-3 hidden">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.products.create.video_preview') }}</label>
                                        <div id="video-preview-container" class="aspect-video w-full bg-gray-50 border border-gray-200 rounded-md flex items-center justify-center text-gray-400 text-sm"></div>
                                    </div>
                                </div>

                                <!-- Gallery Builder UI -->
                                <div>
                                    <h6 class="text-sm font-semibold text-gray-700 mb-2">{{ __('facility.products.create.media_gallery_title') }}</h6>
                                    <div class="flex flex-col md:flex-row gap-3">
                                        <input type="url" id="gallery-image-url" class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="{{ __('facility.products.create.image_url_placeholder') }}">
                                        <button type="button" id="add-gallery-image" class="bg-gray-800 hover:bg-black text-white text-sm font-medium py-2 px-4 rounded-md">{{ __('facility.products.create.add_image') }}</button>
                                        <button type="button" id="paste-multiple-images" class="bg-gray-100 hover:bg-gray-200 text-gray-800 text-sm font-medium py-2 px-4 rounded-md">{{ __('facility.products.create.paste_multiple') }}</button>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-2">{{ __('facility.products.create.drag_hint') }}</p>

                                    <div id="gallery-grid" class="mt-4 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 min-h-[4rem]">
                                        <!-- thumbnails rendered by JS -->
                                    </div>

                                    <!-- Hidden textarea keeps JSON for backend submission -->
                                    <textarea name="image_gallery" id="image_gallery" rows="3" class="w-full px-3 py-2 border border-gray-200 rounded-md mt-4 hidden" placeholder='["https://.../1.jpg","https://.../2.jpg"]'>{{ old('image_gallery') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Features & Options -->
                        <div id="features" class="mb-8 bg-white rounded-md border border-gray-200 p-5">
                            <h5 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                                {{ __('facility.products.create.features_options') }}
                            </h5>
                            
                            <!-- Boolean Options (generic flags only) -->
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                                <div class="flex items-center">
                                    <input type="checkbox" id="is_featured" name="is_featured" value="1" 
                                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 focus:ring-2" 
                                           {{ old('is_featured') ? 'checked' : '' }}>
                                    <label for="is_featured" class="ml-2 text-sm font-medium text-gray-700">
                                        {{ __('facility.products.create.featured') }}
                                    </label>
                                </div>
                                
                            </div>
                            
                            <!-- Features Selection -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-3">{{ __('facility.products.create.features') }}</label>
                                <div id="features-container">
                                    <p class="text-gray-500 text-sm">{{ __('facility.products.create.select_category_for_features') }}</p>
                                </div>
                            </div>

                            <!-- Property-specific numeric fields removed: managed via dynamic attributes by category -->

                            <div class="flex items-center">
                                <input type="checkbox" id="is_verified" name="is_verified" value="1" 
                                       class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 focus:ring-2" 
                                       {{ old('is_verified') ? 'checked' : '' }}>
                                <label for="is_verified" class="ml-2 text-sm font-medium text-gray-700">{{ __('facility.products.create.verified') ?? 'موثّق' }}</label>
                            </div>
                        </div>

                        <!-- Attributes -->
                        <div id="attributes" class="mb-8 bg-white rounded-md border border-gray-200 p-5">
                            <h5 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                                {{ __('facility.products.create.attributes') }}
                            </h5>
                            
                            <div id="attributes-container">
                                <p class="text-gray-500 text-sm">{{ __('facility.products.create.select_category_for_attributes') }}</p>
                            </div>
                        </div>

                        <!-- Availability -->
                        <div id="availability" class="mb-8 bg-white rounded-md border border-gray-200 p-5">
                            <h5 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">التوفر</h5>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <x-form-input 
                                        type="date"
                                        name="available_from"
                                        :label="__('facility.products.create.available_from') ?? 'متاح من'"
                                    />
                                </div>
                                <div>
                                    <x-form-input 
                                        type="date"
                                        name="available_to"
                                        :label="__('facility.products.create.available_to') ?? 'متاح إلى'"
                                    />
                                </div>
                            </div>
                        </div>

                        

                        

                    <!-- Internal Notes (moved to end) -->
                    <div class="mb-8 bg-white rounded-md border border-gray-200 p-5">
                        <h5 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">معلومات داخلية عن المنتج</h5>
                        <x-form-textarea 
                            name="additional_info"
                            :label="'معلومات داخلية عن المنتج'"
                            rows="4"
                        />
                    </div>

                    <div class="flex flex-col sm:flex-row justify-end gap-3 pt-6 border-t border-gray-200">
                        <a href="{{ route('facility.products.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-6 rounded-md transition duration-200 text-center">
                            {{ __('facility.form.cancel') }}
                        </a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-md transition duration-200">
                            {{ __('facility.products.create.create_product') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
</div>
</div>
</div>
@endsection

@push('scripts')
<script>
window.ProductCreateConfig = {
    strings: {
        locale_label: @json(__('facility.products.create.locale')),
        select_language: @json(__('facility.products.create.select_language')),
        name_label: @json(__('facility.products.create.name')),
        description_label: @json(__('facility.products.create.description')),
        remove_translation: @json(__('facility.products.create.remove_translation')),
        select_category_for_attributes: @json(__('facility.products.create.select_category_for_attributes')),
        select_category_for_features: @json(__('facility.products.create.select_category_for_features')),
        select_neighborhood: @json(__('facility.products.create.select_neighborhood')),
        select_street: @json(__('facility.products.create.select_street')),
        choose_from_map: @json(__('facility.products.create.choose_from_map')),
        map_picker_title: @json(__('facility.products.create.map_picker_title')),
        apply_coordinates: @json(__('facility.products.create.apply_coordinates')),
        remove_image: @json(__('facility.products.create.remove_image')),
        empty_gallery: @json(__('facility.products.create.empty_gallery')),
        paste_multiple_prompt: @json(__('facility.products.create.paste_multiple')),
        image_url_placeholder: @json(__('facility.products.create.image_url_placeholder')),
        copied_to_clipboard: @json('Copied'),
        invalid_coordinates: @json('Invalid coordinates'),
        loading: @json(__('facility.common.loading')),
        failed_to_load: @json(__('facility.common.failed_to_load')),
        use_my_location_error: @json(__('facility.products.create.use_my_location_error')),
        cleared: @json(__('facility.common.cleared')),
        undone: @json(__('facility.common.undone')),
        confirm_replace: @json(__('facility.products.create.confirm_replace'))
    },
    endpoints: {
        neighborhoods: '/api/v1/locations/neighborhoods',
        streets: '/api/v1/locations/streets'
    },
    dict: {
        cities: @json($cities->pluck('localized_name')->values())
    },
    flags: {
        mapPickerEnabled: true,
        locationV2Enabled: true
    }
};
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const rentFlag = document.getElementById('available_for_rent');
    const rentFields = document.getElementById('rent-offer-fields');
    if (!rentFlag || !rentFields) return;

    function syncRentFields() {
        if (rentFlag.checked) {
            rentFields.classList.remove('hidden');
        } else {
            rentFields.classList.add('hidden');
        }
    }

    rentFlag.addEventListener('change', syncRentFields);
    syncRentFields();
});
</script>
@endpush

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    #map-container { height: 420px; }
</style>
@endpush
@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
@endpush