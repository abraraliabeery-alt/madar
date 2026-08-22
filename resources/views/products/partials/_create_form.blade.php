<!-- Product create form partial -->
@php app()->setLocale('ar'); @endphp
<div class="w-full px-3 sm:px-4 lg:px-6 py-6 sm:py-8 relative">
    <div class="max-w-7xl mx-auto">
        <div class="mb-6">
            <h1 class="text-xl sm:text-2xl font-bold text-gray-800">{{ __('facility.products.create.title') }}</h1>
            <p class="text-sm text-gray-500 mt-1 max-w-2xl">{{ __('facility.products.create.basic_info') }} • {{ __('facility.products.create.location_info') }} • {{ __('facility.products.create.media') }}</p>
        </div>

        <div class="grid grid-cols-1 gap-6">
            <div>
                <details id="voice-assist" class="group mb-8 bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <summary class="p-4 sm:p-5 border-b border-gray-200 bg-white cursor-pointer list-none flex items-center justify-between gap-3">
                        <div>
                            <h5 class="text-base sm:text-lg font-semibold text-gray-800">{{ __('facility.products.create.ai_quick_fill') }}</h5>
                            <p class="text-sm text-gray-500 mt-0.5">{{ __('facility.products.create.ai_quick_fill_hint') }}</p>
                        </div>
                        <span class="text-gray-400 group-open:rotate-180 transition-transform">▼</span>
                    </summary>
                    <div class="p-4 sm:p-5 grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Voice -->
                        <div class="space-y-3">
                            <h6 class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                                <span>🎙️</span> الإملاء الصوتي
                            </h6>
                            <div class="flex flex-wrap items-center gap-2">
                                <button type="button" id="voice-start" class="inline-flex items-center gap-2 bg-transparent border border-emerald-600 hover:bg-emerald-50 text-emerald-700 text-sm font-medium py-2 px-3 sm:px-4 rounded-md">
                                    {{ __('facility.products.create.voice.start_speaking') }}
                                </button>
                                <button type="button" id="voice-stop" class="inline-flex items-center gap-2 bg-transparent border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium py-2 px-3 sm:px-4 rounded-md">
                                    {{ __('facility.products.create.voice.stop') }}
                                </button>
                                <span id="voice-status" class="text-sm text-gray-500"></span>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('facility.products.create.voice.transcript') }}</label>
                                <textarea id="voice-transcript" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-sm" placeholder="{{ __('facility.products.create.voice.transcript_placeholder') }}"></textarea>
                            </div>
                            <div class="flex flex-wrap items-center gap-2">
                                <button type="button" id="voice-analyze" class="inline-flex items-center gap-2 bg-transparent border border-emerald-600 hover:bg-emerald-50 text-emerald-700 text-sm font-medium py-2 px-3 rounded-md">
                                    {{ __('facility.products.create.voice.analyze_and_fill') }}
                                </button>
                                <button type="button" id="voice-clear" class="inline-flex items-center gap-2 bg-transparent border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium py-2 px-3 rounded-md">
                                    {{ __('facility.products.create.voice.clear_transcript') }}
                                </button>
                                <button type="button" id="voice-undo" class="inline-flex items-center gap-2 bg-transparent border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium py-2 px-3 rounded-md">
                                    {{ __('facility.products.create.voice.undo_last_fill') }}
                                </button>
                                <span id="voice-analyze-status" class="text-xs text-gray-500"></span>
                            </div>
                        </div>

                        <!-- Ad text -->
                        <div class="space-y-3">
                            <h6 class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                                <span>📝</span> تعبئة من نص إعلان
                            </h6>
                            <div>
                                <label for="ad-text-paste" class="block text-sm font-medium text-gray-700 mb-1.5">ألصق نص الإعلان أو وصف العقار</label>
                                <textarea id="ad-text-paste" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm" placeholder="مثال: شقة 3 غرف في حي الروضة ..."></textarea>
                            </div>
                            <div class="flex justify-start sm:justify-end">
                                <button type="button" id="fill-from-ad" class="inline-flex items-center gap-2 bg-transparent border border-emerald-600 hover:bg-emerald-50 text-emerald-700 text-sm font-medium py-2 px-4 rounded-md">
                                    تعبئة تلقائية من النص
                                </button>
                            </div>
                        </div>

                        <!-- Document / image -->
                        <div class="lg:col-span-2 space-y-2 border-t border-gray-100 pt-4">
                            <h6 class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                                <span>📄</span> قراءة صورة مستند أو مخطط
                            </h6>
                            <div class="flex flex-col sm:flex-row gap-2">
                                <input type="file" id="ai-property-document" accept="image/jpeg,image/png,image/webp" class="flex-1 text-sm border border-gray-300 rounded-md p-2 bg-white">
                                <button type="button" id="ai-analyze-document" class="inline-flex justify-center items-center bg-transparent border border-emerald-600 hover:bg-emerald-50 text-emerald-700 text-sm font-medium px-4 py-2 rounded-md whitespace-nowrap">قراءة ومراجعة</button>
                            </div>
                            <p class="text-xs text-gray-500">تُرسل الصورة للتحليل فقط ولا تُحفظ ضمن العقار. راجع النتائج قبل تعبئتها.</p>
                        </div>
                    </div>
                </details>
                <form id="product-create-form" method="POST" action="{{ $storeRoute }}" enctype="multipart/form-data">
                    @csrf
                    @if ($errors->any())
                        <div class="mb-6 bg-red-50 border border-red-200 rounded-md p-4">
                            <h5 class="text-sm font-semibold text-red-800 mb-2">يوجد أخطاء في النموذج:</h5>
                            <ul class="list-disc list-inside text-sm text-red-700">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                        
                    <!-- Draft Banner -->
                    <div id="draft-banner" class="hidden mb-4 bg-blue-50 border border-blue-200 rounded-md p-3 text-sm text-blue-800 flex items-center justify-between">
                        <span>يوجد مسودة محفوظة لهذا النموذج.</span>
                        <div class="flex gap-3">
                            <button type="button" id="restore-draft" class="text-blue-700 font-semibold hover:underline">استعادة المسودة</button>
                            <button type="button" id="clear-draft" class="text-red-600 hover:underline">حذف المسودة</button>
                        </div>
                    </div>

                    <!-- Stepper -->
                    <div id="form-stepper" class="mb-6 bg-white rounded-xl border border-gray-200 shadow-sm p-4 z-20">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-semibold text-gray-700" id="stepper-title">إضافة عقار</span>
                            <span class="text-xs font-medium text-emerald-600" id="completion-badge">0%</span>
                        </div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs text-gray-500" id="stepper-progress-text">1 / 6</span>
                            <span class="text-xs text-gray-500" id="completion-remaining">الحقول المطلوبة</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2 mb-3">
                            <div id="stepper-progress-bar" class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: 16%"></div>
                        </div>
                        <div id="field-hint" class="hidden mb-3 text-sm text-blue-900 bg-blue-50 border border-blue-100 rounded p-2">
                            <span class="font-semibold">تلميح:</span> <span id="field-hint-text"></span>
                        </div>
                        <div class="flex gap-2 overflow-x-auto pb-2 scroll-smooth snap-x" style="scrollbar-width: thin;">
                            <button type="button" data-target="offer-type" class="step-btn snap-start flex-none min-w-[5.5rem] py-2 px-3 rounded-md text-xs sm:text-sm text-center bg-blue-600 text-white transition">العرض</button>
                            <button type="button" data-target="basic-info" class="step-btn snap-start flex-none min-w-[5.5rem] py-2 px-3 rounded-md text-xs sm:text-sm text-center bg-gray-200 text-gray-700 transition">الأساسيات</button>
                            <button type="button" data-target="details-step" class="step-btn snap-start flex-none min-w-[5.5rem] py-2 px-3 rounded-md text-xs sm:text-sm text-center bg-gray-200 text-gray-700 transition">التفاصيل</button>
                            <button type="button" data-target="media-info" class="step-btn snap-start flex-none min-w-[5.5rem] py-2 px-3 rounded-md text-xs sm:text-sm text-center bg-gray-200 text-gray-700 transition">الوسائط</button>
                            <button type="button" data-target="location-info" class="step-btn snap-start flex-none min-w-[5.5rem] py-2 px-3 rounded-md text-xs sm:text-sm text-center bg-gray-200 text-gray-700 transition">الموقع</button>
                            <button type="button" data-target="availability" class="step-btn snap-start flex-none min-w-[5.5rem] py-2 px-3 rounded-md text-xs sm:text-sm text-center bg-gray-200 text-gray-700 transition">الحالة</button>
                            <button type="button" data-target="product-preview-card" class="step-btn snap-start flex-none min-w-[5.5rem] py-2 px-3 rounded-md text-xs sm:text-sm text-center bg-gray-200 text-gray-700 transition">المعاينة</button>
                        </div>
                        <div class="flex items-center justify-between pt-3 border-t border-gray-200 mt-2">
                            <button type="button" id="stepper-prev" class="px-4 py-2 bg-transparent border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-md text-sm transition" disabled>السابق</button>
                            <button type="button" id="stepper-next" class="px-4 py-2 bg-transparent border border-emerald-600 hover:bg-emerald-50 text-emerald-700 rounded-md text-sm transition">التالي</button>
                        </div>
                    </div>

                    <!-- Offer Type & Classification -->
                    <div id="offer-type" class="mb-8 bg-white rounded-xl border border-gray-200 p-4 sm:p-5 shadow-sm">
                        <h5 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4 pb-2 border-b border-gray-200">
                            {{ __('facility.products.create.classification_and_offer') }}
                        </h5>
                        <input type="hidden" name="main_category_id" id="main_category_id" value="{{ old('main_category_id') }}">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
                            <div class="md:col-span-2">
                                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.products.create.category') }} <span class="text-red-500">*</span></label>
                                <select id="category_id" name="category_id" required disabled class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">{{ __('facility.products.create.select_category') }}</option>
                                </select>
                                @error('category_id')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="md:col-span-1 flex flex-col gap-3 pt-1">
                                <span class="text-sm font-medium text-gray-700">{{ __('facility.products.create.offer_type') }}</span>
                                <div class="flex items-center gap-4">
                                    <div class="flex items-center gap-2">
                                        <input type="checkbox" id="available_for_sale" name="available_for_sale" value="1"
                                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 focus:ring-2"
                                               {{ old('available_for_sale') ? 'checked' : '' }}>
                                        <label for="available_for_sale" class="text-sm font-medium text-gray-700">
                                            {{ __('facility.products.create.for_sale') }}
                                        </label>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <input type="checkbox" id="available_for_rent" name="available_for_rent" value="1"
                                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 focus:ring-2"
                                               {{ old('available_for_rent') ? 'checked' : '' }}>
                                        <label for="available_for_rent" class="text-sm font-medium text-gray-700">
                                            {{ __('facility.products.create.for_rent') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                        <!-- Basic Information -->
                        <div id="basic-info" class="mb-8 bg-white rounded-xl border border-gray-200 p-4 sm:p-5 shadow-sm">
                            <h5 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4 pb-2 border-b border-gray-200">
                                {{ __('facility.products.create.basic_info') }}
                            </h5>
                            <p class="text-sm text-gray-500 mb-4">{{ __('facility.products.create.basic_info_help') }}</p>
                            
                            <div class="mb-4 flex flex-wrap gap-2">
                                <button type="button" id="ai-generate-title" class="inline-flex items-center px-4 py-2 bg-transparent border border-emerald-600 hover:bg-emerald-50 text-emerald-700 text-sm font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                    اقتراح عنوان بالذكاء
                                </button>
                                <button type="button" id="ai-generate-description" class="inline-flex items-center px-4 py-2 bg-transparent border border-emerald-600 hover:bg-emerald-50 text-emerald-700 text-sm font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                    توليد الوصف بالذكاء الاصطناعي
                                </button>
                                <button type="button" id="ai-generate-marketing" class="inline-flex items-center px-4 py-2 bg-transparent border border-emerald-600 hover:bg-emerald-50 text-emerald-700 text-sm font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                    تجهيز محتوى جميع القنوات
                                </button>
                            </div>
                            <div id="ai-marketing-results" class="hidden mb-5 rounded-md border border-indigo-200 bg-indigo-50 p-4">
                                <div class="flex items-center justify-between gap-2 mb-3">
                                    <h6 class="font-semibold text-indigo-900">المحتوى التسويقي المقترح</h6>
                                    <div class="flex items-center gap-2">
                                        <button type="button" id="ai-marketing-toggle-preview" class="text-xs bg-transparent border border-emerald-600 text-emerald-700 px-2 py-1 rounded hover:bg-emerald-50 transition">
                                            معاينة المنشورات
                                        </button>
                                        <span class="text-xs text-indigo-700">راجع النص قبل النشر</span>
                                    </div>
                                </div>
                                <div id="ai-marketing-channels" class="grid grid-cols-1 md:grid-cols-2 gap-3"></div>
                                <div id="ai-marketing-preview" class="hidden grid grid-cols-1 md:grid-cols-2 gap-3 mt-3"></div>
                            </div>
                            
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


                        <div id="rent-offer-fields" class="mb-8 bg-white rounded-xl border border-gray-200 p-4 sm:p-5 shadow-sm hidden">
                            <h5 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4 pb-2 border-b border-gray-200">{{ __('facility.products.create.rent_details') }}</h5>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <x-form-input 
                                        type="number"
                                        step="0.01"
                                        name="rent_offer[price]"
                                        :label="__('facility.products.create.price') ?? 'السعر'"
                                    />
                                    @if(isset($suggestPriceRoute))
                                        <button type="button" class="suggest-price-btn mt-2 text-xs bg-transparent hover:bg-gray-50 text-emerald-700 rounded px-2 py-1 border border-emerald-600" data-offer-type="rent">اقتراح سعر ذكي</button>
                                    @endif
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
                            </div>
                        </div>

                        <div id="sale-offer-fields" class="mb-8 bg-white rounded-xl border border-gray-200 p-4 sm:p-5 shadow-sm hidden">
                            <h5 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4 pb-2 border-b border-gray-200">{{ __('facility.products.create.sale_details') }}</h5>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <x-form-input 
                                        type="number"
                                        step="0.01"
                                        name="sale_offer[price]"
                                        :label="__('facility.products.create.price') ?? 'السعر'"
                                    />
                                    @if(isset($suggestPriceRoute))
                                        <button type="button" class="suggest-price-btn mt-2 text-xs bg-transparent hover:bg-gray-50 text-emerald-700 rounded px-2 py-1 border border-emerald-600" data-offer-type="sale">اقتراح سعر ذكي</button>
                                    @endif
                                </div>
                            </div>
                        </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                @if(isset($buildingOptions) && !empty($buildingOptions))
                                    <div class="md:col-span-1" id="building-select-wrapper">
                                        <label for="building_id" class="block text-sm font-medium text-gray-700 mb-2">العمارة</label>
                                        <select id="building_id" name="building_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <option value="">اختر العمارة</option>
                                            @foreach($buildingOptions as $id => $name)
                                                <option value="{{ $id }}" {{ old('building_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                            @endforeach
                                        </select>
                                        @error('building_id')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @endif
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

                        <!-- Media Section -->
                        <div id="media-info" class="mb-8 bg-white rounded-xl border border-gray-200 p-4 sm:p-5 shadow-sm">
                            <h5 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4 pb-2 border-b border-gray-200">
                                {{ __('facility.products.create.media') }}
                            </h5>

                            <!-- Main image -->
                            <div id="main-image-drop" class="mb-6 border-2 border-dashed border-gray-300 rounded-lg p-4 sm:p-6 hover:bg-gray-50 transition text-center bg-gray-50/30">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <i class="fas fa-image text-3xl text-gray-400"></i>
                                    <label for="main_image" class="block text-sm font-medium text-gray-700 cursor-pointer">اسحب الصورة الرئيسية هنا أو اضغط للاختيار</label>
                                    <input type="file" name="main_image" id="main_image" accept="image/*" class="w-full text-sm text-gray-700 border border-gray-300 rounded-md p-2 bg-white">
                                </div>
                                @error('main_image')
                                    <p class="text-red-600 text-sm mt-2 text-right">{{ $message }}</p>
                                @enderror
                                <div id="main-image-preview-wrap" class="mt-4 hidden">
                                    <img id="main-image-preview" src="" class="w-full h-48 sm:h-56 object-contain rounded border border-gray-200 bg-white mx-auto">
                                    <div class="flex flex-wrap items-center justify-center gap-3 mt-3">
                                        <button type="button" id="ai-analyze-main-image" class="text-sm text-purple-600 hover:text-purple-800 font-medium">
                                            توليد عنوان ووصف من الصورة
                                        </button>
                                        <button type="button" id="main-image-remove" class="text-sm text-red-600 hover:text-red-800">حذف الصورة</button>
                                    </div>
                                    <span id="ai-analyze-main-image-status" class="text-xs text-gray-500 block mt-1"></span>
                                </div>
                            </div>

                            <!-- Gallery -->
                            <div id="gallery-drop" class="mb-6 border-2 border-dashed border-gray-300 rounded-lg p-4 sm:p-6 hover:bg-gray-50 transition bg-gray-50/30">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-3">
                                    <label for="image_gallery_files" class="block text-sm font-medium text-gray-700">صور المعرض</label>
                                    <input type="file" name="image_gallery[]" id="image_gallery_files" accept="image/*" multiple class="w-full sm:w-auto text-sm text-gray-700 border border-gray-300 rounded-md p-2 bg-white">
                                </div>
                                <div class="flex flex-wrap items-center gap-3">
                                    <p class="text-xs text-gray-500">يمكنك اختيار عدة صور.</p>
                                    <button type="button" id="smart-sort-gallery" class="text-xs text-indigo-700 hover:underline">ترتيب الصور حسب الجودة</button>
                                    <button type="button" id="smart-select-cover" class="text-xs text-emerald-700 hover:underline">اختيار أفضل صورة غلاف</button>
                                </div>
                                @error('image_gallery')
                                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                                @enderror
                                <div id="gallery-preview" class="mt-3 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3"></div>
                            </div>

                            <!-- Video: URL or file -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">الفيديو</label>
                                <div class="flex gap-4 mb-2">
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="video_source" value="url" checked class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                        <span class="ml-2 text-sm">رابط فيديو</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="video_source" value="file" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                        <span class="ml-2 text-sm">ملف فيديو</span>
                                    </label>
                                </div>
                                <div id="video-url-wrap">
                                    <input type="url" name="video_url" id="video_url" placeholder="رابط YouTube / Vimeo / mp4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @error('video_url')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div id="video-file-wrap" class="hidden">
                                    <input type="file" name="video" id="video_file" accept="video/*" class="w-full text-sm text-gray-700 border border-gray-300 rounded-md p-2">
                                    @error('video')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div id="video-preview" class="mt-3 hidden">
                                    <div id="video-preview-container" class="aspect-video w-full bg-gray-50 border border-gray-200 rounded-md flex items-center justify-center text-gray-400 text-sm"></div>
                                    <button type="button" id="video-remove" class="mt-2 text-sm text-red-600 hover:text-red-800">حذف الفيديو</button>
                                </div>
                            </div>
                        </div>

                        <!-- Location Information -->
                        <div id="location-info" class="mb-8 bg-white rounded-xl border border-gray-200 p-4 sm:p-5 shadow-sm">
                            <h5 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4 pb-2 border-b border-gray-200">
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

                            <div class="flex flex-wrap items-center gap-2 mt-2">
                                <button type="button" id="extract-coordinates" class="inline-flex items-center gap-1.5 bg-transparent border border-gray-300 hover:bg-gray-50 text-gray-700 text-xs font-medium py-1.5 px-2.5 sm:px-3 rounded-md">
                                    {{ __('facility.products.create.extract_from_url') }}
                                </button>
                                <button type="button" id="clear-coordinates" class="inline-flex items-center gap-1.5 bg-transparent border border-gray-300 hover:bg-gray-50 text-gray-700 text-xs font-medium py-1.5 px-2.5 sm:px-3 rounded-md">
                                    {{ __('facility.products.create.clear_coordinates') }}
                                </button>
                                <button type="button" id="copy-coordinates" class="inline-flex items-center gap-1.5 bg-transparent border border-gray-300 hover:bg-gray-50 text-gray-700 text-xs font-medium py-1.5 px-2.5 sm:px-3 rounded-md">
                                    {{ __('facility.products.create.copy_coordinates') }}
                                </button>
                                <button type="button" id="build-maps-url" class="inline-flex items-center gap-1.5 bg-transparent border border-gray-300 hover:bg-gray-50 text-gray-700 text-xs font-medium py-1.5 px-2.5 sm:px-3 rounded-md">
                                    {{ __('facility.products.create.build_maps_url') }}
                                </button>
                                <button type="button" id="use-my-location" class="inline-flex items-center gap-1.5 bg-transparent border border-emerald-600 hover:bg-emerald-50 text-emerald-700 text-xs font-medium py-1.5 px-2.5 sm:px-3 rounded-md">
                                    {{ __('facility.products.create.use_my_location') }}
                                </button>
                            </div>

                            <div class="mt-4">
                                <button type="button" id="open-map-picker" class="inline-flex items-center gap-2 bg-transparent border border-emerald-600 hover:bg-emerald-50 text-emerald-700 text-sm font-bold py-2 px-4 rounded-md">
                                    {{ __('facility.products.create.choose_from_map') }}
                                </button>
                            </div>

                            <!-- Map Picker Modal -->
                            <div id="map-picker-modal" class="fixed inset-0 bg-black/50 hidden z-50 p-3 sm:p-6 overflow-y-auto">
                                <div class="min-h-full flex items-start justify-center">
                                    <div class="bg-white w-full max-w-3xl mx-2 sm:mx-0 mt-16 sm:mt-24 rounded-lg shadow-lg overflow-hidden">
                                        <div class="flex items-center justify-between p-4 border-b border-gray-200">
                                            <h3 class="text-base font-semibold text-gray-800">{{ __('facility.products.create.map_picker_title') }}</h3>
                                            <button type="button" id="close-map-picker" class="text-gray-500 hover:text-gray-700 text-xl leading-none">✕</button>
                                        </div>
                                        <div id="map-container" class="w-full h-[60vh] sm:h-[420px]"></div>
                                        <div class="flex flex-col-reverse sm:flex-row justify-end gap-2 sm:gap-3 p-4 border-t border-gray-200">
                                            <button type="button" id="cancel-map-picker" class="bg-transparent border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium py-2 px-4 rounded-md w-full sm:w-auto">{{ __('facility.form.cancel') }}</button>
                                            <button type="button" id="apply-map-picker" class="bg-transparent border border-emerald-600 hover:bg-emerald-50 text-emerald-700 text-sm font-medium py-2 px-4 rounded-md w-full sm:w-auto">{{ __('facility.products.create.apply_coordinates') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- Details: Attributes & Features -->
                        <div id="details-step" class="hidden">
                            <div id="attributes" class="mb-8 bg-white rounded-xl border border-gray-200 p-4 sm:p-5 shadow-sm">
                                <h5 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4 pb-2 border-b border-gray-200">
                                    {{ __('facility.products.create.attributes') }}
                                </h5>
                                
                                <div id="attributes-container">
                                    <p class="text-gray-500 text-sm">{{ __('facility.products.create.select_category_for_attributes') }}</p>
                                </div>
                            </div>

                            <!-- Features & Options -->
                            <div id="features" class="mb-8 bg-white rounded-xl border border-gray-200 p-4 sm:p-5 shadow-sm">
                                <h5 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4 pb-2 border-b border-gray-200">
                                    {{ __('facility.products.create.features_options') }}
                                </h5>
                                
                                <!-- Features Selection -->
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-3">{{ __('facility.products.create.features') }}</label>
                                    <div id="features-container">
                                        <p class="text-gray-500 text-sm">{{ __('facility.products.create.select_category_for_features') }}</p>
                                    </div>
                                </div>

                                <!-- Property-specific numeric fields removed: managed via dynamic attributes by category -->
                            </div>
                        </div>

                        <!-- Status & Availability -->
                        <div id="availability" class="mb-8 bg-white rounded-xl border border-gray-200 p-4 sm:p-5 shadow-sm">
                            <h5 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4 pb-2 border-b border-gray-200">{{ __('facility.products.create.availability') }}</h5>
                            
                            <!-- Product flags -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                                <div class="flex items-center gap-2">
                                    <input type="checkbox" id="is_featured" name="is_featured" value="1" 
                                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 focus:ring-2" 
                                           {{ old('is_featured') ? 'checked' : '' }}>
                                    <label for="is_featured" class="text-sm font-medium text-gray-700">
                                        {{ __('facility.products.create.featured') }}
                                    </label>
                                </div>
                                <div class="flex items-center gap-2">
                                    <input type="checkbox" id="is_verified" name="is_verified" value="1" 
                                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 focus:ring-2" 
                                           {{ old('is_verified') ? 'checked' : '' }}>
                                    <label for="is_verified" class="text-sm font-medium text-gray-700">
                                        {{ __('facility.products.create.verified') }}
                                    </label>
                                </div>
                            </div>

                            <div class="pt-4 border-t border-gray-100">
                                <h6 class="text-base font-semibold text-gray-800 mb-3">{{ __('facility.products.create.internal_notes') }}</h6>
                                <x-form-textarea 
                                    name="additional_info"
                                    :label="__('facility.products.create.internal_notes')"
                                    rows="4"
                                />
                            </div>
                        </div>

                        

                        


                    <!-- Live Preview -->
                    <div id="product-preview-card" class="mb-8 bg-white rounded-xl border border-gray-200 p-4 sm:p-5 shadow-sm">
                        <h5 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4 pb-2 border-b border-gray-200">{{ __('facility.products.create.product_preview') }}</h5>
                        <div id="product-preview-content" class="flex flex-col md:flex-row gap-4">
                            <img id="preview-image" src="" class="w-full md:w-1/3 h-40 object-cover rounded border border-gray-200 hidden">
                            <div class="flex-1">
                                <h4 id="preview-title" class="text-xl font-bold text-gray-900 mb-2"></h4>
                                <p id="preview-offer" class="text-sm text-gray-500 mb-2"></p>
                                <p id="preview-price" class="text-lg font-semibold text-blue-600 mb-2"></p>
                                <p id="preview-location" class="text-sm text-gray-600 mb-2"></p>
                                <p id="preview-description" class="text-sm text-gray-700"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Sticky bottom actions -->
                    <div id="form-bottom-actions" class="sticky bottom-0 w-full bg-white border-t border-gray-200 p-3 shadow-lg z-30 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-2">
                        <div class="flex flex-wrap gap-2">
                            <button type="button" id="bottom-stepper-prev" class="px-4 py-2 bg-transparent border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-md text-sm transition" disabled>السابق</button>
                            <button type="button" id="bottom-stepper-next" class="px-4 py-2 bg-transparent border border-emerald-600 hover:bg-emerald-50 text-emerald-700 rounded-md text-sm transition">التالي</button>
                            <button type="button" id="readiness-check" class="px-4 py-2 bg-transparent border border-emerald-600 hover:bg-emerald-50 text-emerald-700 rounded-md text-sm transition">
                                فحص الجاهزية
                            </button>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ $indexRoute }}" class="bg-transparent border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium py-2 px-5 rounded-md text-sm text-center transition">
                                {{ __('facility.form.cancel') }}
                            </a>
                            <button type="submit" class="bg-transparent border border-emerald-600 hover:bg-emerald-50 text-emerald-700 font-medium py-2 px-5 rounded-md text-sm transition">
                                {{ __('facility.products.create.create_product') }}
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
</div>
</div>
</div>

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
        could_not_parse_price: @json(__('facility.products.create.voice.could_not_parse_price')),
        filled_fields: @json(__('facility.products.create.voice.filled_fields')),
        analyzing: @json(__('facility.products.create.voice.analyzing')),
        listening: @json(__('facility.products.create.voice.listening')),
        converted: @json(__('facility.products.create.voice.converted')),
        loading: @json(__('facility.common.loading')),
        failed_to_load: @json(__('facility.common.failed_to_load')),
        use_my_location_error: @json(__('facility.products.create.use_my_location_error')),
        cleared: @json(__('facility.common.cleared')),
        undone: @json(__('facility.common.undone')),
        confirm_replace: @json(__('facility.products.create.confirm_replace'))
    },
    endpoints: {
        neighborhoods: '/api/v1/locations/neighborhoods',
        streets: '/api/v1/locations/streets',
        voiceProductAnalyze: '/voice/products/analyze',
        productDocumentAnalyze: '/voice/products/analyze-document'
    },
    subCategories: @json($subCategoriesList),
    mainCategoryIds: @json(array_keys($mainCategoryOptions ?? [])),
    buildingMainCategoryIds: @json($buildingMainCategoryIds ?? []),
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

// Load attributes & features when category changes
document.addEventListener('DOMContentLoaded', function() {
    const mainCategorySelect = document.getElementById('main_category_id');
    const categorySelect = document.getElementById('category_id');
    const attributesContainer = document.getElementById('attributes-container');
    const featuresContainer = document.getElementById('features-container');
    const cfg = window.ProductCreateConfig || {};
    const allSubCategories = cfg.subCategories || [];
    const oldCategoryId = @json(old('category_id'));
    const oldAttributes = @json(old('attributes'));
    const oldFeatures = @json(old('features', []));

    if (!mainCategorySelect || !categorySelect || !attributesContainer || !featuresContainer) return;

    const activeMainIds = (cfg.mainCategoryIds || []).map(String);

    const populateSubCategories = (mainId, selectedId = null) => {
        categorySelect.innerHTML = '<option value="">{{ __('facility.products.create.select_category') }}</option>';
        let subs = allSubCategories.filter(c => activeMainIds.includes(String(c.parent_id)));
        if (mainId) {
            subs = subs.filter(c => String(c.parent_id) === String(mainId));
        }
        subs.forEach(c => {
            const option = document.createElement('option');
            option.value = c.id;
            option.textContent = c.name;
            if (String(c.id) === String(selectedId)) option.selected = true;
            categorySelect.appendChild(option);
        });
        categorySelect.disabled = subs.length === 0;
    };

    const loadAttributes = (categoryId) => {
        if (!categoryId) return;
        attributesContainer.innerHTML = '<p class="text-gray-500 text-sm">جاري تحميل الخصائص...</p>';
        fetch(`/api/v1/attributes/by-category?category_id=${categoryId}&locale={{ app()->getLocale() }}`)
            .then(r => r.json())
            .then(data => {
                if (data.success && data.data.length > 0) {
                    let html = '<div class="grid grid-cols-1 md:grid-cols-2 gap-6">';
                    data.data.forEach(attr => {
                        const oldItem = (oldAttributes && oldAttributes[attr.id]) ? oldAttributes[attr.id] : null;
                        const oldValue = oldItem ? oldItem.value : '';
                        const requiredMark = attr.required ? ' <span class="text-red-500">*</span>' : '';
                        let icon = '';
                        if (attr.icon) {
                            const isFontIcon = attr.icon.includes('fa-') || !attr.icon.includes('/');
                            if (isFontIcon) {
                                icon = `<i class="${attr.icon} text-gray-500 me-2 inline-block"></i>`;
                            } else {
                                const src = attr.icon.startsWith('http') ? attr.icon : (attr.icon.startsWith('/') ? attr.icon : '/' + attr.icon);
                                icon = `<img src="${src}" alt="" width="20" height="20" class="inline-block me-2">`;
                            }
                        }
                        const symbol = attr.translated_symbol ? ` <span class="text-gray-500 text-xs mr-1">${attr.translated_symbol}</span>` : '';
                        const isRequired = attr.required ? 'required' : '';
                        let inputHtml = '';

                        if (attr.options && attr.options.length > 0) {
                            const options = attr.options.map(o => `<option value="${o}" ${String(oldValue) === String(o) ? 'selected' : ''}>${o}</option>`).join('');
                            inputHtml = `<select id="attribute_${attr.id}" name="attributes[${attr.id}][value]" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" ${isRequired}><option value=""></option>${options}</select>`;
                        } else if (attr.type === 'boolean') {
                            const checked = oldValue == 1 || oldValue === '1' || oldValue === true ? 'checked' : '';
                            inputHtml = `<input type="hidden" name="attributes[${attr.id}][value]" value="0"><input type="checkbox" id="attribute_${attr.id}" name="attributes[${attr.id}][value]" value="1" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 focus:ring-2" ${checked}>`;
                        } else if (attr.type === 'number') {
                            inputHtml = `<input type="number" step="any" id="attribute_${attr.id}" name="attributes[${attr.id}][value]" value="${oldValue}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" ${isRequired}>`;
                        } else {
                            inputHtml = `<input type="text" id="attribute_${attr.id}" name="attributes[${attr.id}][value]" value="${oldValue}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" ${isRequired}>`;
                        }

                        html += `
                            <div>
                                <label for="attribute_${attr.id}" class="block text-sm font-medium text-gray-700 mb-2">${icon}${attr.name}${requiredMark}${symbol}</label>
                                ${inputHtml}
                                <input type="hidden" name="attributes[${attr.id}][attribute_id]" value="${attr.id}">
                            </div>`;
                    });
                    html += '</div>';
                    attributesContainer.innerHTML = html;
                } else {
                    attributesContainer.innerHTML = '<p class="text-gray-500 text-sm">{{ __('facility.products.create.no_attributes_available') }}</p>';
                }
            })
            .catch(() => attributesContainer.innerHTML = '<p class="text-red-500 text-sm">{{ __('facility.products.create.error_loading_attributes') }}</p>');
    };

    const loadFeatures = (categoryId) => {
        if (!categoryId) return;
        featuresContainer.innerHTML = '<p class="text-gray-500 text-sm">جاري تحميل المميزات...</p>';
        fetch(`/api/v1/features/by-category?category_id=${categoryId}&locale={{ app()->getLocale() }}`)
            .then(r => r.json())
            .then(data => {
                if (data.success && data.data.length > 0) {
                    let html = '<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">';
                    data.data.forEach(feature => {
                        const checked = oldFeatures && oldFeatures.includes(String(feature.id)) ? 'checked' : '';
                        const icon = feature.icon ? `<img src="${feature.icon}" alt="" width="20" class="inline mr-2">` : '';
                        html += `
                            <div class="flex items-center">
                                <input type="checkbox" id="feature_${feature.id}" name="features[]" value="${feature.id}" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 focus:ring-2" ${checked}>
                                <label for="feature_${feature.id}" class="ml-2 text-sm text-gray-700">${icon}${feature.name}</label>
                            </div>`;
                    });
                    html += '</div>';
                    featuresContainer.innerHTML = html;
                } else {
                    featuresContainer.innerHTML = '<p class="text-gray-500 text-sm">{{ __('facility.products.create.no_features_available') }}</p>';
                }
            })
            .catch(() => featuresContainer.innerHTML = '<p class="text-red-500 text-sm">{{ __('facility.products.create.error_loading_features') }}</p>');
    };

    const buildingSelectWrapper = document.getElementById('building_id')?.closest('.md\\:col-span-1');

    const syncBuildingVisibility = () => {
        if (!buildingSelectWrapper || !mainCategorySelect) return;
        const ids = (cfg.buildingMainCategoryIds || []).map(String);
        buildingSelectWrapper.classList.toggle('hidden', !ids.includes(String(mainCategorySelect.value)));
    };

    mainCategorySelect.addEventListener('change', function() {
        populateSubCategories(this.value);
        attributesContainer.innerHTML = '<p class="text-gray-500 text-sm">{{ __('facility.products.create.select_category_for_attributes') }}</p>';
        featuresContainer.innerHTML = '<p class="text-gray-500 text-sm">{{ __('facility.products.create.select_category_for_features') }}</p>';
        syncBuildingVisibility();
    });

    syncBuildingVisibility();

    categorySelect.addEventListener('change', function() {
        loadAttributes(this.value);
        loadFeatures(this.value);
        const selectedSub = allSubCategories.find(c => String(c.id) === String(this.value));
        if (selectedSub && mainCategorySelect) {
            mainCategorySelect.value = selectedSub.parent_id;
        }
        syncBuildingVisibility();
    });

    if (oldCategoryId) {
        const oldSub = allSubCategories.find(c => String(c.id) === String(oldCategoryId));
        if (oldSub) {
            mainCategorySelect.value = oldSub.parent_id;
            populateSubCategories(oldSub.parent_id, oldCategoryId);
            loadAttributes(oldCategoryId);
            loadFeatures(oldCategoryId);
        } else {
            populateSubCategories('', oldCategoryId);
        }
    } else if (mainCategorySelect.value) {
        populateSubCategories(mainCategorySelect.value);
    } else {
        populateSubCategories('', null);
    }

    // AI generate description
    const aiBtn = document.getElementById('ai-generate-description');
    const aiMarketingBtn = document.getElementById('ai-generate-marketing');
    const aiMarketingResults = document.getElementById('ai-marketing-results');
    const aiMarketingChannels = document.getElementById('ai-marketing-channels');
    const aiMarketingPreview = document.getElementById('ai-marketing-preview');
    const aiMarketingTogglePreview = document.getElementById('ai-marketing-toggle-preview');
    const descriptionTextarea = document.querySelector('textarea[name^="translations"][name$="[description]"]');
    let currentMarketingContent = null;
    let marketingPreviewMode = false;

    function collectProductAiData() {
        const titleInput = document.querySelector('input[name^="translations"][name$="[title]"]');
        const attrs = [];
        attributesContainer.querySelectorAll('input, select, textarea').forEach(input => {
            if (input.type === 'hidden' || !input.value) return;
            if (input.type === 'checkbox' && !input.checked) return;
            const label = input.closest('div')?.querySelector('label')?.textContent?.trim() || input.name;
            attrs.push({ name: label, value: input.value });
        });
        const features = [];
        featuresContainer.querySelectorAll('input[type="checkbox"]:checked').forEach(cb => {
            const label = cb.closest('div')?.querySelector('label')?.textContent?.trim() || cb.value;
            features.push(label);
        });
        const salePrice = document.querySelector('input[name="sale_offer[price]"]')?.value || '';
        const rentPrice = document.querySelector('input[name="rent_offer[price]"]')?.value || '';
        const isForSale = document.getElementById('available_for_sale')?.checked;
        const isForRent = document.getElementById('available_for_rent')?.checked;
        const rentPeriod = document.querySelector('select[name="rent_offer[period]"]')?.value;
        let offerLabel = null;
        if (isForSale) offerLabel = 'للبيع';
        else if (isForRent) {
            const periodMap = {rent_daily: 'يومي', rent_monthly: 'شهري', rent_yearly: 'سنوي'};
            offerLabel = 'للإيجار' + (rentPeriod ? ' ' + (periodMap[rentPeriod] || '') : '');
        }

        return {
            title: titleInput?.value || '',
            category: categorySelect.options[categorySelect.selectedIndex]?.textContent || '',
            city: document.querySelector('select[name="city_id"] option:checked')?.textContent || '',
            neighborhood: document.querySelector('select[name="neighborhood_id"] option:checked')?.textContent || null,
            street: document.querySelector('select[name="street_id"] option:checked')?.textContent || null,
            address: document.querySelector('input[name="address"]')?.value || '',
            price: salePrice || rentPrice || null,
            offer_type: offerLabel,
            attributes: attrs,
            features
        };
    }

    async function requestProductAi(url, payload) {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            body: JSON.stringify(payload)
        });
        const data = await response.json();
        if (!response.ok || !data.success) throw new Error(data.message || 'تعذر توليد المحتوى.');
        return data;
    }

    if (aiBtn && descriptionTextarea) {
        aiBtn.addEventListener('click', async function() {
            const original = aiBtn.textContent;
            aiBtn.disabled = true;
            aiBtn.textContent = 'جاري التوليد...';
            try {
                const data = await requestProductAi('/api/v1/products/generate-description', collectProductAiData());
                descriptionTextarea.value = data.description;
                descriptionTextarea.dispatchEvent(new Event('input', { bubbles: true }));
            } catch (e) {
                alert(e.message || 'حدث خطأ في الاتصال بالذكاء الاصطناعي.');
            } finally {
                aiBtn.disabled = false;
                aiBtn.textContent = original;
            }
        });
    }

    function renderMarketingPreview(content) {
        if (!aiMarketingPreview) return;
        const title = content?.title || '';
        const description = content?.description || '';
        const whatsapp = content?.whatsapp || '';
        const x = content?.x || '';
        const instagram = content?.instagram || '';
        const emailSubject = content?.email_subject || '';
        const emailBody = content?.email_body || '';

        const card = (heading, body, className, icon) => `
            <div class="rounded border bg-white p-3 text-sm ${className}">
                <div class="flex items-center gap-2 mb-2 font-semibold text-gray-800">
                    ${icon ? '<span class="' + icon + ' text-lg ml-1">●</span>' : ''}
                    ${heading}
                </div>
                <div class="text-gray-700 whitespace-pre-wrap text-xs leading-relaxed">${escapeHtml(body)}</div>
            </div>
        `;

        const escapeHtml = (text) => {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        };

        aiMarketingPreview.innerHTML = '';
        if (title || description) {
            aiMarketingPreview.innerHTML += card('إعلان', (title ? title + '\n\n' : '') + description, 'border-indigo-200', 'text-indigo-600');
        }
        if (whatsapp) {
            aiMarketingPreview.innerHTML += card('واتساب', whatsapp, 'border-green-200', 'text-green-600');
        }
        if (x) {
            aiMarketingPreview.innerHTML += card('منصة X', x, 'border-sky-200', 'text-sky-600');
        }
        if (instagram) {
            aiMarketingPreview.innerHTML += card('إنستغرام', instagram, 'border-pink-200', 'text-pink-600');
        }
        if (emailSubject || emailBody) {
            aiMarketingPreview.innerHTML += card('بريد إلكتروني', (emailSubject ? 'الموضوع: ' + emailSubject + '\n\n' : '') + emailBody, 'border-amber-200', 'text-amber-600');
        }
    }

    function toggleMarketingPreview() {
        if (!aiMarketingChannels || !aiMarketingPreview || !aiMarketingTogglePreview) return;
        marketingPreviewMode = !marketingPreviewMode;
        if (marketingPreviewMode) {
            aiMarketingChannels.classList.add('hidden');
            aiMarketingPreview.classList.remove('hidden');
            aiMarketingTogglePreview.textContent = 'تعديل المنشورات';
        } else {
            aiMarketingChannels.classList.remove('hidden');
            aiMarketingPreview.classList.add('hidden');
            aiMarketingTogglePreview.textContent = 'معاينة المنشورات';
        }
    }

    aiMarketingTogglePreview?.addEventListener('click', () => {
        if (!currentMarketingContent) { alert('ولّد المحتوى التسويقي أولاً.'); return; }
        toggleMarketingPreview();
    });

    if (aiMarketingBtn && aiMarketingResults && aiMarketingChannels) {
        aiMarketingBtn.addEventListener('click', async function() {
            const original = aiMarketingBtn.textContent;
            aiMarketingBtn.disabled = true;
            aiMarketingBtn.textContent = 'جاري تجهيز القنوات...';
            try {
                const data = await requestProductAi('/api/v1/products/generate-marketing-content', collectProductAiData());
                const labels = {
                    title: 'عنوان الإعلان', description: 'وصف الموقع', whatsapp: 'واتساب',
                    x: 'منصة X', instagram: 'إنستغرام', email_subject: 'عنوان البريد', email_body: 'نص البريد'
                };
                aiMarketingChannels.innerHTML = '';
                Object.keys(labels).forEach(key => {
                    const value = data.content?.[key] || '';
                    if (!value) return;
                    const card = document.createElement('div');
                    card.className = 'rounded border border-indigo-200 bg-white p-3';
                    const heading = document.createElement('div');
                    heading.className = 'font-semibold text-sm text-gray-800 mb-2';
                    heading.textContent = labels[key];
                    const text = document.createElement('textarea');
                    text.className = 'w-full rounded border border-gray-200 p-2 text-sm';
                    text.rows = key === 'description' || key === 'email_body' ? 6 : 4;
                    text.value = value;
                    const actions = document.createElement('div');
                    actions.className = 'flex gap-2 mt-2';
                    const copy = document.createElement('button');
                    copy.type = 'button';
                    copy.className = 'text-xs text-indigo-700 hover:underline';
                    copy.textContent = 'نسخ';
                    copy.addEventListener('click', () => navigator.clipboard.writeText(text.value));
                    actions.appendChild(copy);
                    if (key === 'description' && descriptionTextarea) {
                        const apply = document.createElement('button');
                        apply.type = 'button';
                        apply.className = 'text-xs text-emerald-700 hover:underline';
                        apply.textContent = 'استخدامه كوصف';
                        apply.addEventListener('click', () => {
                            descriptionTextarea.value = text.value;
                            descriptionTextarea.dispatchEvent(new Event('input', { bubbles: true }));
                        });
                        actions.appendChild(apply);
                    }
                    card.append(heading, text, actions);
                    aiMarketingChannels.appendChild(card);
                });
                currentMarketingContent = data.content;
                marketingPreviewMode = false;
                aiMarketingChannels.classList.remove('hidden');
                aiMarketingPreview.classList.add('hidden');
                if (aiMarketingTogglePreview) aiMarketingTogglePreview.textContent = 'معاينة المنشورات';
                renderMarketingPreview(currentMarketingContent);
                aiMarketingResults.classList.remove('hidden');
            } catch (e) {
                alert(e.message || 'حدث خطأ في الاتصال بالذكاء الاصطناعي.');
            } finally {
                aiMarketingBtn.disabled = false;
                aiMarketingBtn.textContent = original;
            }
        });
    }

    // Offer type toggles
    const availableForSale = document.getElementById('available_for_sale');
    const availableForRent = document.getElementById('available_for_rent');
    const saleOfferFields = document.getElementById('sale-offer-fields');
    const rentOfferFields = document.getElementById('rent-offer-fields');
    function syncOfferFields() {
        saleOfferFields?.classList.toggle('hidden', !(availableForSale?.checked));
        rentOfferFields?.classList.toggle('hidden', !(availableForRent?.checked));
    }
    availableForSale?.addEventListener('change', syncOfferFields);
    availableForRent?.addEventListener('change', syncOfferFields);
    syncOfferFields();

    // Live product preview
    const previewTitle = document.getElementById('preview-title');
    const previewOffer = document.getElementById('preview-offer');
    const previewPrice = document.getElementById('preview-price');
    const previewLocation = document.getElementById('preview-location');
    const previewDescription = document.getElementById('preview-description');
    const previewImage = document.getElementById('preview-image');
    const mainImagePreviewInput = document.getElementById('main_image');
    const previewCity = document.querySelector('select[name="city_id"]');
    const previewAddress = document.querySelector('input[name="address"]');
    const salePricePreview = document.querySelector('input[name="sale_offer[price]"]');
    const rentPricePreview = document.querySelector('input[name="rent_offer[price]"]');
    function renderPreview() {
        if (previewTitle) previewTitle.textContent = titleInput?.value || '';
        if (previewDescription) previewDescription.textContent = descriptionTextarea?.value || '';
        const offer = [];
        if (availableForSale?.checked) {
            offer.push('للبيع');
            if (previewPrice) previewPrice.textContent = salePricePreview?.value ? `سعر البيع: ${salePricePreview.value}` : '';
        } else if (availableForRent?.checked) {
            offer.push('للإيجار');
            if (previewPrice) previewPrice.textContent = rentPricePreview?.value ? `سعر الإيجار: ${rentPricePreview.value}` : '';
        } else {
            if (previewPrice) previewPrice.textContent = '';
        }
        if (previewOffer) previewOffer.textContent = offer.join(' / ');
        const city = previewCity?.options[previewCity.selectedIndex]?.textContent || '';
        if (previewLocation) previewLocation.textContent = (previewAddress?.value ? previewAddress.value + ', ' : '') + city;
        if (previewImage) {
            if (mainImagePreviewInput?.files?.[0]) {
                previewImage.src = URL.createObjectURL(mainImagePreviewInput.files[0]);
                previewImage.classList.remove('hidden');
            } else {
                previewImage.classList.add('hidden');
                previewImage.src = '';
            }
        }
    }
    [titleInput, descriptionTextarea, salePricePreview, rentPricePreview, previewCity, previewAddress].forEach(el => {
        el && el.addEventListener('input', renderPreview);
    });
    availableForSale?.addEventListener('change', renderPreview);
    availableForRent?.addEventListener('change', renderPreview);
    mainImagePreviewInput?.addEventListener('change', renderPreview);
    renderPreview();

    // AI analyze main image
    const aiAnalyzeMainImage = document.getElementById('ai-analyze-main-image');
    const aiAnalyzeMainImageStatus = document.getElementById('ai-analyze-main-image-status');
    if (aiAnalyzeMainImage && mainImagePreviewInput) {
        aiAnalyzeMainImage.addEventListener('click', async () => {
            const file = mainImagePreviewInput.files[0];
            if (!file) { alert('اختر الصورة الرئيسية أولاً.'); return; }

            const formData = new FormData();
            formData.append('image', file);

            aiAnalyzeMainImage.disabled = true;
            if (aiAnalyzeMainImageStatus) aiAnalyzeMainImageStatus.textContent = 'جارٍ التحليل...';

            try {
                const res = await fetch('/api/v1/products/generate-from-image', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    body: formData
                });
                const data = await res.json();
                if (!res.ok || !data.success) throw new Error(data.message || 'تعذر التحليل.');

                if (data.title && titleInput) {
                    titleInput.value = data.title;
                    titleInput.dispatchEvent(new Event('input', { bubbles: true }));
                }
                if (data.description && descriptionTextarea) {
                    descriptionTextarea.value = data.description;
                    descriptionTextarea.dispatchEvent(new Event('input', { bubbles: true }));
                }
                renderPreview();
                updateCompletion();
                if (aiAnalyzeMainImageStatus) aiAnalyzeMainImageStatus.textContent = 'تم توليد العنوان والوصف.';
            } catch (e) {
                alert(e.message || 'حدث خطأ أثناء تحليل الصورة.');
                if (aiAnalyzeMainImageStatus) aiAnalyzeMainImageStatus.textContent = '';
            } finally {
                aiAnalyzeMainImage.disabled = false;
            }
        });
    }

    // Smart field hints
    const fieldHint = document.getElementById('field-hint');
    const fieldHintText = document.getElementById('field-hint-text');
    const helpMap = {
        main_category_id: 'اختر تصنيف العقار العام، مثل: سكني أو تجاري.',
        category_id: 'اختر الفئة الدقيقة للعقار، مثل: شقة، فيلا، أرض، عمارة.',
        city_id: 'اختر المدينة التي يقع فيها العقار.',
        neighborhood_id: 'اختر الحي بدقة لتسهيل البحث.',
        street_id: 'اختر الشارع إن توفر، أو اتركه فارغاً.',
        'address': 'اكتب العنوان التفصيلي بدقة، مثال: شارع التحلية، حي الروضة، بجوار مسجد...',
        'sale_offer[price]': 'اكتب سعر البيع بالريال السعودي بدون فواصل أو رموز.',
        'rent_offer[price]': 'اكتب قيمة الإيجار بالريال السعودي.',
        'rent_offer[period]': 'اختر مدة الإيجار: شهري، ربع سنوي، سنوي...',
        'area': 'أدخل المساحة بالمتر المربع، مثال: 120.',
        'translations[ar][title]': 'عنوان قصير وجذاب للإعلان، لا يتجاوز 120 حرفاً.',
        'translations[ar][description]': 'اكتب وصفاً واضحاً يشمل نوع العقار والموقع وأهم المميزات.',
        'main_image': 'اختر صورة واضحة وجذابة للعقار كصورة رئيسية.',
        'video_url': 'ضع رابط فيديو يوتيوب أو فيميو إن وجد.',
        'is_featured': 'حدد إذا كنت تريد تمييز الإعلان في القوائم.',
        'status_id': 'اختر حالة العقار: متاح، محجوز، مباع...'
    };

    function findHint(el) {
        const key = el?.name || el?.id;
        if (key && helpMap[key]) return helpMap[key];
        for (const name in helpMap) {
            if (key && (key.includes(name) || name.includes(key))) return helpMap[name];
        }
        return null;
    }

    if (productForm && fieldHint && fieldHintText) {
        productForm.querySelectorAll('input, select, textarea').forEach(el => {
            if (el.type === 'submit' || el.type === 'button' || el.type === 'hidden' || el.disabled) return;
            el.addEventListener('focus', () => {
                const hint = findHint(el);
                if (hint) {
                    fieldHintText.textContent = hint;
                    fieldHint.classList.remove('hidden');
                } else {
                    fieldHint.classList.add('hidden');
                }
            });
        });
    }

    // AI generate title
    const aiTitleBtn = document.getElementById('ai-generate-title');
    if (aiTitleBtn && titleInput) {
        aiTitleBtn.addEventListener('click', async function() {
            const original = aiTitleBtn.textContent;
            aiTitleBtn.disabled = true;
            aiTitleBtn.textContent = 'جاري التوليد...';
            try {
                const data = await requestProductAi('/api/v1/products/generate-marketing-content', collectProductAiData());
                if (data.content?.title) {
                    titleInput.value = data.content.title;
                    titleInput.dispatchEvent(new Event('input', { bubbles: true }));
                    renderPreview();
                }
            } catch (e) {
                alert(e.message || 'حدث خطأ في الاتصال بالذكاء الاصطناعي.');
            } finally {
                aiTitleBtn.disabled = false;
                aiTitleBtn.textContent = original;
            }
        });
    }

    // Fill from ad text
    const adTextPaste = document.getElementById('ad-text-paste');
    const fillFromAdBtn = document.getElementById('fill-from-ad');
    const voiceTranscript = document.getElementById('voice-transcript');
    const voiceAnalyze = document.getElementById('voice-analyze');
    if (fillFromAdBtn && adTextPaste && voiceTranscript && voiceAnalyze) {
        fillFromAdBtn.addEventListener('click', () => {
            const text = adTextPaste.value.trim();
            if (!text) { alert('الصق نص الإعلان أولاً.'); return; }
            voiceTranscript.value = text;
            voiceAnalyze.click();
            adTextPaste.value = '';
        });
    }

    // Readiness check
    const readinessCheck = document.getElementById('readiness-check');
    if (readinessCheck && productForm) {
        readinessCheck.addEventListener('click', () => {
            const required = productForm.querySelectorAll('[required]');
            const missing = [];
            required.forEach(el => {
                if (el.disabled) return;
                const isFilled = (el.type === 'checkbox' || el.type === 'radio') ? el.checked : (el.tagName === 'SELECT' ? (!!el.value && el.value !== '') : !!el.value.trim());
                if (!isFilled) {
                    missing.push(el);
                    el.classList.add('border-red-500', 'ring-1', 'ring-red-500');
                } else {
                    el.classList.remove('border-red-500', 'ring-1', 'ring-red-500');
                }
            });
            if (missing.length === 0) {
                alert('كل الحقول المطلوبة ممتلئة. العقار جاهز للنشر.');
                return;
            }
            const names = missing.slice(0, 5).map(el => {
                const label = productForm.querySelector(`label[for="${el.id}"]`) || el.closest('div')?.querySelector('label');
                return label?.textContent?.trim() || el.name || 'حقل مطلوب';
            });
            const first = missing[0];
            const stepOfFirst = sectionIds.findIndex(id => document.getElementById(id)?.contains(first));
            if (stepOfFirst >= 0) goToStep(stepOfFirst);
            alert(`يوجد ${missing.length} حقول مطلوبة ناقصة:\n- ${names.join('\n- ')}` + (missing.length > 5 ? '\n...' : ''));
            setTimeout(() => first?.scrollIntoView({ behavior: 'smooth', block: 'center' }), 100);
        });
    }
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Main image preview
    const mainInput = document.getElementById('main_image');
    const mainWrap = document.getElementById('main-image-preview-wrap');
    const mainImg = document.getElementById('main-image-preview');
    const mainRemove = document.getElementById('main-image-remove');
    if (mainInput && mainWrap && mainImg) {
        mainInput.addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                mainImg.src = URL.createObjectURL(file);
                mainWrap.classList.remove('hidden');
            }
        });
        if (mainRemove) {
            mainRemove.addEventListener('click', function () {
                mainInput.value = '';
                mainImg.src = '';
                mainWrap.classList.add('hidden');
            });
        }
    }

    // Gallery preview with remove
    const galleryInput = document.getElementById('image_gallery_files');
    const galleryPreview = document.getElementById('gallery-preview');
    let galleryFiles = new DataTransfer();
    function renderGallery() {
        if (!galleryPreview) return;
        galleryPreview.innerHTML = '';
        for (let i = 0; i < galleryFiles.files.length; i++) {
            const file = galleryFiles.files[i];
            const url = URL.createObjectURL(file);
            const wrap = document.createElement('div');
            wrap.className = 'relative group border border-gray-200 rounded-md overflow-hidden bg-white';
            wrap.innerHTML = '<img src="' + url + '" class="w-full h-28 object-cover"><button type="button" data-idx="' + i + '" class="absolute top-1 right-1 bg-white/90 border border-red-200 text-red-600 text-xs px-2 py-1 rounded-md opacity-0 group-hover:opacity-100">حذف</button>';
            galleryPreview.appendChild(wrap);
            wrap.querySelector('button').addEventListener('click', function () {
                const idx = parseInt(this.dataset.idx);
                const dt = new DataTransfer();
                for (let j = 0; j < galleryFiles.files.length; j++) {
                    if (j !== idx) dt.items.add(galleryFiles.files[j]);
                }
                galleryFiles = dt;
                galleryInput.files = galleryFiles.files;
                renderGallery();
            });
        }
    }
    if (galleryInput) {
        galleryInput.addEventListener('change', function () {
            const dt = new DataTransfer();
            for (let f of galleryFiles.files) dt.items.add(f);
            for (let f of this.files) dt.items.add(f);
            galleryFiles = dt;
            this.files = galleryFiles.files;
            renderGallery();
        });
    }

    async function rankedGalleryFiles() {
        const unique = [];
        const seen = new Set();
        for (const file of galleryFiles.files) {
            const signature = [file.name, file.size, file.lastModified].join(':');
            if (seen.has(signature)) continue;
            seen.add(signature);
            let score = file.size;
            try {
                const bitmap = await createImageBitmap(file);
                score = bitmap.width * bitmap.height;
                bitmap.close();
            } catch (_) {}
            unique.push({ file, score });
        }
        return unique.sort((a, b) => b.score - a.score).map(item => item.file);
    }

    document.getElementById('smart-sort-gallery')?.addEventListener('click', async function(){
        const files = await rankedGalleryFiles();
        if (!files.length) return alert('أضف صور المعرض أولاً.');
        const dt = new DataTransfer();
        files.forEach(file => dt.items.add(file));
        galleryFiles = dt;
        galleryInput.files = galleryFiles.files;
        renderGallery();
    });

    document.getElementById('smart-select-cover')?.addEventListener('click', async function(){
        const files = await rankedGalleryFiles();
        if (!files.length || !mainInput) return alert('أضف صور المعرض أولاً.');
        const dt = new DataTransfer();
        dt.items.add(files[0]);
        mainInput.files = dt.files;
        mainInput.dispatchEvent(new Event('change', { bubbles: true }));
    });

    // Video: URL or file
    const urlRadio = document.querySelector('input[name="video_source"][value="url"]');
    const fileRadio = document.querySelector('input[name="video_source"][value="file"]');
    const urlWrap = document.getElementById('video-url-wrap');
    const fileWrap = document.getElementById('video-file-wrap');
    const videoUrl = document.getElementById('video_url');
    const videoFile = document.getElementById('video_file');
    const videoPreview = document.getElementById('video-preview');
    const videoPreviewContainer = document.getElementById('video-preview-container');
    const videoRemove = document.getElementById('video-remove');
    function renderVideo(src) {
        if (!videoPreviewContainer) return;
        videoPreviewContainer.innerHTML = '';
        if (!src) {
            videoPreview?.classList.add('hidden');
            return;
        }
        const yt = src.match(/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([\w-]{11})/);
        const vm = src.match(/vimeo\.com\/(\d+)/);
        if (yt) {
            videoPreviewContainer.innerHTML = '<iframe src="https://www.youtube.com/embed/' + yt[1] + '" class="w-full h-full" frameborder="0" allowfullscreen></iframe>';
        } else if (vm) {
            videoPreviewContainer.innerHTML = '<iframe src="https://player.vimeo.com/video/' + vm[1] + '" class="w-full h-full" frameborder="0" allowfullscreen></iframe>';
        } else if (/\.(mp4|webm|ogg)(\?.*)?$/i.test(src)) {
            videoPreviewContainer.innerHTML = '<video src="' + src + '" controls class="w-full h-full"></video>';
        } else {
            const a = document.createElement('a');
            a.href = src; a.target = '_blank'; a.textContent = src;
            videoPreviewContainer.appendChild(a);
        }
        videoPreview?.classList.remove('hidden');
    }
    if (urlRadio && fileRadio) {
        function syncVideoSource() {
            if (urlRadio.checked) {
                urlWrap?.classList.remove('hidden');
                fileWrap?.classList.add('hidden');
                if (videoFile) videoFile.value = '';
                if (videoPreview) videoPreview.classList.add('hidden');
            } else {
                urlWrap?.classList.add('hidden');
                fileWrap?.classList.remove('hidden');
                if (videoUrl) videoUrl.value = '';
                if (videoPreview) videoPreview.classList.add('hidden');
            }
        }
        urlRadio.addEventListener('change', syncVideoSource);
        fileRadio.addEventListener('change', syncVideoSource);
        syncVideoSource();
    }
    if (videoUrl) videoUrl.addEventListener('input', function () { renderVideo(this.value); });
    if (videoFile) videoFile.addEventListener('change', function () {
        const file = this.files[0];
        if (file) renderVideo(URL.createObjectURL(file));
    });
    if (videoRemove) {
        videoRemove.addEventListener('click', function () {
            if (videoUrl) videoUrl.value = '';
            if (videoFile) videoFile.value = '';
            if (videoPreview) videoPreview.classList.add('hidden');
        });
    }

    // Drag & drop handlers
    function setupDropZone(dropEl, inputEl, isMultiple) {
        if (!dropEl || !inputEl) return;
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(evt => {
            dropEl.addEventListener(evt, function (e) {
                e.preventDefault();
                e.stopPropagation();
            });
        });
        ['dragenter', 'dragover'].forEach(evt => {
            dropEl.addEventListener(evt, function () {
                dropEl.classList.add('bg-blue-50', 'border-blue-400');
            });
        });
        ['dragleave', 'drop'].forEach(evt => {
            dropEl.addEventListener(evt, function () {
                dropEl.classList.remove('bg-blue-50', 'border-blue-400');
            });
        });
        dropEl.addEventListener('drop', function (e) {
            const files = e.dataTransfer.files;
            if (!files.length) return;
            if (isMultiple) {
                const dt = new DataTransfer();
                for (let f of galleryFiles.files) dt.items.add(f);
                for (let f of files) dt.items.add(f);
                galleryFiles = dt;
                inputEl.files = galleryFiles.files;
                renderGallery();
            } else {
                inputEl.files = files;
                inputEl.dispatchEvent(new Event('change'));
            }
        });
    }
    setupDropZone(document.getElementById('main-image-drop'), mainInput, false);
    setupDropZone(document.getElementById('gallery-drop'), galleryInput, true);
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('product-create-form');
    const DRAFT_KEY = 'product_create_draft';
    const banner = document.getElementById('draft-banner');
    const restoreBtn = document.getElementById('restore-draft');
    const clearBtn = document.getElementById('clear-draft');
    if (!form) return;

    function hasDraft() { return !!localStorage.getItem(DRAFT_KEY); }
    function showBanner() { if (hasDraft()) banner?.classList.remove('hidden'); }
    function hideBanner() { banner?.classList.add('hidden'); }

    function saveDraft() {
        const data = {};
        for (const el of form.elements) {
            if (!el.name || el.type === 'file' || el.type === 'submit' || el.type === 'button' || el.name === '_token' || el.name.startsWith('_token')) continue;
            if (el.type === 'checkbox' || el.type === 'radio') {
                if (el.checked) data[el.name] = el.value;
                else if (!data.hasOwnProperty(el.name)) data[el.name] = '';
            } else {
                data[el.name] = el.value;
            }
        }
        localStorage.setItem(DRAFT_KEY, JSON.stringify(data));
    }

    function restoreDraft() {
        const json = localStorage.getItem(DRAFT_KEY);
        if (!json) return;
        const data = JSON.parse(json);
        for (const el of form.elements) {
            if (!el.name || !data.hasOwnProperty(el.name) || el.type === 'file' || el.type === 'submit' || el.type === 'button' || el.name === '_token') continue;
            if (el.type === 'checkbox' || el.type === 'radio') {
                el.checked = String(el.value) === String(data[el.name]);
            } else {
                el.value = data[el.name];
            }
        }
        const mainCategory = form.elements['main_category_id'];
        if (mainCategory) {
            mainCategory.dispatchEvent(new Event('change'));
            setTimeout(() => {
                const category = form.elements['category_id'];
                if (category && data['category_id']) {
                    category.value = data['category_id'];
                    category.dispatchEvent(new Event('change'));
                }
                ['available_for_sale', 'available_for_rent'].forEach(n => {
                    const cb = form.elements[n];
                    if (cb) cb.dispatchEvent(new Event('change'));
                });
            }, 50);
        }
        hideBanner();
    }

    function clearDraft() {
        localStorage.removeItem(DRAFT_KEY);
        hideBanner();
    }

    let timeout;
    form.addEventListener('input', () => { clearTimeout(timeout); timeout = setTimeout(saveDraft, 1000); });
    form.addEventListener('change', () => { clearTimeout(timeout); timeout = setTimeout(saveDraft, 1000); });
    form.addEventListener('submit', clearDraft);
    restoreBtn?.addEventListener('click', restoreDraft);
    clearBtn?.addEventListener('click', clearDraft);
    showBanner();
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const suggestPriceRoute = @json($suggestPriceRoute ?? null);
    if (!suggestPriceRoute) return;
    document.querySelectorAll('.suggest-price-btn').forEach(btn => {
        btn.addEventListener('click', async () => {
            const categoryId = document.querySelector('select[name="category_id"]')?.value;
            const cityId = document.querySelector('select[name="city_id"]')?.value;
            if (!categoryId || !cityId) { alert('اختر الفئة والمدينة أولاً'); return; }
            let offerType = btn.dataset.offerType;
            if (offerType === 'rent') {
                const period = document.querySelector('select[name="rent_offer[period]"]')?.value;
                offerType = period || 'rent_monthly';
            }
            try {
                const res = await fetch(suggestPriceRoute + '?offer_type=' + encodeURIComponent(offerType) + '&category_id=' + encodeURIComponent(categoryId) + '&city_id=' + encodeURIComponent(cityId));
                const data = await res.json();
                if (data.price) {
                    const input = btn.parentElement.querySelector('input[type="number"]');
                    if (input) { input.value = data.price; input.dispatchEvent(new Event('input')); }
                } else {
                    alert('لا توجد بيانات كافية لاقتراح السعر');
                }
            } catch (e) { console.error(e); }
        });
    });
});
</script>
<script src="{{ asset('js/facility/products-create.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const stepper = document.getElementById('form-stepper');
    const stepButtons = stepper ? stepper.querySelectorAll('.step-btn') : [];
    const progressBar = document.getElementById('stepper-progress-bar');
    const progressText = document.getElementById('stepper-progress-text');
    const prevBtn = document.getElementById('stepper-prev');
    const nextBtn = document.getElementById('stepper-next');
    const bottomPrev = document.getElementById('bottom-stepper-prev');
    const bottomNext = document.getElementById('bottom-stepper-next');
    const completionBadge = document.getElementById('completion-badge');
    const completionRemaining = document.getElementById('completion-remaining');
    const sectionIds = ['offer-type','basic-info','details-step','media-info','location-info','availability','product-preview-card'];
    let activeStep = 0;

    const updateCompletion = () => {
        const form = document.getElementById('product-create-form');
        if (!form) return;
        const required = form.querySelectorAll('[required]');
        let total = 0, filled = 0;
        required.forEach(el => {
            if (el.disabled) return;
            total++;
            let hasValue = false;
            if (el.type === 'checkbox' || el.type === 'radio') {
                hasValue = el.checked;
            } else if (el.tagName === 'SELECT') {
                hasValue = !!el.value && el.value !== '';
            } else {
                hasValue = !!el.value.trim();
            }
            if (hasValue) filled++;
        });
        const percent = total ? Math.round((filled / total) * 100) : 0;
        if (completionBadge) completionBadge.textContent = percent + '%';
        if (completionRemaining) completionRemaining.textContent = 'تم تعبئة ' + filled + ' من ' + total + ' حقول مطلوبة';
    };

    const setActiveStep = (index) => {
        activeStep = index;
        let activePanel = null;
        sectionIds.forEach((id, i) => {
            const panel = document.getElementById(id);
            if (!panel) return;
            if (i === index) {
                panel.classList.remove('hidden');
                activePanel = panel;
            } else {
                panel.classList.add('hidden');
            }
        });
        if (activePanel) {
            setTimeout(() => {
                activePanel.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }, 10);
        }
        stepButtons.forEach((b, i) => {
            if (i === index) {
                b.classList.add('bg-blue-600', 'text-white');
                b.classList.remove('bg-gray-200', 'text-gray-700');
            } else {
                b.classList.remove('bg-blue-600', 'text-white');
                b.classList.add('bg-gray-200', 'text-gray-700');
            }
        });
        if (progressBar) progressBar.style.width = ((index + 1) / sectionIds.length * 100) + '%';
        if (progressText) progressText.textContent = (index + 1) + ' / ' + sectionIds.length;
        if (prevBtn) prevBtn.disabled = index === 0;
        if (nextBtn) nextBtn.textContent = index === sectionIds.length - 1 ? 'إنهاء' : 'التالي';
        if (bottomPrev) bottomPrev.disabled = index === 0;
        if (bottomNext) bottomNext.textContent = index === sectionIds.length - 1 ? 'إنهاء' : 'التالي';
        updateCompletion();
    };

    const goToStep = (index) => {
        if (index < 0 || index >= sectionIds.length) return;
        setActiveStep(index);
    };

    stepButtons.forEach((btn, i) => {
        btn.addEventListener('click', () => goToStep(i));
    });

    if (prevBtn) prevBtn.addEventListener('click', () => goToStep(activeStep - 1));
    if (nextBtn) nextBtn.addEventListener('click', () => goToStep(activeStep + 1));
    if (bottomPrev) bottomPrev.addEventListener('click', () => goToStep(activeStep - 1));
    if (bottomNext) bottomNext.addEventListener('click', () => goToStep(activeStep + 1));

    const maybeAutoAdvance = () => {
        const panel = document.getElementById(sectionIds[activeStep]);
        if (!panel) return;
        const required = panel.querySelectorAll('[required]');
        if (required.length === 0) return;
        let allFilled = true;
        required.forEach(el => {
            if (el.disabled) return;
            if (el.type === 'checkbox' || el.type === 'radio') {
                if (!el.checked) allFilled = false;
            } else if (el.tagName === 'SELECT') {
                if (!el.value || el.value === '') allFilled = false;
            } else {
                if (!el.value.trim()) allFilled = false;
            }
        });
        if (allFilled && activeStep < sectionIds.length - 1) {
            goToStep(activeStep + 1);
        }
    };

    let autoAdvanceTimeout;
    const productForm = document.getElementById('product-create-form');
    if (productForm) {
        productForm.addEventListener('input', () => {
            updateCompletion();
            clearTimeout(autoAdvanceTimeout);
            autoAdvanceTimeout = setTimeout(maybeAutoAdvance, 400);
        });
        productForm.addEventListener('change', () => {
            updateCompletion();
            clearTimeout(autoAdvanceTimeout);
            autoAdvanceTimeout = setTimeout(maybeAutoAdvance, 100);
        });
    }

    setActiveStep(0);
});
</script>
@endpush

@push('styles')
@if($loadTailwind ?? false)
<script src="https://cdn.tailwindcss.com"></script>
<script>tailwind.config = { corePlugins: { preflight: false } }</script>
@endif
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    #map-container { height: 60vh; max-height: 420px; }
    .scroll-smooth { scroll-behavior: smooth; }
    .snap-x { scroll-snap-type: x mandatory; }
    .snap-start { scroll-snap-align: start; }
</style>
@endpush
@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
@endpush

@if(auth()->check() && request()->routeIs('admin.*'))
<!-- AI assistant widget -->
<div id="ai-assistant-widget" class="fixed bottom-28 sm:bottom-24 left-3 sm:left-4 z-40">
    <button id="ai-assistant-toggle" type="button" class="bg-transparent border border-emerald-600 hover:bg-emerald-50 text-emerald-700 rounded-full px-3 py-2.5 sm:px-4 sm:py-3 shadow-lg text-sm font-medium transition">
        مساعد AI
    </button>
    <div id="ai-assistant-panel" class="hidden fixed sm:absolute bottom-20 sm:bottom-14 left-3 right-3 sm:left-0 sm:right-auto w-auto sm:w-96 bg-white border border-gray-200 rounded-lg shadow-xl flex flex-col overflow-hidden" style="max-height: 28rem;">
        <div class="bg-purple-600 text-white px-4 py-2 text-sm font-medium flex justify-between items-center">
            <span>مساعد الإدخال الذكي</span>
            <button id="ai-assistant-close" type="button" class="text-white hover:text-gray-200 text-lg leading-none">&times;</button>
        </div>
        <div id="ai-assistant-messages" class="p-3 h-64 overflow-y-auto text-sm space-y-3 bg-gray-50"></div>
        <div class="p-3 border-t border-gray-200 flex gap-2 bg-white">
            <input type="text" id="ai-assistant-input" class="flex-1 border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500" placeholder="اسأل عن أي حقل...">
            <button id="ai-assistant-send" type="button" class="bg-transparent border border-emerald-600 hover:bg-emerald-50 text-emerald-700 px-3 py-2 rounded-md text-sm transition">إرسال</button>
        </div>
    </div>
</div>
@endif

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const aiAssistantUrl = @json(route('admin.ai-assistant'));
    const toggle = document.getElementById('ai-assistant-toggle');
    const close = document.getElementById('ai-assistant-close');
    const panel = document.getElementById('ai-assistant-panel');
    const input = document.getElementById('ai-assistant-input');
    const send = document.getElementById('ai-assistant-send');
    const messages = document.getElementById('ai-assistant-messages');
    if (!toggle || !panel || !input) return;

    function appendMessage(text, isUser) {
        const div = document.createElement('div');
        div.className = isUser ? 'text-right' : 'text-left';
        const bubble = document.createElement('div');
        bubble.className = isUser ? 'inline-block bg-purple-100 text-purple-900 px-3 py-2 rounded-lg' : 'inline-block bg-white border border-gray-200 text-gray-800 px-3 py-2 rounded-lg';
        bubble.textContent = text;
        div.appendChild(bubble);
        messages.appendChild(div);
        messages.scrollTop = messages.scrollHeight;
    }

    toggle.addEventListener('click', () => panel.classList.toggle('hidden'));
    close?.addEventListener('click', () => panel.classList.add('hidden'));

    async function sendQuestion() {
        const q = input.value.trim();
        if (!q) return;
        appendMessage(q, true);
        input.value = '';
        appendMessage('...', false);
        const loading = messages.lastChild;
        try {
            const res = await fetch(aiAssistantUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify({ question: q })
            });
            const data = await res.json();
            messages.removeChild(loading);
            appendMessage(data.success ? (data.data?.answer || 'لا توجد إجابة.') : (data.message || 'تعذر الرد.'), false);
        } catch (e) {
            messages.removeChild(loading);
            appendMessage('تعذر الاتصال بخدمة المساعد.', false);
        }
    }

    send?.addEventListener('click', sendQuestion);
    input.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') sendQuestion();
    });
});
</script>
@endpush

