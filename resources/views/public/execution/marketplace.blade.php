@extends('layouts.app')

@section('title', 'منصة المشاريع')

@push('styles')
    <style>
        .glass {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
        }
        .dark .glass {
            background: rgba(17, 24, 39, 0.75);
        }
        .card-shadow {
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
        }
    </style>
@endpush

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">
        <section class="pt-8 pb-10">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start mb-8">
                <div class="lg:col-span-2 space-y-4">
                    <h2 id="execution-hero-title" class="text-2xl md:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">سوق المقاولات والمشاريع في المملكة</h2>
                    <p id="execution-hero-desc" class="text-sm md:text-base text-gray-600 dark:text-gray-300 leading-relaxed max-w-xl">اربط مشروعك بالمقاولين المناسبين، واطّلع على المشاريع المتاحة بحسب النوع والميزانية والمدة.</p>
                    <div class="flex flex-wrap gap-3 text-[11px] text-gray-600 dark:text-gray-300">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-primary-50 dark:bg-secondary-800 text-primary-700 dark:text-primary-200 border border-primary-100 dark:border-secondary-700">
                            <i class="fas fa-shield-check ml-1 text-[10px]"></i>
                            نظام موحّد لطلبات التنفيذ
                        </span>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-primary-50 dark:bg-secondary-800 text-primary-700 dark:text-primary-200 border border-primary-100 dark:border-secondary-700">
                            <i class="fas fa-language ml-1 text-[10px]"></i>
                            يدعم تعدد اللغات في العناوين والوصف
                        </span>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-primary-50 dark:bg-secondary-800 text-primary-700 dark:text-primary-200 border border-primary-100 dark:border-secondary-700">
                            <i class="fas fa-bolt ml-1 text-[10px]"></i>
                            متابعة لحظية للحالة والعروض
                        </span>
                    </div>
                </div>
                <div class="glass card-shadow rounded-2xl p-4 md:p-5 border border-white/60 dark:border-secondary-800">
                    <div class="flex items-center justify-between mb-3 text-xs text-gray-500 dark:text-gray-300">
                        <span class="flex items-center gap-1"><i class="fas fa-signal text-primary-600"></i> حالة السوق الآن</span>
                    </div>
                    <div class="grid grid-cols-3 gap-3 text-center text-xs">
                        <div class="bg-primary-900 text-white rounded-xl p-3">
                            <div class="text-[11px] text-primary-100 mb-1">طلبات مفتوحة</div>
                            <div class="text-xl font-bold">{{ $stats['total_open'] ?? 0 }}</div>
                        </div>
                        <div class="bg-primary-50 dark:bg-secondary-800 rounded-xl p-3">
                            <div class="text-[11px] text-primary-700 dark:text-primary-200 mb-1">طلبات منتهية</div>
                            <div class="text-xl font-semibold text-primary-700 dark:text-primary-200">{{ $stats['total_closed'] ?? 0 }}</div>
                        </div>
                        <div class="bg-primary-50 dark:bg-secondary-800 rounded-xl p-3">
                            <div class="text-[11px] text-primary-700 dark:text-primary-200 mb-1">إجمالي العروض</div>
                            <div class="text-xl font-semibold text-primary-700 dark:text-primary-200">{{ $stats['total_bids'] ?? 0 }}</div>
                        </div>
                    </div>
                    @if($highlightRequest)
                        @php
                            $t = $highlightRequest->translations->firstWhere('locale', app()->getLocale());
                        @endphp
                        <div class="mt-4 text-[11px] text-gray-600 dark:text-gray-300 border-t border-gray-200 dark:border-secondary-800 pt-3">
                            <div class="flex items-center justify-between mb-1">
                                <span class="font-semibold text-gray-800 dark:text-white flex items-center gap-1">
                                    <i class="fas fa-bullhorn text-primary-600"></i>
                                    طلب نشط الآن
                                </span>
                                <span class="text-gray-400">{{ $highlightRequest->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="text-gray-700 dark:text-gray-200 line-clamp-2">{{ $t->title ?? ('طلب #' . $highlightRequest->id) }}</div>
                        </div>
                    @endif
                </div>
            </div>

            <div id="new-request" class="glass card-shadow rounded-2xl p-5 md:p-7 mb-10 border border-white/70 dark:border-secondary-800">
                <h3 id="execution-new-title" class="text-lg md:text-xl font-semibold text-gray-900 dark:text-white mb-1 flex items-center gap-2">
                    <i class="fas fa-plus-circle text-primary-600"></i>
                    أضف مشروعك
                </h3>
                <p id="execution-new-desc" class="text-xs text-gray-500 dark:text-gray-300 mb-4">هذه الواجهة للعرض العام فقط. إنشاء المشاريع الفعلية يتم من خلال لوحة المنشأة.</p>
                <div class="bg-gray-50 dark:bg-secondary-800/60 rounded-xl p-4 text-xs text-gray-600 dark:text-gray-200 border border-dashed border-gray-200 dark:border-secondary-800">
                    <p class="mb-2 flex items-center gap-2">
                        <i class="fas fa-circle-info text-primary-600"></i>
                        لإنشاء طلب تنفيذ حقيقي وإدارته بالكامل:
                    </p>
                    <ul class="space-y-1 mr-5 list-disc">
                        <li>قم بتسجيل الدخول أو إنشاء حساب.</li>
                        <li>أنشئ منشأتك ثم ادخل إلى لوحة المنشأة.</li>
                        <li>اذهب إلى قسم "طلبات التنفيذ" واستخدم مساحة العمل الكاملة لإنشاء الطلب ومتابعة العروض.</li>
                    </ul>
                    <div class="mt-3 flex flex-wrap gap-2">
                        <a id="execution-cta" href="{{ route('login') }}" class="inline-flex items-center px-4 py-1.5 rounded-full bg-primary-900 text-white text-xs font-semibold hover:bg-primary-800">
                            <i class="fas fa-arrow-left ml-1 text-[10px]"></i>
                            سجّل للدخول وإضافة مشروع
                        </a>
                    </div>
                </div>
            </div>

            <div id="live" class="mb-8">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-base md:text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                        <i class="fas fa-bolt text-primary-600"></i>
                        نشاط السوق المباشر
                    </h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3 text-xs">
                    @forelse($openRequests->take(4) as $req)
                        @php
                            $t = $req->translations->firstWhere('locale', app()->getLocale());
                        @endphp
                        <div class="glass rounded-xl p-3 border border-white/80 dark:border-secondary-800 card-shadow">
                            <p class="text-[11px] text-gray-500 dark:text-gray-300 mb-1 flex items-center gap-1">
                                <i class="fas fa-clock text-primary-600"></i>
                                {{ $req->created_at->diffForHumans() }}
                            </p>
                            <p class="font-semibold text-gray-800 dark:text-white text-xs line-clamp-2 mb-1">{{ $t->title ?? ('طلب #' . $req->id) }}</p>
                            <p class="text-[11px] text-gray-500 dark:text-gray-300 flex items-center gap-2">
                                <span class="inline-flex items-center gap-1"><i class="fas fa-gavel text-primary-600"></i>{{ $req->bids_count }} عروض</span>
                                @if($req->due_date)
                                    <span class="inline-flex items-center gap-1"><i class="fas fa-calendar text-primary-600"></i>{{ $req->due_date->format('Y-m-d') }}</span>
                                @endif
                            </p>
                        </div>
                    @empty
                        <p class="text-xs text-gray-500">لا يوجد نشاط حالياً، ابدأ أنت بأول طلب من لوحة المنشأة.</p>
                    @endforelse
                </div>
            </div>

            <div id="open" class="mb-10">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">
                    <h3 class="text-base md:text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                        <i class="fas fa-list text-primary-600"></i>
                        الطلبات المفتوحة للعروض
                    </h3>
                    <form method="GET" action="{{ route('public.execution.marketplace') }}" class="w-full md:w-auto flex flex-wrap items-center gap-2 text-[11px] bg-white/70 dark:bg-secondary-900/60 border border-gray-200 dark:border-secondary-800 rounded-full px-2 py-1.5">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="عنوان الطلب" class="flex-1 min-w-[120px] border-0 focus:ring-0 focus:outline-none bg-transparent px-2" />
                        <input type="text" name="type" value="{{ request('type') }}" placeholder="نوع (مقاولات، صيانة...)" class="w-32 border-0 focus:ring-0 focus:outline-none bg-transparent px-2 border-r border-gray-200 dark:border-secondary-800" />
                        <input type="number" name="min_budget" value="{{ request('min_budget') }}" placeholder="ميزانية من" class="w-24 border-0 focus:ring-0 focus:outline-none bg-transparent px-2 border-r border-gray-200 dark:border-secondary-800" />
                        <input type="number" name="max_budget" value="{{ request('max_budget') }}" placeholder="ميزانية إلى" class="w-24 border-0 focus:ring-0 focus:outline-none bg-transparent px-2 border-r border-gray-200 dark:border-secondary-800" />
                        <select name="status" class="border-0 bg-transparent focus:ring-0 focus:outline-none text-gray-600 dark:text-gray-200 px-1 border-r border-gray-200 dark:border-secondary-800">
                            <option value="open" {{ request('status','open') === 'open' ? 'selected' : '' }}>مفتوحة</option>
                            <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>منتهية</option>
                            <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>الكل</option>
                        </select>
                        <button type="submit" class="inline-flex items-center px-3 py-1.5 rounded-full bg-primary-600 text-white font-semibold hover:bg-primary-700">
                            <i class="fas fa-filter ml-1 text-[10px]"></i>
                            تصفية
                        </button>
                    </form>
                </div>

                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">أحدث المشاريع</h2>
                        <p class="text-gray-600 dark:text-gray-300">{{ method_exists($openRequests, 'total') ? ($openRequests->total() ?? 0) : ($openRequests->count() ?? 0) }} مشروع متاح</p>
                    </div>
                </div>

                @if(request()->hasAny(['q','type','min_budget','max_budget','status']))
                    <div class="flex flex-wrap items-center gap-2 mb-3 text-[11px]">
                        <span class="text-gray-500">الفلاتر المطبقة:</span>
                        @if(request('q'))
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-gray-100 dark:bg-secondary-800 text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-secondary-700">
                                <i class="fas fa-search ml-1 text-[9px]"></i> "{{ request('q') }}"
                            </span>
                        @endif
                        @if(request('type'))
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-gray-100 dark:bg-secondary-800 text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-secondary-700">
                                <i class="fas fa-tag ml-1 text-[9px]"></i> {{ request('type') }}
                            </span>
                        @endif
                        @if(request('min_budget'))
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-primary-50 dark:bg-secondary-800 text-primary-700 dark:text-primary-200 border border-primary-100 dark:border-secondary-700">
                                من {{ number_format(request('min_budget')) }} ريال
                            </span>
                        @endif
                        @if(request('max_budget'))
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-primary-50 dark:bg-secondary-800 text-primary-700 dark:text-primary-200 border border-primary-100 dark:border-secondary-700">
                                إلى {{ number_format(request('max_budget')) }} ريال
                            </span>
                        @endif
                        @if(request('status') && request('status') !== 'open')
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-primary-50 dark:bg-secondary-800 text-primary-700 dark:text-primary-200 border border-primary-100 dark:border-secondary-700">
                                الحالة: {{ request('status') === 'closed' ? 'منتهية' : 'الكل' }}
                            </span>
                        @endif
                    </div>
                @endif

                @if(isset($openRequests) && $openRequests->count() > 0)
                    <x-multi-view-grid
                        :items="$openRequests"
                        type="execution_requests"
                        :showPagination="false"
                        :showViewToggle="true"
                        idPrefix="execution-requests"
                    />
                @else
                    <p class="text-sm text-gray-500">لا توجد طلبات مفتوحة حالياً.</p>
                @endif

                <div class="mt-4">
                    {{ $openRequests->links() }}
                </div>
            </div>

            <div id="ended" class="mb-8">
                <h3 class="text-base md:text-lg font-semibold text-gray-900 mb-3 flex items-center gap-2">
                    <i class="fas fa-archive text-gray-500"></i>
                    لمحات من مشاريع منتهية
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                    @forelse($endedRequests as $req)
                        @php
                            $t = $req->translations->firstWhere('locale', app()->getLocale());
                        @endphp
                        <div class="bg-white/90 dark:bg-secondary-900/60 rounded-2xl border border-gray-100 dark:border-secondary-800 card-shadow p-4">
                            <div class="flex items-center justify-between mb-1 text-[11px] text-gray-500 dark:text-gray-300">
                                <span class="inline-flex items-center gap-1">
                                    <i class="fas fa-flag-checkered text-primary-600"></i>
                                    مشروع منتهي
                                </span>
                                <span>{{ $req->updated_at->format('Y-m-d') }}</span>
                            </div>
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-1 line-clamp-2">{{ $t->title ?? ('طلب #' . $req->id) }}</h4>
                            @if($t && $t->description)
                                <p class="text-[11px] text-gray-600 dark:text-gray-300 line-clamp-3 mb-2">{{ $t->description }}</p>
                            @endif
                            <div class="flex items-center justify-between text-[11px] text-gray-500 dark:text-gray-300">
                                <span class="inline-flex items-center gap-1">
                                    <i class="fas fa-gavel text-primary-600"></i>
                                    {{ $req->bids_count ?? $req->bids()->count() }} عروض إجمالية
                                </span>
                                <span class="inline-flex items-center gap-1">
                                    <i class="fas fa-circle text-[6px] {{ $req->status === 'completed' ? 'text-primary-600' : 'text-gray-400' }}"></i>
                                    {{ $req->status }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-gray-500">لم تُسجَّل مشاريع منتهية بعد.</p>
                    @endforelse
                </div>
            </div>
        </section>
    </div>

@push('scripts')
<script>
    (function () {
        const copy = {
            contractor: {
                topTitle: 'منصة المشاريع',
                topSubtitle: 'سوق المقاولات والمشاريع في المملكة',
                heroTitle: 'مشاريع جاهزة للتنفيذ',
                heroDesc: 'استعرض المشاريع المفتوحة، وقدّم عرضك كمقاول بناءً على النوع والميزانية والمدة.',
                newTitle: 'اعرض مشروعك على المقاولين',
                newDesc: 'هذه الواجهة للعرض العام فقط. إنشاء المشاريع الفعلية يتم من خلال لوحة المنشأة.',
                cta: 'سجّل للدخول وإضافة مشروع'
            },
            owner_individual: {
                topTitle: 'منصة المشاريع',
                topSubtitle: 'اعرض مشروعك واستقبل عروض المقاولين',
                heroTitle: 'اطرح مشروعك بثقة',
                heroDesc: 'حدّد احتياجك وميزانيتك وموعدك، ثم استقبل عروضاً من مقاولين مناسبين.',
                newTitle: 'أضف مشروعك',
                newDesc: 'هذه الواجهة للعرض العام فقط. إنشاء المشاريع الفعلية يتم من خلال لوحة المنشأة.',
                cta: 'سجّل للدخول وإضافة مشروع'
            },
            owner_company: {
                topTitle: 'منصة المشاريع',
                topSubtitle: 'منصة موحدة لطرح مشاريع الشركات واستقبال العروض',
                heroTitle: 'اطرح مشاريع شركتك',
                heroDesc: 'اعرض نطاق العمل والمتطلبات والميزانية، واحصل على عروض تنفيذ قابلة للمقارنة.',
                newTitle: 'أضف مشروع شركتك',
                newDesc: 'هذه الواجهة للعرض العام فقط. إنشاء المشاريع الفعلية يتم من خلال لوحة المنشأة.',
                cta: 'سجّل للدخول وإضافة مشروع'
            },
            owner_government: {
                topTitle: 'منصة المشاريع',
                topSubtitle: 'مساحة لعرض المشاريع واستقبال عروض التنفيذ',
                heroTitle: 'اطرح مشاريعك واستقبل عروضاً مؤهلة',
                heroDesc: 'اعرض تفاصيل المشروع والمتطلبات، ثم قارن العروض وفق الميزانية والمدة.',
                newTitle: 'أضف مشروعك',
                newDesc: 'هذه الواجهة للعرض العام فقط. إنشاء المشاريع الفعلية يتم من خلال لوحة المنشأة.',
                cta: 'سجّل للدخول وإضافة مشروع'
            },
            interested: {
                topTitle: 'منصة المشاريع',
                topSubtitle: 'استكشف المشاريع واتجاهات سوق المقاولات',
                heroTitle: 'استكشف المشاريع المتاحة',
                heroDesc: 'تصفّح المشاريع المفتوحة وتعرّف على الأنواع والميزانيات والجدول الزمني.',
                newTitle: 'ابدأ من لوحة المنشأة',
                newDesc: 'هذه الواجهة للعرض العام فقط. إنشاء المشاريع الفعلية يتم من خلال لوحة المنشأة.',
                cta: 'تسجيل الدخول للبدء'
            }
        };

        function setText(id, value) {
            const el = document.getElementById(id);
            if (el && typeof value === 'string') {
                el.textContent = value;
            }
        }

        function applyMode(mode) {
            const m = copy[mode] ? mode : 'contractor';
            const data = copy[m];
            setText('execution-top-title', data.topTitle);
            setText('execution-top-subtitle', data.topSubtitle);
            setText('execution-hero-title', data.heroTitle);
            setText('execution-hero-desc', data.heroDesc);
            setText('execution-new-title', data.newTitle);
            setText('execution-new-desc', data.newDesc);
            setText('execution-cta', data.cta);
        }

        const initial = document.documentElement.dataset.browseAs || (document.body ? document.body.dataset.browseAs : null) || 'contractor';
        applyMode(initial);
        window.addEventListener('browseAsChanged', function (e) {
            applyMode(e.detail && e.detail.value ? e.detail.value : 'contractor');
        });
    })();
</script>
@endpush

@endsection
