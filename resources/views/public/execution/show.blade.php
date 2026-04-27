@extends('layouts.app')

@section('title', 'تفاصيل طلب التنفيذ #' . $executionRequest->id)

@section('content')
    <header class="bg-slate-900 text-white py-3 shadow-md">
        <div class="max-w-5xl mx-auto px-4 flex items-center justify-between">
            <a href="{{ route('public.execution.marketplace') }}" class="flex items-center gap-2 text-xs text-slate-200 hover:text-white">
                <i class="fas fa-arrow-right"></i>
                العودة لسوق التنفيذ
            </a>
            <div class="flex items-center gap-2 text-xs">
                @auth
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-slate-800 text-[11px]">
                        <i class="fas fa-user ml-1 text-[9px]"></i>
                        {{ auth()->user()->name ?? 'حساب مستخدم' }}
                    </span>
                @else
                    <a href="{{ route('login') }}" class="inline-flex items-center px-3 py-1.5 rounded-full bg-emerald-400 text-slate-900 text-[11px] font-semibold hover:bg-emerald-300">
                        <i class="fas fa-sign-in-alt ml-1 text-[9px]"></i>
                        تسجيل الدخول لتقديم عرض
                    </a>
                @endauth
            </div>
        </div>
    </header>

    @php
        $t = $executionRequest->translations->firstWhere('locale', app()->getLocale());
        $budgetRange = null;
        if(!is_null($executionRequest->budget_min) || !is_null($executionRequest->budget_max)) {
            $min = $executionRequest->budget_min ? number_format($executionRequest->budget_min) : null;
            $max = $executionRequest->budget_max ? number_format($executionRequest->budget_max) : null;
            $budgetRange = trim(($min ? $min : '') . ' - ' . ($max ? $max : ''));
        }
    @endphp

    <main class="flex-1 bg-slate-50">
        <section class="max-w-5xl mx-auto px-4 py-6 space-y-5">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4 mb-3">
                    <div class="space-y-2">
                        <div class="inline-flex items-center px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 text-[11px] border border-emerald-100">
                            <i class="fas fa-circle text-[7px] ml-1"></i>
                            طلب تنفيذ مفتوح
                        </div>
                        <h1 class="text-xl md:text-2xl font-bold text-slate-900">
                            {{ $t->title ?? ('طلب تنفيذ #' . $executionRequest->id) }}
                        </h1>
                        <p class="text-xs text-slate-500 flex flex-wrap gap-3">
                            <span><i class="fas fa-clock ml-1 text-amber-500"></i> {{ $executionRequest->created_at->diffForHumans() }}</span>
                            @if($executionRequest->due_date)
                                <span><i class="fas fa-calendar ml-1 text-indigo-500"></i> حتى {{ $executionRequest->due_date->format('Y-m-d') }}</span>
                            @endif
                            @if($executionRequest->type)
                                <span><i class="fas fa-tag ml-1 text-slate-500"></i> {{ $executionRequest->type }}</span>
                            @endif
                        </p>
                    </div>
                    <div class="text-xs text-slate-600 bg-slate-50 rounded-xl p-3 min-w-[180px]">
                        <div class="flex items-center justify-between mb-1">
                            <span>العروض المستلمة</span>
                            <span class="font-semibold text-slate-900"><i class="fas fa-gavel ml-1 text-amber-500"></i> {{ $executionRequest->bids->count() }}</span>
                        </div>
                        <div class="flex flex-col gap-1">
                            @if($budgetRange)
                                <span><i class="fas fa-wallet ml-1 text-emerald-500"></i> {{ $budgetRange }} ريال</span>
                            @else
                                <span><i class="fas fa-wallet ml-1 text-slate-400"></i> الميزانية غير محددة</span>
                            @endif
                            <span><i class="fas fa-signal ml-1 text-slate-400"></i> الحالة: {{ $executionRequest->status }}</span>
                        </div>
                    </div>
                </div>

                @if($t && $t->description)
                    <div class="border-t border-slate-100 pt-3 mt-2">
                        <h2 class="text-sm font-semibold text-slate-800 mb-1">وصف الطلب</h2>
                        <p class="text-sm text-slate-700 whitespace-pre-line leading-relaxed">{{ $t->description }}</p>
                    </div>
                @endif

                <div class="border-t border-slate-100 pt-3 mt-3">
                    <h2 class="text-sm font-semibold text-slate-800 mb-2 flex items-center gap-2">
                        <i class="fas fa-timeline text-indigo-500"></i>
                        الخط الزمني للطلب
                    </h2>
                    <div class="flex items-center gap-3 text-[11px] text-slate-600 flex-wrap">
                        <div class="flex items-center gap-1">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span>تم إنشاء الطلب {{ $executionRequest->created_at->format('Y-m-d H:i') }}</span>
                        </div>
                        <span class="h-px w-6 bg-slate-200"></span>
                        <div class="flex items-center gap-1">
                            <span class="w-2 h-2 rounded-full {{ $executionRequest->bids->count() ? 'bg-emerald-500' : 'bg-slate-300' }}"></span>
                            <span>
                                @if($executionRequest->bids->count())
                                    استلام {{ $executionRequest->bids->count() }} عرض/عروض
                                @else
                                    بانتظار أول عرض من المنفِّذين
                                @endif
                            </span>
                        </div>
                        <span class="h-px w-6 bg-slate-200"></span>
                        <div class="flex items-center gap-1">
                            @php
                                $isClosed = in_array($executionRequest->status, ['completed','closed','cancelled']);
                            @endphp
                            <span class="w-2 h-2 rounded-full {{ $isClosed ? 'bg-emerald-500' : 'bg-slate-300' }}"></span>
                            <span>
                                @if($isClosed)
                                    الطلب منتهٍ بالحالة: {{ $executionRequest->status }}
                                @else
                                    الطلب ما زال قيد المتابعة
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 items-start">
                <div class="md:col-span-2 space-y-4">
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4">
                        <h2 class="text-sm font-semibold text-slate-800 mb-3 flex items-center gap-2">
                            <i class="fas fa-gavel text-amber-500"></i>
                            العروض المقدَّمة
                        </h2>
                        <div class="space-y-3 text-xs">
                            @forelse($executionRequest->bids as $bid)
                                <div class="border border-slate-100 rounded-xl p-3 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                                    <div class="space-y-1">
                                        <div class="text-slate-800 font-semibold">
                                            {{ optional($bid->executorUser)->name ?? 'منفِّذ غير معرّف' }}
                                        </div>
                                        <div class="text-slate-600 flex flex-wrap gap-3">
                                            <span><i class="fas fa-wallet ml-1 text-emerald-500"></i> {{ $bid->price_total ? number_format($bid->price_total) . ' ' . ($bid->currency ?? 'SAR') : 'لم يحدد السعر' }}</span>
                                            <span><i class="fas fa-calendar-day ml-1 text-indigo-500"></i> {{ $bid->duration_days ? $bid->duration_days . ' يوم' : 'مدة غير محددة' }}</span>
                                            <span><i class="fas fa-shield-halved ml-1 text-slate-500"></i> {{ $bid->warranty_months ? $bid->warranty_months . ' شهر ضمان' : 'بدون ضمان معلَن' }}</span>
                                        </div>
                                        @if(isset($bid->data['notes']) && $bid->data['notes'])
                                            <p class="text-slate-600 mt-1 whitespace-pre-line">{{ $bid->data['notes'] }}</p>
                                        @endif
                                    </div>
                                    <div class="flex flex-col items-end gap-1 text-[11px]">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full
                                            @if($bid->status === 'accepted') bg-emerald-100 text-emerald-700
                                            @elseif($bid->status === 'rejected') bg-red-100 text-red-700
                                            @else bg-slate-100 text-slate-700 @endif">
                                            {{ $bid->status }}
                                        </span>
                                        <span class="text-slate-400">{{ $bid->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-slate-500 text-xs">لا توجد عروض حتى الآن على هذا الطلب.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4">
                        <h2 class="text-sm font-semibold text-slate-800 mb-3 flex items-center gap-2">
                            <i class="fas fa-handshake text-emerald-500"></i>
                            قدّم عرضك على هذا الطلب
                        </h2>

                        @auth
                            @error('execution')
                                <p class="mb-2 text-[11px] text-red-600">{{ $message }}</p>
                            @enderror
                            <form method="POST" action="{{ route('public.execution.bids.store', $executionRequest) }}" class="space-y-3 text-xs">
                                @csrf
                                <div>
                                    <label class="block text-[11px] font-medium text-slate-700 mb-1">قيمة العرض الإجمالية (بالريال)</label>
                                    <input type="number" step="0.01" name="price_total" class="w-full border border-slate-200 rounded-md px-3 py-1.5 focus:ring-1 focus:ring-emerald-400 focus:border-emerald-400" required>
                                </div>
                                <div>
                                    <label class="block text-[11px] font-medium text-slate-700 mb-1">رسالة العرض / ملخص خطة العمل</label>
                                    <textarea name="message" rows="4" class="w-full border border-slate-200 rounded-md px-3 py-1.5 focus:ring-1 focus:ring-emerald-400 focus:border-emerald-400" placeholder="اشرح بإيجاز ما يشمله هذا العرض، مراحل التنفيذ، شروط خاصة..." required></textarea>
                                </div>
                                <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 rounded-lg bg-emerald-500 text-white text-xs font-semibold hover:bg-emerald-600">
                                    <i class="fas fa-paper-plane ml-1 text-[10px]"></i>
                                    إرسال العرض
                                </button>
                            </form>
                        @else
                            <p class="text-xs text-slate-600 mb-3">لتقديم عرض على هذا الطلب، تحتاج لتسجيل الدخول بحسابك.</p>
                            <a href="{{ route('login') }}" class="w-full inline-flex items-center justify-center px-4 py-2 rounded-lg bg-emerald-500 text-white text-xs font-semibold hover:bg-emerald-600">
                                <i class="fas fa-sign-in-alt ml-1 text-[10px]"></i>
                                تسجيل الدخول وتقديم عرض
                            </a>
                        @endauth
                    </div>

                    <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-3 text-[11px] text-emerald-800">
                        <p class="font-semibold mb-1 flex items-center gap-1">
                            <i class="fas fa-circle-info"></i>
                            تذكير مهم
                        </p>
                        <p>هذه الصفحة للعرض العام والتقديم، إدارة الطلبات والعروض بشكل كامل (قبول، رفض، تتبع تنفيذ) تتم داخل لوحة المنشأة الخاصة بصاحب الطلب.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
