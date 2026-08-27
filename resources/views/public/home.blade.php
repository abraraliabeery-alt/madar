@extends('layouts.app')

@section('title', 'مدار التفاوض')

@section('meta')
<meta name="language" content="{{ app()->getLocale() }}">
<meta name="language-alternate" content="{{ app()->getLocale() === 'ar' ? 'en' : 'ar' }}">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<meta property="og:title" content="مدار التفاوض">
<meta property="og:site_name" content="مدار التفاوض">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="twitter:title" content="مدار التفاوض">
@endsection

@section('content')
<div class="relative -mt-16 bg-gradient-to-br from-slate-50 via-white to-slate-100 dark:from-slate-900 dark:via-slate-950 dark:to-slate-900">
    <div class="hero-shell relative z-0 overflow-hidden w-full pb-20 sm:pb-24 lg:pb-32 mb-10 sm:mb-14 rounded-b-[1.5rem] sm:rounded-b-[2.5rem] lg:rounded-b-[3rem]">
        <div class="absolute inset-0 overflow-hidden hero-media">
            <video autoplay muted loop playsinline preload="auto">
                <source src="{{ asset('images/1.mp4') }}" type="video/mp4">
            </video>
            <!-- Readability scrim: keeps the white hero copy legible over any video frame -->
            <div class="hero-scrim"></div>
            <div class="absolute bottom-0 left-0 right-0 h-64 sm:h-80 bg-slate-900/40 backdrop-blur-sm pointer-events-none"
                 style="-webkit-mask-image: linear-gradient(to top, black 0%, black 30%, transparent 100%); mask-image: linear-gradient(to top, black 0%, black 30%, transparent 100%);"></div>
        </div>
    <!-- Hero Section -->
    <div class="relative text-center px-4 sm:px-6 lg:px-8 pt-24 sm:pt-32 lg:pt-44 pb-14 sm:pb-20 lg:pb-24 hero z-10">
        <div class="relative">
            @php($mode = \App\Helpers\PlatformModeHelper::getMode())
            <h1 class="font-bold text-white mb-3 sm:mb-4 hero-title drop-shadow-md text-balance">
                @if($mode === \App\Helpers\PlatformModeHelper::MODE_REAL_ESTATE)
                    مدار التفاوض — منصة العقارات الذكية
                @elseif($mode === \App\Helpers\PlatformModeHelper::MODE_CONTRACTING)
                    منصة المقاولات — اعتمد واطرح مشروعك
                @else
                    مدار التفاوض — دورة حياة العقار
                @endif
            </h1>
            <p class="hero-subtitle text-white/85 mb-7 sm:mb-10 max-w-2xl mx-auto leading-relaxed text-pretty drop-shadow-sm">
                @if($mode === \App\Helpers\PlatformModeHelper::MODE_REAL_ESTATE)
                    ابحث عن عقارك، قارن العروض، وابدأ التفاوض بثقة—في تجربة واحدة.
                @elseif($mode === \App\Helpers\PlatformModeHelper::MODE_CONTRACTING)
                    اطرح منافستك، استقبل عروض المقاولين، وقارن بسرعة عبر منصة واحدة.
                @else
                    من البحث والشراء/الإيجار إلى التنفيذ والعقود—كلها في منصة واحدة.
                @endif
            </p>
        </div>

        <!-- Search Form -->
        <div class="max-w-5xl mx-auto text-start">
            <div class="search-card bg-black/45 backdrop-blur-md rounded-2xl border border-white/20 shadow-2xl overflow-hidden">
                <div class="divide-y divide-white/10">
                    <button type="button" class="accordion-trigger" onclick="toggleHomeSection('home-basic')" aria-expanded="true" aria-controls="home-basic">
                        <span>{{ __('general.home.quick_search') }}</span>
                        <i class="fas fa-chevron-up" data-accordion-icon="home-basic"></i>
                    </button>
                    <div id="home-basic" class="px-4 sm:px-5 pb-5">
                        @if(\App\Helpers\PlatformModeHelper::getMode() === \App\Helpers\PlatformModeHelper::MODE_CONTRACTING)
                            <form action="{{ route('public.execution.marketplace') }}" method="GET" class="search-form">
                                <input type="text"
                                       class="search-input"
                                       name="q"
                                       placeholder="{{ __('general.home.search.quick_placeholder_contracting') }}"
                                       required>
                                <button class="search-button" type="submit">
                                    <i class="fas fa-search"></i>
                                    <span class="sm:hidden">{{ __('public.search.title') }}</span>
                                </button>
                            </form>
                        @else
                            <form action="{{ route('public.search') }}" method="GET" class="search-form">
                                <input type="text"
                                       class="search-input"
                                       name="q"
                                       placeholder="{{ __('general.home.search.quick_placeholder_' . \App\Helpers\PlatformModeHelper::getMode()) }}"
                                       required>
                                <button class="search-button" type="submit">
                                    <i class="fas fa-search"></i>
                                    <span class="sm:hidden">{{ __('public.search.title') }}</span>
                                </button>
                            </form>
                        @endif
                    </div>

                    <button type="button" class="accordion-trigger" onclick="toggleHomeSection('home-advanced')" aria-expanded="false" aria-controls="home-advanced">
                        <span>{{ __('general.home.advanced_search') }}</span>
                        <i class="fas fa-chevron-down" data-accordion-icon="home-advanced"></i>
                    </button>
                    @if(\App\Helpers\PlatformModeHelper::getMode() === \App\Helpers\PlatformModeHelper::MODE_CONTRACTING)
                    <div id="home-advanced" class="px-4 sm:px-5 pb-5 hidden">
                        <form id="homeAdvancedSearchForm" action="{{ route('public.execution.marketplace') }}" method="GET">
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                                <input type="text" id="home_q" name="q" class="field" placeholder="{{ __('public.search.search_term') }}">
                                <input type="text" id="home_type" name="type" class="field" placeholder="{{ __('general.home.search.work_type') }}">
                                <input type="number" id="home_min_budget" name="min_budget" class="field" placeholder="{{ __('general.home.search.budget_min') }}">
                                <input type="number" id="home_max_budget" name="max_budget" class="field" placeholder="{{ __('general.home.search.budget_max') }}">
                            </div>

                            <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <select id="home_status" name="status" class="field">
                                    <option value="open" selected>{{ __('general.home.search.status') }}</option>
                                    <option value="closed">{{ __('general.home.search.closed') }}</option>
                                    <option value="all">{{ __('general.home.search.all') }}</option>
                                </select>
                                <select id="home_sort" name="sort" class="field">
                                    <option value="latest">{{ __('general.home.search.sort_by') }}</option>
                                    <option value="due_date">{{ __('general.home.search.due_date') }}</option>
                                    <option value="budget_low">{{ __('general.home.search.budget_low') }}</option>
                                    <option value="budget_high">{{ __('general.home.search.budget_high') }}</option>
                                    <option value="bids">{{ __('general.home.search.bids') }}</option>
                                </select>
                            </div>

                            <div class="mt-5 flex flex-col sm:flex-row gap-3 sm:justify-center">
                                <button type="submit" class="btn-solid">
                                    <i class="fas fa-search"></i>
                                    <span>{{ __('public.search.title') }}</span>
                                </button>
                                <a href="{{ route('public.execution.marketplace') }}" class="btn-ghost">
                                    <i class="fas fa-list"></i>
                                    <span>{{ __('general.home.search.view_marketplace') }}</span>
                                </a>
                            </div>
                        </form>
                    </div>
                    @else
                    <div id="home-advanced" class="px-4 sm:px-5 pb-5 hidden">
                        <form id="homeAdvancedSearchForm" action="{{ route('public.products.index') }}" method="GET">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <select id="home_subcategory_id" name="category_id" class="field sm:col-span-1">
                                    <option value="">{{ __('public.common.category') }}</option>
                                </select>
                                <input type="number" id="home_min_price" name="min_price" class="field" placeholder="{{ __('general.home.search.price_min') }}">
                                <input type="number" id="home_max_price" name="max_price" class="field" placeholder="{{ __('general.home.search.price_max') }}">
                            </div>

                            <div id="home_attributes_container" class="mt-3 hidden">
                                <div id="home_attributes_list" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3"></div>
                            </div>

                            <div class="mt-5 flex flex-col sm:flex-row gap-3 sm:justify-center">
                                <button type="submit" class="btn-solid">
                                    <i class="fas fa-search"></i>
                                    <span>{{ __('public.search.title') }}</span>
                                </button>
                            </div>
                        </form>

                        <script>
                        (function() {
                            const categories = @json(($searchCategories ?? collect())->map(fn($c) => ['id' => $c->id, 'parent_id' => $c->parent_id, 'name' => $c->getTranslatedName()]));
                            const subSelect = document.getElementById('home_subcategory_id');
                            const attributesContainer = document.getElementById('home_attributes_container');
                            const attributesList = document.getElementById('home_attributes_list');
                            const attributesApiUrl = '{{ url("/api/v1/attributes/by-category") }}';
                            const currentLocale = '{{ app()->getLocale() }}';

                            function populateCategories() {
                                const subs = categories.filter(c => c.parent_id !== null);
                                subSelect.innerHTML = '';
                                subSelect.appendChild(new Option('{{ __('public.search.all_categories') }}', ''));
                                subs.forEach(c => subSelect.appendChild(new Option(c.name, c.id)));
                            }

                            function createField(attr) {
                                const wrapper = document.createElement('div');
                                const attrName = attr.name || attr.key || ('Attribute ' + attr.id);
                                const labelText = attr.translated_symbol ? (attrName + ' (' + attr.translated_symbol + ')') : attrName;
                                const options = Array.isArray(attr.options) ? attr.options : [];

                                const attrType = (attr.type || 'text').toLowerCase();
                                const inputBaseClass = 'field';

                                if (options.length > 0) {
                                    const sel = document.createElement('select');
                                    sel.name = 'attr_select[' + attr.id + ']';
                                    sel.className = inputBaseClass;
                                    sel.appendChild(new Option(attrName, ''));
                                    options.forEach(opt => sel.appendChild(new Option(opt, opt)));
                                    wrapper.appendChild(sel);
                                } else if (['number', 'numeric', 'integer', 'float', 'decimal'].includes(attrType)) {
                                    const row = document.createElement('div');
                                    row.className = 'grid grid-cols-2 gap-2';
                                    const minIn = document.createElement('input');
                                    minIn.type = 'number';
                                    minIn.name = 'attr_min[' + attr.id + ']';
                                    minIn.className = inputBaseClass;
                                    minIn.step = '0.01';
                                    minIn.placeholder = labelText + ' - ' + 'من';
                                    const maxIn = document.createElement('input');
                                    maxIn.type = 'number';
                                    maxIn.name = 'attr_max[' + attr.id + ']';
                                    maxIn.className = inputBaseClass;
                                    maxIn.step = '0.01';
                                    maxIn.placeholder = labelText + ' - ' + 'إلى';
                                    row.appendChild(minIn);
                                    row.appendChild(maxIn);
                                    wrapper.appendChild(row);
                                } else if (['bool', 'boolean', 'yes_no'].includes(attrType)) {
                                    const checkLabel = document.createElement('label');
                                    checkLabel.className = 'flex items-center gap-2 rtl:flex-row-reverse';
                                    const check = document.createElement('input');
                                    check.type = 'checkbox';
                                    check.name = 'attr_bool[' + attr.id + ']';
                                    check.value = '1';
                                    check.className = 'w-5 h-5 text-blue-600 focus:ring-blue-500 rounded';
                                    checkLabel.appendChild(check);
                                    const checkSpan = document.createElement('span');
                                    checkSpan.className = 'text-sm font-medium';
                                    checkSpan.textContent = labelText;
                                    checkLabel.appendChild(checkSpan);
                                    wrapper.appendChild(checkLabel);
                                } else if (attrType === 'date') {
                                    const inp = document.createElement('input');
                                    inp.type = 'date';
                                    inp.name = 'attr[' + attr.id + ']';
                                    inp.className = inputBaseClass;
                                    inp.placeholder = labelText;
                                    wrapper.appendChild(inp);
                                } else if (attrType === 'time') {
                                    const inp = document.createElement('input');
                                    inp.type = 'time';
                                    inp.name = 'attr[' + attr.id + ']';
                                    inp.className = inputBaseClass;
                                    inp.placeholder = labelText;
                                    wrapper.appendChild(inp);
                                } else if (attrType === 'datetime') {
                                    const inp = document.createElement('input');
                                    inp.type = 'datetime-local';
                                    inp.name = 'attr[' + attr.id + ']';
                                    inp.className = inputBaseClass;
                                    inp.placeholder = labelText;
                                    wrapper.appendChild(inp);
                                } else if (attrType === 'textarea') {
                                    const ta = document.createElement('textarea');
                                    ta.name = 'attr[' + attr.id + ']';
                                    ta.rows = 2;
                                    ta.className = inputBaseClass;
                                    ta.placeholder = labelText;
                                    wrapper.appendChild(ta);
                                } else {
                                    const inp = document.createElement('input');
                                    inp.type = 'text';
                                    inp.name = 'attr[' + attr.id + ']';
                                    inp.className = inputBaseClass;
                                    inp.placeholder = labelText;
                                    wrapper.appendChild(inp);
                                }
                                return wrapper;
                            }

                            async function showAttributes(categoryId) {
                                attributesList.innerHTML = '';
                                if (!categoryId) {
                                    attributesContainer.classList.add('hidden');
                                    return;
                                }
                                try {
                                    const res = await fetch(attributesApiUrl + '?category_id=' + categoryId + '&locale=' + currentLocale);
                                    const data = await res.json();
                                    const attrs = (data.data || []).filter(a => a.is_active !== false);
                                    if (attrs.length === 0) {
                                        attributesContainer.classList.add('hidden');
                                        return;
                                    }
                                    attrs.forEach(attr => attributesList.appendChild(createField(attr)));
                                    attributesContainer.classList.remove('hidden');
                                } catch (e) {
                                    attributesContainer.classList.add('hidden');
                                }
                            }

                            subSelect.addEventListener('change', () => showAttributes(subSelect.value));
                            populateCategories();
                        })();
                        </script>
                    </div>
                    @endif

                    @if(\App\Helpers\PlatformModeHelper::getMode() !== \App\Helpers\PlatformModeHelper::MODE_CONTRACTING)
                    <button type="button" class="accordion-trigger" onclick="toggleHomeSection('home-map')" aria-expanded="false" aria-controls="home-map">
                        <span>{{ __('general.home.map_search') }}</span>
                        <i class="fas fa-chevron-down" data-accordion-icon="home-map"></i>
                    </button>
                    <div id="home-map" class="px-4 sm:px-5 pb-5 hidden">
                        <form action="{{ route('public.search.map') }}" method="GET" id="homeMapSearchForm">
                            <div class="mb-4 flex flex-wrap items-center justify-center gap-2">
                                <label class="segmented">
                                    <input type="radio" name="search_type" value="projects" checked onchange="updateHomeMapFilters()">
                                    <span>العقارات</span>
                                </label>
                                <label class="segmented">
                                    <input type="radio" name="search_type" value="facilities" onchange="updateHomeMapFilters()">
                                    <span>{{ __('public.navigation.facilities') }}</span>
                                </label>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <select id="home_map_category_id" name="category_id" class="field">
                                    <option value="">{{ __('public.common.category') }}</option>
                                    @foreach(($searchCategories ?? []) as $category)
                                        <option value="{{ $category->id }}">{{ $category->getTranslatedName() }}</option>
                                    @endforeach
                                </select>
                                <div id="homeMapMinPrice">
                                    <input type="number" id="home_map_min_price" name="min_price" class="field" placeholder="{{ __('general.home.search.price_min') }}">
                                </div>
                                <div id="homeMapMaxPrice">
                                    <input type="number" id="home_map_max_price" name="max_price" class="field" placeholder="{{ __('general.home.search.price_max') }}">
                                </div>
                            </div>

                            <div class="mt-5 flex flex-col sm:flex-row gap-3 sm:justify-center">
                                <button type="submit" class="btn-solid">
                                    <i class="fas fa-map"></i>
                                    <span>{{ __('public.search.map_search') }}</span>
                                </button>
                                <a href="{{ route('public.search.map') }}" class="btn-ghost">
                                    <i class="fas fa-external-link-alt"></i>
                                    <span>{{ __('general.home.open_map') }}</span>
                                </a>
                            </div>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- AI Majarrah-style CTA Section -->
    <section class="ai-hero w-full relative overflow-hidden bg-gradient-to-br from-emerald-50 to-white dark:from-slate-900 dark:to-emerald-950/20 py-16 sm:py-24 border-y border-emerald-100 dark:border-emerald-900/30">
        <div class="home-container text-center max-w-5xl">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-emerald-100 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-300 text-sm font-medium mb-6">
                <i class="fas fa-robot"></i>
                <span>مدعوم بالذكاء الاصطناعي</span>
            </div>
            <div class="max-w-2xl mx-auto mb-12">
                <div class="relative flex items-center">
                    <input type="text" id="ai-home-input" class="w-full h-14 pr-5 pl-16 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder-slate-400 text-right focus:outline-none focus:ring-2 focus:ring-emerald-500" placeholder="اسأل مدار الذكي..." onkeydown="if(event.key === 'Enter') openAiModalWithHomePrompt()">
                    <button type="button" onclick="openAiModalWithHomePrompt()" class="absolute left-2 top-2 bottom-2 px-5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold transition">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 max-w-4xl mx-auto text-right">
                @if($mode === \App\Helpers\PlatformModeHelper::MODE_REAL_ESTATE)
                    <a href="{{ url('/investment-studies') }}" class="group p-4 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:border-emerald-400 dark:hover:border-emerald-600 shadow-sm hover:shadow-md transition">
                        <i class="fas fa-lightbulb text-emerald-600 text-2xl mb-3 block"></i>
                        <h3 class="font-bold text-slate-900 dark:text-white mb-1">استشارة استثمارية</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400">حدد أفضل الفرص الاستثمارية في السوق</p>
                    </a>
                    <a href="{{ url('/investment-studies') }}" class="group p-4 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:border-emerald-400 dark:hover:border-emerald-600 shadow-sm hover:shadow-md transition">
                        <i class="fas fa-calculator text-emerald-600 text-2xl mb-3 block"></i>
                        <h3 class="font-bold text-slate-900 dark:text-white mb-1">تقييم عقاري</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400">اعرف القيمة السوقية العادلة لعقارك</p>
                    </a>
                    <a href="{{ url('/investment-studies') }}" class="group p-4 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:border-emerald-400 dark:hover:border-emerald-600 shadow-sm hover:shadow-md transition">
                        <i class="fas fa-hand-holding-usd text-emerald-600 text-2xl mb-3 block"></i>
                        <h3 class="font-bold text-slate-900 dark:text-white mb-1">نصيحة شراء</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400">تأكد من قرار الشراء قبل التعاقد</p>
                    </a>
                    <a href="{{ url('/investment-studies') }}" class="group p-4 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:border-emerald-400 dark:hover:border-emerald-600 shadow-sm hover:shadow-md transition">
                        <i class="fas fa-chart-area text-emerald-600 text-2xl mb-3 block"></i>
                        <h3 class="font-bold text-slate-900 dark:text-white mb-1">تحليل سوق</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400">تعرف على اتجاهات السوق والأسعار</p>
                    </a>
                @elseif($mode === \App\Helpers\PlatformModeHelper::MODE_CONTRACTING)
                    <a href="{{ url('/investment-studies') }}" class="group p-4 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:border-emerald-400 dark:hover:border-emerald-600 shadow-sm hover:shadow-md transition">
                        <i class="fas fa-hard-hat text-emerald-600 text-2xl mb-3 block"></i>
                        <h3 class="font-bold text-slate-900 dark:text-white mb-1">تقدير تكلفة</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400">مشروع سكني بـ 500 وحدة في الرياض</p>
                    </a>
                    <a href="{{ url('/investment-studies') }}" class="group p-4 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:border-emerald-400 dark:hover:border-emerald-600 shadow-sm hover:shadow-md transition">
                        <i class="fas fa-tools text-emerald-600 text-2xl mb-3 block"></i>
                        <h3 class="font-bold text-slate-900 dark:text-white mb-1">مقارنة المقاولين</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400">عروض البناء للمشاريع التجارية</p>
                    </a>
                    <a href="{{ url('/investment-studies') }}" class="group p-4 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:border-emerald-400 dark:hover:border-emerald-600 shadow-sm hover:shadow-md transition">
                        <i class="fas fa-file-contract text-emerald-600 text-2xl mb-3 block"></i>
                        <h3 class="font-bold text-slate-900 dark:text-white mb-1">صياغة عقود</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400">عقود المقاولات وشروط الالتزام</p>
                    </a>
                    <a href="{{ url('/investment-studies') }}" class="group p-4 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:border-emerald-400 dark:hover:border-emerald-600 shadow-sm hover:shadow-md transition">
                        <i class="fas fa-building text-emerald-600 text-2xl mb-3 block"></i>
                        <h3 class="font-bold text-slate-900 dark:text-white mb-1">جدولة مشاريع</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400">تخطيط الجدول الزمني للتنفيذ</p>
                    </a>
                @else
                    <a href="{{ url('/investment-studies') }}" class="group p-4 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:border-emerald-400 dark:hover:border-emerald-600 shadow-sm hover:shadow-md transition">
                        <i class="fas fa-lightbulb text-emerald-600 text-2xl mb-3 block"></i>
                        <h3 class="font-bold text-slate-900 dark:text-white mb-1">استشارة استثمارية</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400">حدد أفضل الفرص الاستثمارية في السوق</p>
                    </a>
                    <a href="{{ url('/investment-studies') }}" class="group p-4 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:border-emerald-400 dark:hover:border-emerald-600 shadow-sm hover:shadow-md transition">
                        <i class="fas fa-calculator text-emerald-600 text-2xl mb-3 block"></i>
                        <h3 class="font-bold text-slate-900 dark:text-white mb-1">تقييم عقاري</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400">اعرف القيمة السوقية العادلة لعقارك</p>
                    </a>
                    <a href="{{ url('/investment-studies') }}" class="group p-4 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:border-emerald-400 dark:hover:border-emerald-600 shadow-sm hover:shadow-md transition">
                        <i class="fas fa-hand-holding-usd text-emerald-600 text-2xl mb-3 block"></i>
                        <h3 class="font-bold text-slate-900 dark:text-white mb-1">نصيحة شراء</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400">تأكد من قرار الشراء قبل التعاقد</p>
                    </a>
                    <a href="{{ url('/investment-studies') }}" class="group p-4 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:border-emerald-400 dark:hover:border-emerald-600 shadow-sm hover:shadow-md transition">
                        <i class="fas fa-chart-area text-emerald-600 text-2xl mb-3 block"></i>
                        <h3 class="font-bold text-slate-900 dark:text-white mb-1">تحليل سوق</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400">تعرف على اتجاهات السوق والأسعار</p>
                    </a>
                @endif
            </div>
        </div>
    </section>

    <div class="home-container">
    <!-- Featured Cities -->
    @if(isset($featuredCities) && $featuredCities->count() > 0)
        <x-multi-view-grid
            :items="$featuredCities"
            type="cities"
            :title="__('general.home.featured_cities')"
            :maxItems="100"
            :showViewToggle="false"
            idPrefix="cities"
        />
    @endif

    <!-- Featured Categories -->
    @if(isset($categories) && $categories->count() > 0)
        <x-multi-view-grid
            :items="$categories"
            type="categories"
            :title="__('general.home.featured_categories')"
            :maxItems="6"
            :showViewToggle="false"
            idPrefix="categories"
        />
    @endif

    <section class="mb-12 sm:mb-16">
        <div class="section-head">
            <h2 class="section-title">{{ __('general.home.our_services_title') }}</h2>
            <p class="section-subtitle">{{ __('general.home.our_services_subtitle') }}</p>
        </div>
        @php($serviceCount = 1
            + (\App\Helpers\PlatformModeHelper::allowsContracting() ? 1 : 0)
            + (\App\Helpers\PlatformModeHelper::allowsRealEstate() ? 2 : 0))
        <div class="services-grid" style="--service-count: {{ $serviceCount }};">
            @if(\App\Helpers\PlatformModeHelper::allowsContracting())
                <a href="{{ route('public.execution.marketplace') }}" class="service-card group">
                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/5 to-emerald-600/10 opacity-0 transition-opacity group-hover:opacity-100"></div>
                    <div class="relative text-center">
                        <div class="service-icon bg-gradient-to-br from-emerald-400 to-emerald-600 text-white">
                            <i class="fas fa-gavel"></i>
                        </div>
                        <div class="service-title">{{ __('general.home.services.project_platform.title') }}</div>
                        <div class="service-desc">{{ __('general.home.services.project_platform.description') }}</div>
                    </div>
                </a>
            @endif
            @if(\App\Helpers\PlatformModeHelper::allowsRealEstate())
                <a href="{{ route('public.facilities.index') }}" class="service-card group">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-500/5 to-blue-600/10 opacity-0 transition-opacity group-hover:opacity-100"></div>
                    <div class="relative text-center">
                        <div class="service-icon bg-gradient-to-br from-blue-400 to-blue-600 text-white">
                            <i class="fas fa-building"></i>
                        </div>
                        <div class="service-title">{{ __('general.home.services.contractors_directory.title') }}</div>
                        <div class="service-desc">{{ __('general.home.services.contractors_directory.description') }}</div>
                    </div>
                </a>
            @endif
            @if(\App\Helpers\PlatformModeHelper::allowsRealEstate())
                <a href="{{ route('public.products.index') }}" class="service-card group">
                    <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/5 to-indigo-600/10 opacity-0 transition-opacity group-hover:opacity-100"></div>
                    <div class="relative text-center">
                        <div class="service-icon bg-gradient-to-br from-indigo-400 to-indigo-600 text-white">
                            <i class="fas fa-home"></i>
                        </div>
                        <div class="service-title">{{ __('general.home.services.browse_projects.title') }}</div>
                        <div class="service-desc">{{ __('general.home.services.browse_projects.description') }}</div>
                    </div>
                </a>
            @endif
            <a href="{{ url('/investment-studies') }}" class="service-card group">
                <div class="absolute inset-0 bg-gradient-to-br from-amber-500/5 to-amber-600/10 opacity-0 transition-opacity group-hover:opacity-100"></div>
                <div class="relative text-center">
                    <div class="service-icon bg-gradient-to-br from-amber-400 to-amber-600 text-white">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="service-title">{{ __('general.home.services.analysis_center.title') }}</div>
                    <div class="service-desc">{{ __('general.home.services.analysis_center.description') }}</div>
                </div>
            </a>
        </div>
    </section>

    @php($homeStats = [
        ['icon' => 'fas fa-home', 'value' => $stats['total_products'] ?? 0, 'label' => __('general.home.stats.total_projects')],
        ['icon' => 'fas fa-building', 'value' => $stats['total_facilities'] ?? 0, 'label' => __('general.home.stats.companies_and_facilities')],
        ['icon' => 'fas fa-layer-group', 'value' => $stats['total_categories'] ?? 0, 'label' => __('general.home.stats.categories')],
        ['icon' => 'fas fa-star', 'value' => $stats['featured_products'] ?? 0, 'label' => __('general.home.stats.featured_projects')],
    ])
    <section class="stats-band mb-12 sm:mb-16">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-5">
            @foreach($homeStats as $stat)
                <div class="stat-card">
                    <div class="stat-icon"><i class="{{ $stat['icon'] }}"></i></div>
                    <div class="stat-value">{{ number_format($stat['value']) }}</div>
                    <div class="stat-label">{{ $stat['label'] }}</div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Global View Toggle -->
    <div class="flex justify-between sm:justify-end items-center gap-3 mb-6">
        <span class="text-sm opacity-70">{{ __('general.view_toggle.display') }}</span>
        <div class="view-toggle-group">
            <button id="small-grid-view"
                    class="view-toggle-btn is-active"
                    onclick="switchView('small-grid')"
                    title="{{ __('general.view_toggle.small_grid') }}">
                <i class="fas fa-th"></i>
            </button>
            <button id="large-grid-view"
                    class="view-toggle-btn"
                    onclick="switchView('large-grid')"
                    title="{{ __('general.view_toggle.large_grid') }}">
                <i class="fas fa-th-large"></i>
            </button>
            <button id="list-view"
                    class="view-toggle-btn"
                    onclick="switchView('list')"
                    title="{{ __('general.view_toggle.list') }}">
                <i class="fas fa-list"></i>
            </button>
        </div>
    </div>

    @if(\App\Helpers\PlatformModeHelper::allowsContracting())
        <!-- Latest Projects (Execution Requests) -->
        @if(isset($latestExecutionRequests) && $latestExecutionRequests->count() > 0)
            <x-multi-view-grid
                :items="$latestExecutionRequests"
                type="execution_requests"
                :title="__('general.home.latest_properties')"
                :viewAllRoute="route('public.projects.index')"
                :viewAllText="__('general.home.view_all_properties')"
                :maxItems="6"
                :showViewToggle="false"
                idPrefix="execution-requests"
                :showPrice="false"
            />
        @endif
    @endif

    @if(\App\Helpers\PlatformModeHelper::allowsRealEstate())
        <div id="real-estate-section">
        <!-- Latest Products (Real Estate) -->
        @if(isset($latestProducts) && $latestProducts->count() > 0)
            @if(isset($searchCategories) && $searchCategories->count() > 0)
                <div class="cat-filter mb-5">
                    @if(request('category_id'))
                        <a href="{{ request()->url() }}" onclick="filterRealEstate(''); return false;" class="chip chip-clear">
                            <i class="fas fa-times"></i>
                            <span>الكل</span>
                        </a>
                    @endif
                    @foreach($searchCategories->whereNull('parent_id') as $mainCategory)
                        @php($subCategories = $searchCategories->where('parent_id', $mainCategory->id))
                        @php($isMainActive = (string) request('category_id') === (string) $mainCategory->id)
                        <div class="cat-row">
                            <a href="{{ request()->fullUrlWithQuery(['category_id' => $mainCategory->id]) }}" onclick="filterRealEstate({{ $mainCategory->id }}); return false;" class="chip chip-main {{ $isMainActive ? 'is-active' : '' }}">
                                {{ $mainCategory->name }}
                            </a>
                            @if($subCategories->count() > 0)
                                <div class="cat-subs no-scrollbar">
                                    @foreach($subCategories as $subCategory)
                                        @php($isSubActive = (string) request('category_id') === (string) $subCategory->id)
                                        <a href="{{ request()->fullUrlWithQuery(['category_id' => $subCategory->id]) }}" onclick="filterRealEstate({{ $subCategory->id }}); return false;" class="chip chip-sub {{ $isSubActive ? 'is-active' : '' }}">
                                            {{ $subCategory->name }}
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif

            <x-multi-view-grid
                :items="$latestProducts"
                type="products"
                :title="__('general.home.latest_properties')"
                :viewAllText="__('general.actions.load_more')"
                :loadMore="true"
                :maxItems="6"
                :showViewToggle="false"
                idPrefix="products"
            />
        @endif
        </div>
    @endif

    </div>
</div>



<!-- AI Investment Study Modal -->
<div id="aiModal" class="fixed inset-0 z-[100] hidden" aria-modal="true" role="dialog" aria-label="استشارة بالذكاء الاصطناعي">
    <div class="absolute inset-0 bg-slate-900/80 backdrop-blur-sm" onclick="closeAiModal()"></div>
    <div class="absolute inset-0 sm:inset-6 lg:inset-10 bg-white dark:bg-slate-900 rounded-none sm:rounded-2xl shadow-2xl overflow-hidden flex flex-col">
        <div class="flex items-center justify-between px-4 sm:px-6 py-3 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800">
            <h3 class="font-bold text-slate-900 dark:text-white flex items-center gap-2">
                <i class="fas fa-robot text-emerald-600"></i>
                <span>مدار الذكي</span>
            </h3>
            <button type="button" onclick="closeAiModal()" class="p-2 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 transition" aria-label="إغلاق">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="flex-1 relative">
            <iframe id="aiModalFrame" src="{{ url('/investment-studies') }}" class="w-full h-full border-0" loading="lazy" title="استشارة بالذكاء الاصطناعي"></iframe>
        </div>
    </div>
</div>

<script>
    function openAiModal(prompt) {
        const modal = document.getElementById('aiModal');
        const frame = document.getElementById('aiModalFrame');
        const baseUrl = '{{ url('/investment-studies') }}';
        const url = prompt ? baseUrl + '?prompt=' + encodeURIComponent(prompt) : baseUrl;
        if (frame) frame.src = url;
        if (modal) modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function openAiModalWithHomePrompt() {
        const input = document.getElementById('ai-home-input');
        const prompt = input ? input.value.trim() : '';
        openAiModal(prompt);
        if (input) input.value = '';
    }

    function closeAiModal() {
        const modal = document.getElementById('aiModal');
        if (modal) modal.classList.add('hidden');
        document.body.style.overflow = '';
    }

    // Close modal on Escape key
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeAiModal();
    });
</script>

@endsection

@push('styles')
<style>
/* ==========================================================================
   Home page — responsive design system
   ========================================================================== */

.line-clamp-1, .line-clamp-2, .line-clamp-3 {
    display: -webkit-box;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.line-clamp-1 { -webkit-line-clamp: 1; }
.line-clamp-2 { -webkit-line-clamp: 2; }
.line-clamp-3 { -webkit-line-clamp: 3; }

.text-balance { text-wrap: balance; }
.text-pretty  { text-wrap: pretty; }

/* Shared page container: fluid gutters that grow with the viewport */
.home-container {
    width: 100%;
    max-width: 80rem;
    margin-inline: auto;
    padding-inline: clamp(1rem, 4vw, 2rem);
    padding-block: clamp(1.5rem, 4vw, 2.5rem);
}

/* --------------------------------------------------------------------------
   Hero
   -------------------------------------------------------------------------- */
.hero-shell { isolation: isolate; background: linear-gradient(to bottom, rgba(15,23,42,.62) 0%, rgba(15,23,42,.34) 45%, rgba(15,23,42,.20) 100%); }

.hero-media {
    -webkit-mask-image: linear-gradient(to bottom, black calc(100% - 4rem), transparent 100%);
    mask-image: linear-gradient(to bottom, black calc(100% - 4rem), transparent 100%);
}
.hero-media video {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center 15%;
    pointer-events: none;
}

.hero-scrim {
    position: absolute;
    inset: 0;
    pointer-events: none;
    background:
        linear-gradient(to bottom, transparent 0%, transparent 10%, rgba(15,23,42,.34) 30%, rgba(15,23,42,.20) 100%);
}
.dark .hero-scrim,
html[data-theme="dark"] .hero-scrim {
    background: linear-gradient(to bottom, rgba(2,6,23,.80) 0%, rgba(2,6,23,.60) 45%, rgba(2,6,23,.45) 100%);
}

.hero-title {
    /* 1.9rem on a 360px phone → 3.25rem on desktop */
    font-size: clamp(1.9rem, 1.15rem + 3.4vw, 3.25rem);
    line-height: 1.22;
    letter-spacing: -0.01em;
}
.hero-subtitle {
    font-size: clamp(0.975rem, 0.9rem + 0.5vw, 1.25rem);
}

/* Motion / data saving: don't autoplay-animate the background for these users */
@media (prefers-reduced-motion: reduce) {
    .hero-media video { display: none; }
    .hero-media { background: #0f172a; }
    .floating-buttons a, .fab { animation: none !important; }
}

/* While the page sits on the hero, the sticky nav is transparent over a dark
   video scrim — so its default dark text must flip to white to stay legible. */
.home-at-top nav a,
.home-at-top nav button,
.home-at-top nav span,
.home-at-top nav i {
    color: #fff;
}
.home-at-top nav button[data-theme-toggle] {
    border-color: rgba(255, 255, 255, .45);
}
/* Dropdown/flyout panels keep their own light background — restore their text */
.home-at-top nav .absolute,
.home-at-top nav .absolute a,
.home-at-top nav .absolute span,
.home-at-top nav .absolute i,
.home-at-top nav .absolute h3,
.home-at-top nav .absolute p {
    color: var(--brand-fg);
}
/* …except the unread-notification badge, which is white-on-red by design */
.home-at-top nav .bg-red-500 { color: #fff; }

/* --------------------------------------------------------------------------
   Search card
   -------------------------------------------------------------------------- */
.search-card { color: #fff; }

/* Accordion headers sit on a dark translucent card → force light text */
.search-card .accordion-trigger {
    display: flex;
    width: 100%;
    align-items: center;
    justify-content: space-between;
    gap: 0.75rem;
    padding: 0.9rem 1rem;
    font-weight: 700;
    font-size: 0.95rem;
    color: #fff !important;
    background: transparent;
    text-align: start;
    transition: background-color .18s ease;
    min-height: 48px; /* comfortable tap target */
}
@media (min-width: 640px) {
    .search-card .accordion-trigger { padding: 1rem 1.25rem; font-size: 1rem; }
}
.search-card .accordion-trigger:hover { background: rgba(255,255,255,.07); }
.search-card .accordion-trigger i { color: rgba(255,255,255,.8) !important; transition: transform .2s ease; }

/* Inputs inside the card */
.search-card .field,
.search-card input[type="text"],
.search-card input[type="number"],
.search-card input[type="date"],
.search-card input[type="time"],
.search-card input[type="datetime-local"],
.search-card select,
.search-card textarea {
    width: 100%;
    padding: 0.7rem 0.9rem;
    font-size: 0.95rem;
    border-radius: 0.75rem;
    border: 1px solid rgba(255,255,255,.28);
    background-color: rgba(255,255,255,.96) !important;
    color: #0f172a !important;
    transition: border-color .15s ease, box-shadow .15s ease;
    min-height: 44px;
}
.search-card .field:focus,
.search-card input:focus,
.search-card select:focus,
.search-card textarea:focus {
    outline: none;
    border-color: rgba(255,255,255,.85);
    box-shadow: 0 0 0 3px rgba(255,255,255,.25);
}
.search-card ::placeholder { color: #64748b !important; opacity: 1; }
.search-card label,
.search-card span { color: #e2e8f0; }

/* Quick search: input + button glued on ≥sm, stacked on phones */
.search-form {
    display: flex;
    flex-direction: column;
    gap: 0.6rem;
}
.search-form .search-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    min-height: 46px;
    font-weight: 700;
    color: #fff !important;
    background: #111827;
    border-radius: 0.75rem;
    transition: filter .15s ease;
}
.search-form .search-button:hover { filter: brightness(1.25); }
.search-form .search-button i { color: #fff !important; }

@media (min-width: 640px) {
    .search-form { flex-direction: row; gap: 0; }
    .search-form .search-input {
        border-start-start-radius: 0.75rem;
        border-end-start-radius: 0.75rem;
        border-start-end-radius: 0;
        border-end-end-radius: 0;
        border-inline-end: 0;
    }
    .search-form .search-button {
        flex: none;
        border-start-start-radius: 0;
        border-end-start-radius: 0;
        border-start-end-radius: 0.75rem;
        border-end-end-radius: 0.75rem;
    }
}

/* Card action buttons */
.search-card .btn-solid,
.search-card .btn-ghost {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    width: 100%;
    padding: 0.75rem 1.75rem;
    min-height: 46px;
    font-weight: 700;
    font-size: 0.95rem;
    border-radius: 0.75rem;
    transition: filter .15s ease, background-color .15s ease;
}
@media (min-width: 640px) {
    .search-card .btn-solid,
    .search-card .btn-ghost { width: auto; }
}
.search-card .btn-solid { background: #111827; color: #fff !important; }
.search-card .btn-solid:hover { filter: brightness(1.3); }
.search-card .btn-ghost {
    background: rgba(255,255,255,.14);
    color: #fff !important;
    border: 1px solid rgba(255,255,255,.3);
}
.search-card .btn-ghost:hover { background: rgba(255,255,255,.24); }

/* Segmented radio pills (map search) */
.search-card .segmented {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 0.9rem;
    border-radius: 999px;
    border: 1px solid rgba(255,255,255,.28);
    background: rgba(255,255,255,.08);
    cursor: pointer;
    min-height: 42px;
}
.search-card .segmented span { font-size: 0.875rem; font-weight: 600; }
.search-card .segmented:has(input:checked) {
    background: rgba(255,255,255,.9);
    border-color: transparent;
}
.search-card .segmented:has(input:checked) span { color: #0f172a !important; }
.search-card .segmented input { accent-color: #111827; width: 1rem; height: 1rem; }

/* --------------------------------------------------------------------------
   Section headings
   -------------------------------------------------------------------------- */
.section-head { text-align: center; margin-bottom: clamp(1.5rem, 3vw, 2.25rem); }
.section-title {
    font-size: clamp(1.375rem, 1.1rem + 1.1vw, 2rem);
    font-weight: 800;
    line-height: 1.3;
    margin-bottom: 0.4rem;
}
.section-subtitle {
    font-size: clamp(0.875rem, 0.85rem + 0.2vw, 1rem);
    opacity: .7;
    max-width: 42rem;
    margin-inline: auto;
}

/* --------------------------------------------------------------------------
   Service cards
   -------------------------------------------------------------------------- */
/* Auto-fitting, centred grid: never leaves a lonely orphan column */
.services-grid {
    display: grid;
    gap: 1rem;
    grid-template-columns: 1fr;
    justify-content: center;
}
@media (min-width: 640px) {
    .services-grid {
        gap: 1.5rem;
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}
@media (min-width: 1024px) {
    .services-grid {
        grid-template-columns: repeat(min(var(--service-count, 4), 4), minmax(0, 17rem));
    }
}

.service-card {
    position: relative;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    border-radius: 1rem;
    border: 1px solid var(--brand-border, rgba(0,0,0,.12));
    background: var(--brand-bg, #fff);
    padding: clamp(1.1rem, 2.5vw, 1.5rem);
    box-shadow: 0 1px 2px rgba(0,0,0,.05), 0 8px 24px -12px rgba(0,0,0,.15);
    transition: transform .25s ease, box-shadow .25s ease;
    height: 100%;
}
@media (hover: hover) {
    .service-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 1px 2px rgba(0,0,0,.05), 0 22px 40px -18px rgba(0,0,0,.28);
    }
}
.service-card:active { transform: translateY(-2px); }

.service-icon {
    width: 3rem;
    height: 3rem;
    border-radius: 0.9rem;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 0.85rem;
    font-size: 1.25rem;
    box-shadow: 0 8px 18px -8px rgba(0,0,0,.45);
}
.service-icon i { color: #fff !important; }
@media (min-width: 640px) {
    .service-icon { width: 3.5rem; height: 3.5rem; font-size: 1.5rem; }
}
.service-title {
    font-weight: 700;
    font-size: clamp(1rem, 0.95rem + 0.25vw, 1.2rem);
    margin-bottom: 0.4rem;
}
.service-desc {
    font-size: 0.85rem;
    line-height: 1.65;
    opacity: .75;
}

/* --------------------------------------------------------------------------
   Stats band
   -------------------------------------------------------------------------- */
.stats-band {
    border-radius: clamp(1rem, 2.5vw, 1.75rem);
    padding: clamp(1.1rem, 3vw, 2rem);
    background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
    box-shadow: 0 18px 45px -22px rgba(0,0,0,.6);
}
.stat-card {
    text-align: center;
    padding: clamp(0.75rem, 2vw, 1.15rem) 0.5rem;
    border-radius: 1rem;
    background: rgba(255,255,255,.09);
    border: 1px solid rgba(255,255,255,.12);
    backdrop-filter: blur(6px);
}
.stat-icon {
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 999px;
    background: rgba(255,255,255,.16);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 0.6rem;
    font-size: 1rem;
}
.stat-icon i { color: #fff !important; }
@media (min-width: 640px) {
    .stat-icon { width: 3rem; height: 3rem; font-size: 1.15rem; }
}
.stat-value {
    font-size: clamp(1.35rem, 1.1rem + 1.2vw, 2rem);
    font-weight: 800;
    color: #fff !important;
    line-height: 1.15;
    font-variant-numeric: tabular-nums;
}
.stat-label {
    font-size: clamp(0.7rem, 0.68rem + 0.15vw, 0.85rem);
    color: rgba(255,255,255,.75) !important;
    margin-top: 0.2rem;
}

/* --------------------------------------------------------------------------
   View toggle
   -------------------------------------------------------------------------- */
.view-toggle-group {
    display: inline-flex;
    padding: 0.2rem;
    gap: 0.2rem;
    border-radius: 0.75rem;
    border: 1px solid var(--brand-border, rgba(0,0,0,.12));
    background: var(--brand-bg, #fff);
}
.view-toggle-btn {
    width: 2.35rem;
    height: 2.35rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 0.55rem;
    font-size: 0.9rem;
    opacity: .55;
    transition: background-color .18s ease, opacity .18s ease;
}
.view-toggle-btn:hover { opacity: 1; background: rgba(127,127,127,.14); }
.view-toggle-btn.is-active {
    opacity: 1;
    background: var(--brand-fg, #111827);
}
.view-toggle-btn.is-active i { color: var(--brand-bg, #fff) !important; }

/* --------------------------------------------------------------------------
   Category filter chips
   -------------------------------------------------------------------------- */
.cat-filter { display: flex; flex-direction: column; gap: 0.55rem; }

.cat-row {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.45rem 0.6rem;
    border-radius: 0.85rem;
    border: 1px solid var(--brand-border, rgba(0,0,0,.12));
    background: var(--brand-bg, #fff);
    min-width: 0;
}

/* Sub-categories scroll sideways instead of exploding the row height */
.cat-subs {
    display: flex;
    align-items: center;
    gap: 0.35rem;
    overflow-x: auto;
    flex: 1 1 auto;
    min-width: 0;
    padding-block: 0.15rem;
    -webkit-overflow-scrolling: touch;
}
.cat-subs::before {
    content: '';
    flex: 0 0 1px;
    align-self: stretch;
    background: var(--brand-border, rgba(0,0,0,.15));
    margin-inline-end: 0.2rem;
}

.chip {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    white-space: nowrap;
    border-radius: 0.6rem;
    transition: background-color .16s ease, color .16s ease;
    flex: 0 0 auto;
}
.chip-main {
    font-weight: 700;
    font-size: clamp(0.85rem, 0.82rem + 0.2vw, 1rem);
    padding: 0.45rem 0.8rem;
    border: 1px solid var(--brand-border, rgba(0,0,0,.12));
}
.chip-sub {
    font-weight: 600;
    font-size: clamp(0.775rem, 0.75rem + 0.15vw, 0.9rem);
    padding: 0.35rem 0.65rem;
    opacity: .7;
}
.chip-sub:hover { opacity: 1; background: rgba(127,127,127,.12); }
.chip-clear {
    align-self: flex-start;
    font-weight: 700;
    font-size: 0.85rem;
    padding: 0.45rem 0.8rem;
    border: 1px solid var(--brand-border, rgba(0,0,0,.12));
}
.chip.is-active {
    background: var(--brand-fg, #111827) !important;
    opacity: 1;
}
.chip.is-active,
.chip.is-active i { color: var(--brand-bg, #fff) !important; }

/* --------------------------------------------------------------------------
   Floating action dock
   -------------------------------------------------------------------------- */
.fab-dock {
    position: fixed;
    z-index: 40;
    bottom: max(1rem, env(safe-area-inset-bottom));
    inset-inline-end: 1rem;
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 0.6rem;
    /* Revealed once the hero is scrolled past, so it never covers the search card */
    opacity: 0;
    visibility: hidden;
    transform: translateY(1rem);
    transition: opacity .25s ease, transform .25s ease, visibility .25s ease;
}
.fab-dock.is-visible {
    opacity: 1;
    visibility: visible;
    transform: none;
}
@media (min-width: 1024px) {
    .fab-dock { bottom: 1.5rem; inset-inline-end: 1.5rem; gap: 0.85rem; }
}

.fab-toggle {
    width: 3.25rem;
    height: 3.25rem;
    border-radius: 999px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    order: 2;
    font-size: 1.15rem;
    color: #fff !important;
    background: linear-gradient(135deg, #1e293b, #0f172a);
    box-shadow: 0 10px 28px -8px rgba(0,0,0,.55);
    transition: transform .25s ease;
}
.fab-dock[data-open="true"] .fab-toggle { transform: rotate(45deg); }

.fab-actions {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 0.6rem;
}
/* Collapsed by default on small screens */
@media (max-width: 1023.98px) {
    .fab-dock[data-open="false"] .fab-actions {
        opacity: 0;
        visibility: hidden;
        transform: translateY(0.75rem) scale(.92);
        pointer-events: none;
    }
    .fab-actions {
        transition: opacity .2s ease, transform .2s ease, visibility .2s ease;
        transform-origin: bottom right;
    }
}

.fab {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.6rem;
    border-radius: 999px;
    font-weight: 700;
    font-size: 0.9rem;
    color: #fff !important;
    box-shadow: 0 10px 26px -10px rgba(0,0,0,.55);
    transition: filter .2s ease, box-shadow .2s ease, transform .2s ease;
    /* Icon-only pill on phones */
    width: 3rem;
    height: 3rem;
    padding: 0;
}
.fab i { color: #fff !important; font-size: 1.05rem; }
.fab-label { display: none; }
.fab:hover { filter: brightness(1.12); transform: translateY(-2px); }

/* Full labelled buttons once there is room */
@media (min-width: 1024px) {
    .fab {
        width: auto;
        height: auto;
        min-width: 14rem;
        padding: 0.9rem 1.5rem;
        border-radius: 1rem;
        justify-content: flex-start;
    }
    .fab-label { display: inline; color: #fff !important; }
}

.fab-primary { background: linear-gradient(135deg, #334155, #1e293b); }
.fab-purple  { background: linear-gradient(135deg, #4f46e5, #7c3aed); }
.fab-green   { background: linear-gradient(135deg, #059669, #10b981); }

/* Keep the dock clear of the page bottom edge on phones */
@media (max-width: 1023.98px) {
    .home-container { padding-bottom: 5.5rem; }
}

/* --------------------------------------------------------------------------
   Legacy theme hook
   -------------------------------------------------------------------------- */
body.theme-1 .hero { background: transparent; }
</style>
<script>
    // Applied before first paint so the nav never flashes dark text over the hero
    if (window.scrollY <= 10) {
        document.documentElement.classList.add('home-at-top');
    }
</script>
@endpush

@push('scripts')
<script>
function toggleHomeSection(sectionId) {
    const allSections = ['home-basic', 'home-advanced', 'home-map'];
    allSections.forEach(id => {
        const el = document.getElementById(id);
        if (!el) {
            return;
        }

        const icon = document.querySelector(`[data-accordion-icon="${id}"]`);
        const trigger = document.querySelector(`[aria-controls="${id}"]`);
        const willOpen = id === sectionId && el.classList.contains('hidden');

        el.classList.toggle('hidden', !willOpen);
        if (icon) {
            icon.classList.toggle('fa-chevron-up', willOpen);
            icon.classList.toggle('fa-chevron-down', !willOpen);
        }
        if (trigger) {
            trigger.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
        }
    });
}

function toggleFabDock() {
    const dock = document.getElementById('fabDock');
    if (!dock) {
        return;
    }
    const isOpen = dock.dataset.open === 'true';
    dock.dataset.open = isOpen ? 'false' : 'true';
    const toggle = dock.querySelector('.fab-toggle');
    if (toggle) {
        toggle.setAttribute('aria-expanded', isOpen ? 'false' : 'true');
    }
}

function updateHomeAdvancedAction() {
    const form = document.getElementById('homeAdvancedSearchForm');
    if (!form) {
        return;
    }

    const selected = document.querySelector('input[name="home_search_type"]:checked');
    if (!selected) {
        return;
    }

    const type = selected.value;
    const details = document.getElementById('homePropertyDetails');

    if (type === 'products') {
        form.action = '{{ route("public.search.advanced") }}';
        if (details) {
            details.classList.remove('hidden');
        }
    } else {
        form.action = '{{ route("public.search.facilities") }}';
        if (details) {
            details.classList.add('hidden');
        }
    }
}

function updateHomeMapFilters() {
    const selected = document.querySelector('#homeMapSearchForm input[name="search_type"]:checked');
    const type = selected ? selected.value : 'projects';
    const minPrice = document.getElementById('homeMapMinPrice');
    const maxPrice = document.getElementById('homeMapMaxPrice');

    const showPrice = type === 'projects';
    if (minPrice) {
        minPrice.style.display = showPrice ? 'block' : 'none';
    }
    if (maxPrice) {
        maxPrice.style.display = showPrice ? 'block' : 'none';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    updateHomeAdvancedAction();
    updateHomeMapFilters();
});

// Helper function for locale-aware number formatting
function formatNumber(number, locale = '{{ app()->getLocale() }}') {
    if (locale === 'ar') {
        return new Intl.NumberFormat('ar-SA').format(number);
    } else {
        return new Intl.NumberFormat('en-US').format(number);
    }
}

/**
 * Switch between different view modes (small-grid, large-grid, list)
 * Includes validation to prevent invalid view types from hiding content
 * @param {string} viewType - The view type to switch to
 */
function switchView(viewType) {
    // Get all grid containers dynamically
    const gridContainers = document.querySelectorAll('[id$="-small-grid"]:not([id^="cities-"]):not([id^="categories-"]), [id$="-large-grid"]:not([id^="cities-"]):not([id^="categories-"]), [id$="-list"]:not([id^="cities-"]):not([id^="categories-"])');
    
    // Toggle buttons
    const smallGridBtn = document.getElementById('small-grid-view');
    const largeGridBtn = document.getElementById('large-grid-view');
    const listBtn = document.getElementById('list-view');
    
    // Validate view type - only allow valid options
    const validViewTypes = ['small-grid', 'large-grid', 'list'];
    if (!validViewTypes.includes(viewType)) {
        console.warn(`Invalid view type "${viewType}" detected. Falling back to list view.`);
        viewType = 'list';
        // Clear invalid preference from localStorage
        localStorage.removeItem('preferredView');
    }
    
    // Hide all views first
    gridContainers.forEach(container => {
        container.classList.add('hidden');
    });
    
    // Reset all button styles
    [smallGridBtn, largeGridBtn, listBtn].forEach(btn => {
        if (btn) {
            btn.classList.remove('is-active');
            btn.setAttribute('aria-pressed', 'false');
        }
    });
    
    // Show the selected view type
    const targetSuffix = viewType === 'small-grid' ? '-small-grid' : 
                        viewType === 'large-grid' ? '-large-grid' : '-list';
    
    const targetContainers = document.querySelectorAll(`[id$="${targetSuffix}"]:not([id^="cities-"]):not([id^="categories-"])`);
    targetContainers.forEach(container => {
        container.classList.remove('hidden');
    });
    
    // Update button styles
    let activeBtn = null;
    if (viewType === 'small-grid' && smallGridBtn) {
        activeBtn = smallGridBtn;
    } else if (viewType === 'large-grid' && largeGridBtn) {
        activeBtn = largeGridBtn;
    } else if (viewType === 'list' && listBtn) {
        activeBtn = listBtn;
    }
    
    if (activeBtn) {
        activeBtn.classList.add('is-active');
        activeBtn.setAttribute('aria-pressed', 'true');
    }
    
    // Store user preference in localStorage only if it's valid
    if (validViewTypes.includes(viewType)) {
        localStorage.setItem('preferredView', viewType);
    }
    
    // Safety check: if no view containers exist, show small grid as fallback
    if (gridContainers.length === 0) {
        console.warn('No view containers found. This might indicate a rendering issue.');
    }
}

// Set initial view based on user preference
document.addEventListener('DOMContentLoaded', function() {
    const preferredView = localStorage.getItem('preferredView');
    const validViewTypes = ['small-grid', 'large-grid', 'list'];
    
    // Use preferred view if valid, otherwise default to list view
    const initialView = validViewTypes.includes(preferredView) ? preferredView : 'list';
    
    // If no valid preference exists, set list as default
    if (!preferredView || !validViewTypes.includes(preferredView)) {
        localStorage.setItem('preferredView', 'list');
    }
    
    switchView(initialView);
    
    // Add language-specific functionality
    const currentLocale = '{{ app()->getLocale() }}';
    const isRTL = currentLocale === 'ar';
    
    // Set document direction based on language
    if (isRTL) {
        document.documentElement.setAttribute('dir', 'rtl');
        document.documentElement.setAttribute('lang', 'ar');
    } else {
        document.documentElement.setAttribute('dir', 'ltr');
        document.documentElement.setAttribute('lang', 'en');
    }
    
    // Hero-aware chrome: flip the transparent nav to white text while it sits on the
    // hero video, and only reveal the floating dock once the search card is out of the way.
    const dockEl = document.getElementById('fabDock');
    const heroEl = document.querySelector('.hero-shell');
    if (heroEl) {
        const syncHeroChrome = () => {
            document.documentElement.classList.toggle('home-at-top', window.scrollY <= 10);
            if (dockEl) {
                const revealAt = Math.max(heroEl.offsetHeight - window.innerHeight * 0.35, 120);
                dockEl.classList.toggle('is-visible', window.scrollY > revealAt);
            }
        };
        syncHeroChrome();
        window.addEventListener('scroll', syncHeroChrome, { passive: true });
        window.addEventListener('resize', syncHeroChrome);
    }

    // Collapse the floating dock when tapping elsewhere on small screens
    document.addEventListener('click', function(e) {
        const dock = document.getElementById('fabDock');
        if (dock && dock.dataset.open === 'true' && !dock.contains(e.target)) {
            dock.dataset.open = 'false';
            const toggle = dock.querySelector('.fab-toggle');
            if (toggle) {
                toggle.setAttribute('aria-expanded', 'false');
            }
        }
    });

    // Add accessibility improvements
    const viewToggleButtons = document.querySelectorAll('.view-toggle-btn');
    viewToggleButtons.forEach(button => {
        button.setAttribute('aria-label', button.title);
        button.setAttribute('role', 'button');
        button.setAttribute('tabindex', '0');
    });
});

    function filterRealEstate(categoryId) {
        const url = new URL(window.location.href);
        if (categoryId) {
            url.searchParams.set('category_id', categoryId);
        } else {
            url.searchParams.delete('category_id');
        }

        const currentSection = document.getElementById('real-estate-section');
        const visibleContainer = currentSection ? currentSection.querySelector('[id$="-small-grid"]:not(.hidden), [id$="-large-grid"]:not(.hidden), [id$="-list"]:not(.hidden)') : null;
        const currentView = visibleContainer ? visibleContainer.id.replace(/^[^-]+-/, '') : 'small-grid';

        fetch(url.toString())
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newSection = doc.getElementById('real-estate-section');
                if (newSection && currentSection) {
                    currentSection.innerHTML = newSection.innerHTML;
                }
                window.history.pushState({}, '', url.toString());
                if (typeof switchView === 'function') {
                    switchView(currentView);
                }
            })
            .catch(error => console.error('Error filtering products:', error));
    }
</script>
@endpush
