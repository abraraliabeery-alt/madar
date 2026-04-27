@extends('layouts.app')

@section('title', 'منصة المشاريع')

@push('styles')
    <style>
        .glass {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
        }
        .card-shadow {
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
        }
    </style>
@endpush

@section('content')
    <header class="bg-indigo-950/95 text-white shadow-lg">
        <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-emerald-400 flex items-center justify-center text-indigo-950 font-black text-xl">
                    <i class="fas fa-gavel"></i>
                </div>
                <div>
                    <h1 id="execution-top-title" class="text-lg md:text-xl font-bold tracking-tight">منصة المشاريع</h1>
                    <p id="execution-top-subtitle" class="text-[11px] text-indigo-100">سوق المقاولات والمشاريع في المملكة</p>
                </div>
            </div>
            <nav class="hidden md:flex items-center gap-6 text-sm">
                <a href="#new-request" class="hover:text-emerald-300 flex items-center gap-1"><i class="fas fa-plus-circle text-xs"></i> أضف مشروعك</a>
                <a href="#live" class="hover:text-emerald-300 flex items-center gap-1"><i class="fas fa-bolt text-xs"></i> مباشر الآن</a>
                <a href="#open" class="hover:text-emerald-300 flex items-center gap-1"><i class="fas fa-list text-xs"></i> الطلبات الحالية</a>
                <a href="#ended" class="hover:text-emerald-300 flex items-center gap-1"><i class="fas fa-archive text-xs"></i> الأرشيف</a>
            </nav>
            <div class="flex items-center gap-2 text-xs">
                @auth
                    <span class="hidden sm:inline-flex items-center px-2.5 py-1 rounded-full bg-indigo-800/80 border border-indigo-500/60">
                        <i class="fas fa-user ml-1 text-[10px]"></i>
                        {{ auth()->user()->name ?? 'حساب مستخدم' }}
                    </span>
                    <a href="{{ route('facility.dashboard') }}" class="hidden sm:inline-flex items-center px-3 py-1 rounded-full bg-emerald-400 text-indigo-950 text-xs font-semibold hover:bg-emerald-300">
                        لوحة المنشأة
                    </a>
                @else
                    <a href="{{ route('login') }}" class="inline-flex items-center px-3 py-1.5 rounded-full bg-white/10 border border-white/30 text-xs hover:bg-white/20">
                        <i class="fas fa-sign-in-alt ml-1 text-[11px]"></i>
                        تسجيل الدخول
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <main class="flex-1 bg-gradient-to-b from-sky-50 via-slate-50 to-indigo-50">
        <section class="max-w-6xl mx-auto px-4 pt-8 pb-10">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start mb-8">
                <div class="lg:col-span-2 space-y-4">
                    <h2 id="execution-hero-title" class="text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight">سوق المقاولات والمشاريع في المملكة</h2>
                    <p id="execution-hero-desc" class="text-sm md:text-base text-slate-600 leading-relaxed max-w-xl">اربط مشروعك بالمقاولين المناسبين، واطّلع على المشاريع المتاحة بحسب النوع والميزانية والمدة.</p>
                    <div class="flex flex-wrap gap-3 text-[11px] text-slate-600">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100">
                            <i class="fas fa-shield-check ml-1 text-[10px]"></i>
                            نظام موحّد لطلبات التنفيذ
                        </span>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-100">
                            <i class="fas fa-language ml-1 text-[10px]"></i>
                            يدعم تعدد اللغات في العناوين والوصف
                        </span>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-100">
                            <i class="fas fa-bolt ml-1 text-[10px]"></i>
                            متابعة لحظية للحالة والعروض
                        </span>
                    </div>
                </div>
                <div class="glass card-shadow rounded-2xl p-4 md:p-5 border border-white/60">
                    <div class="flex items-center justify-between mb-3 text-xs text-slate-500">
                        <span class="flex items-center gap-1"><i class="fas fa-signal text-emerald-500"></i> حالة السوق الآن</span>
                    </div>
                    <div class="grid grid-cols-3 gap-3 text-center text-xs">
                        <div class="bg-slate-900 text-white rounded-xl p-3">
                            <div class="text-[11px] text-slate-300 mb-1">طلبات مفتوحة</div>
                            <div class="text-xl font-bold">{{ $stats['total_open'] ?? 0 }}</div>
                        </div>
                        <div class="bg-emerald-50 rounded-xl p-3">
                            <div class="text-[11px] text-emerald-700 mb-1">طلبات منتهية</div>
                            <div class="text-xl font-semibold text-emerald-700">{{ $stats['total_closed'] ?? 0 }}</div>
                        </div>
                        <div class="bg-indigo-50 rounded-xl p-3">
                            <div class="text-[11px] text-indigo-700 mb-1">إجمالي العروض</div>
                            <div class="text-xl font-semibold text-indigo-700">{{ $stats['total_bids'] ?? 0 }}</div>
                        </div>
                    </div>
                    @if($highlightRequest)
                        @php
                            $t = $highlightRequest->translations->firstWhere('locale', app()->getLocale());
                        @endphp
                        <div class="mt-4 text-[11px] text-slate-600 border-t border-slate-200 pt-3">
                            <div class="flex items-center justify-between mb-1">
                                <span class="font-semibold text-slate-800 flex items-center gap-1">
                                    <i class="fas fa-bullhorn text-emerald-500"></i>
                                    طلب نشط الآن
                                </span>
                                <span class="text-slate-400">{{ $highlightRequest->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="text-slate-700 line-clamp-2">{{ $t->title ?? ('طلب #' . $highlightRequest->id) }}</div>
                        </div>
                    @endif
                </div>
            </div>

            <div id="new-request" class="glass card-shadow rounded-2xl p-5 md:p-7 mb-10 border border-white/70">
                <h3 id="execution-new-title" class="text-lg md:text-xl font-semibold text-slate-900 mb-1 flex items-center gap-2">
                    <i class="fas fa-plus-circle text-emerald-500"></i>
                    أضف مشروعك
                </h3>
                <p id="execution-new-desc" class="text-xs text-slate-500 mb-4">هذه الواجهة للعرض العام فقط. إنشاء المشاريع الفعلية يتم من خلال لوحة المنشأة.</p>
                <div class="bg-slate-50 rounded-xl p-4 text-xs text-slate-600 border border-dashed border-slate-200">
                    <p class="mb-2 flex items-center gap-2">
                        <i class="fas fa-circle-info text-indigo-500"></i>
                        لإنشاء طلب تنفيذ حقيقي وإدارته بالكامل:
                    </p>
                    <ul class="space-y-1 mr-5 list-disc">
                        <li>قم بتسجيل الدخول أو إنشاء حساب.</li>
                        <li>أنشئ منشأتك ثم ادخل إلى لوحة المنشأة.</li>
                        <li>اذهب إلى قسم "طلبات التنفيذ" واستخدم مساحة العمل الكاملة لإنشاء الطلب ومتابعة العروض.</li>
                    </ul>
                    <div class="mt-3 flex flex-wrap gap-2">
                        <a id="execution-cta" href="{{ route('login') }}" class="inline-flex items-center px-4 py-1.5 rounded-full bg-slate-900 text-white text-xs font-semibold hover:bg-slate-800">
                            <i class="fas fa-arrow-left ml-1 text-[10px]"></i>
                            سجّل للدخول وإضافة مشروع
                        </a>
                    </div>
                </div>
            </div>

            <div id="live" class="mb-8">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-base md:text-lg font-semibold text-slate-900 flex items-center gap-2">
                        <i class="fas fa-bolt text-amber-500"></i>
                        نشاط السوق المباشر
                    </h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3 text-xs">
                    @forelse($openRequests->take(4) as $req)
                        @php
                            $t = $req->translations->firstWhere('locale', app()->getLocale());
                        @endphp
                        <div class="glass rounded-xl p-3 border border-white/80 card-shadow">
                            <p class="text-[11px] text-slate-500 mb-1 flex items-center gap-1">
                                <i class="fas fa-clock text-emerald-500"></i>
                                {{ $req->created_at->diffForHumans() }}
                            </p>
                            <p class="font-semibold text-slate-800 text-xs line-clamp-2 mb-1">{{ $t->title ?? ('طلب #' . $req->id) }}</p>
                            <p class="text-[11px] text-slate-500 flex items-center gap-2">
                                <span class="inline-flex items-center gap-1"><i class="fas fa-gavel text-amber-500"></i>{{ $req->bids_count }} عروض</span>
                                @if($req->due_date)
                                    <span class="inline-flex items-center gap-1"><i class="fas fa-calendar text-indigo-500"></i>{{ $req->due_date->format('Y-m-d') }}</span>
                                @endif
                            </p>
                        </div>
                    @empty
                        <p class="text-xs text-slate-500">لا يوجد نشاط حالياً، ابدأ أنت بأول طلب من لوحة المنشأة.</p>
                    @endforelse
                </div>
            </div>

            <div id="open" class="mb-10">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">
                    <h3 class="text-base md:text-lg font-semibold text-slate-900 flex items-center gap-2">
                        <i class="fas fa-list text-indigo-500"></i>
                        الطلبات المفتوحة للعروض
                    </h3>
                    <form method="GET" action="{{ route('public.execution.marketplace') }}" class="w-full md:w-auto flex flex-wrap items-center gap-2 text-[11px] bg-white/70 border border-slate-200 rounded-full px-2 py-1.5">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="عنوان الطلب" class="flex-1 min-w-[120px] border-0 focus:ring-0 focus:outline-none bg-transparent px-2" />
                        <input type="text" name="type" value="{{ request('type') }}" placeholder="نوع (مقاولات، صيانة...)" class="w-32 border-0 focus:ring-0 focus:outline-none bg-transparent px-2 border-r border-slate-200" />
                        <input type="number" name="min_budget" value="{{ request('min_budget') }}" placeholder="ميزانية من" class="w-24 border-0 focus:ring-0 focus:outline-none bg-transparent px-2 border-r border-slate-200" />
                        <input type="number" name="max_budget" value="{{ request('max_budget') }}" placeholder="ميزانية إلى" class="w-24 border-0 focus:ring-0 focus:outline-none bg-transparent px-2 border-r border-slate-200" />
                        <select name="status" class="border-0 bg-transparent focus:ring-0 focus:outline-none text-slate-600 px-1 border-r border-slate-200">
                            <option value="open" {{ request('status','open') === 'open' ? 'selected' : '' }}>مفتوحة</option>
                            <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>منتهية</option>
                            <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>الكل</option>
                        </select>
                        <button type="submit" class="inline-flex items-center px-3 py-1.5 rounded-full bg-indigo-600 text-white font-semibold hover:bg-indigo-700">
                            <i class="fas fa-filter ml-1 text-[10px]"></i>
                            تصفية
                        </button>
                    </form>
                </div>

                @if(request()->hasAny(['q','type','min_budget','max_budget','status']))
                    <div class="flex flex-wrap items-center gap-2 mb-3 text-[11px]">
                        <span class="text-slate-500">الفلاتر المطبقة:</span>
                        @if(request('q'))
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-slate-100 text-slate-700 border border-slate-200">
                                <i class="fas fa-search ml-1 text-[9px]"></i> "{{ request('q') }}"
                            </span>
                        @endif
                        @if(request('type'))
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-slate-100 text-slate-700 border border-slate-200">
                                <i class="fas fa-tag ml-1 text-[9px]"></i> {{ request('type') }}
                            </span>
                        @endif
                        @if(request('min_budget'))
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100">
                                من {{ number_format(request('min_budget')) }} ريال
                            </span>
                        @endif
                        @if(request('max_budget'))
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100">
                                إلى {{ number_format(request('max_budget')) }} ريال
                            </span>
                        @endif
                        @if(request('status') && request('status') !== 'open')
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-100">
                                الحالة: {{ request('status') === 'closed' ? 'منتهية' : 'الكل' }}
                            </span>
                        @endif
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    @forelse($openRequests as $req)
                        @php
                            $t = $req->translations->firstWhere('locale', app()->getLocale());
                            $budgetRange = null;
                            if(!is_null($req->budget_min) || !is_null($req->budget_max)) {
                                $min = $req->budget_min ? number_format($req->budget_min) : null;
                                $max = $req->budget_max ? number_format($req->budget_max) : null;
                                $budgetRange = trim(($min ? $min : '') . ' - ' . ($max ? $max : ''));
                            }
                        @endphp
                        <article class="bg-white/90 rounded-2xl border border-slate-100 card-shadow p-4 flex flex-col justify-between">
                            <div class="space-y-2 mb-3">
                                <div class="flex items-center justify-between text-[11px] text-slate-500 mb-1">
                                    <span class="inline-flex items-center gap-1">
                                        <i class="fas fa-circle text-emerald-400 text-[7px]"></i>
                                        طلب مفتوح
                                    </span>
                                    <span>{{ $req->created_at->format('Y-m-d') }}</span>
                                </div>
                                <h4 class="text-sm font-semibold text-slate-900 leading-snug line-clamp-2">{{ $t->title ?? ('طلب #' . $req->id) }}</h4>
                                @if($t && $t->description)
                                    <p class="text-[11px] text-slate-600 line-clamp-3">{{ $t->description }}</p>
                                @endif
                                <div class="flex flex-wrap items-center gap-2 text-[11px] mt-1">
                                    @if($req->type)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-slate-50 text-slate-700 border border-slate-200">
                                            <i class="fas fa-tag ml-1 text-[9px]"></i>
                                            {{ $req->type }}
                                        </span>
                                    @endif
                                    @if($budgetRange)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100">
                                            <i class="fas fa-wallet ml-1 text-[9px]"></i>
                                            {{ $budgetRange }} ريال
                                        </span>
                                    @endif
                                    @if($req->due_date)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-100">
                                            <i class="fas fa-calendar ml-1 text-[9px]"></i>
                                            حتى {{ $req->due_date->format('Y-m-d') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center justify-between mt-2 pt-2 border-t border-slate-100 text-[11px]">
                                <span class="inline-flex items-center gap-1 text-slate-500">
                                    <i class="fas fa-gavel text-amber-500"></i>
                                    {{ $req->bids_count }} عروض مقدّمة
                                </span>
                                <a href="{{ route('public.execution.show', $req) }}" class="inline-flex items-center px-3 py-1.5 rounded-full bg-slate-900 text-white text-[11px] font-semibold hover:bg-slate-800">
                                    عرض التفاصيل
                                </a>
                            </div>
                        </article>
                    @empty
                        <p class="text-xs text-slate-500">لا توجد طلبات مفتوحة حالياً.</p>
                    @endforelse
                </div>

                <div class="mt-4">
                    {{ $openRequests->links() }}
                </div>
            </div>

            <div id="ended" class="mb-8">
                <h3 class="text-base md:text-lg font-semibold text-slate-900 mb-3 flex items-center gap-2">
                    <i class="fas fa-archive text-slate-500"></i>
                    لمحات من مشاريع منتهية
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                    @forelse($endedRequests as $req)
                        @php
                            $t = $req->translations->firstWhere('locale', app()->getLocale());
                        @endphp
                        <div class="bg-white/90 rounded-2xl border border-slate-100 card-shadow p-4">
                            <div class="flex items-center justify-between mb-1 text-[11px] text-slate-500">
                                <span class="inline-flex items-center gap-1">
                                    <i class="fas fa-flag-checkered text-emerald-500"></i>
                                    مشروع منتهي
                                </span>
                                <span>{{ $req->updated_at->format('Y-m-d') }}</span>
                            </div>
                            <h4 class="text-sm font-semibold text-slate-900 mb-1 line-clamp-2">{{ $t->title ?? ('طلب #' . $req->id) }}</h4>
                            @if($t && $t->description)
                                <p class="text-[11px] text-slate-600 line-clamp-3 mb-2">{{ $t->description }}</p>
                            @endif
                            <div class="flex items-center justify-between text-[11px] text-slate-500">
                                <span class="inline-flex items-center gap-1">
                                    <i class="fas fa-gavel text-amber-500"></i>
                                    {{ $req->bids_count ?? $req->bids()->count() }} عروض إجمالية
                                </span>
                                <span class="inline-flex items-center gap-1">
                                    <i class="fas fa-circle text-[6px] {{ $req->status === 'completed' ? 'text-emerald-500' : 'text-slate-400' }}"></i>
                                    {{ $req->status }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-500">لم تُسجَّل مشاريع منتهية بعد.</p>
                    @endforelse
                </div>
            </div>
        </section>
    </main>

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
