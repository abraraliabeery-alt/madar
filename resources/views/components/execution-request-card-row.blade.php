@props([
    'request',
])

@php
    $translation = $request->translations->firstWhere('locale', app()->getLocale());
    $title = $translation->title ?? ('طلب #' . $request->id);

    $publishDate = $request->created_at ? $request->created_at->format('d-m-Y') : null;

    $deadlineDate = $request->due_date;
    if (!empty($deadlineDate) && !($deadlineDate instanceof \Carbon\CarbonInterface)) {
        try {
            $deadlineDate = \Carbon\Carbon::parse($deadlineDate);
        } catch (\Throwable $e) {
            $deadlineDate = null;
        }
    }

    $daysRemaining = null;
    if (!empty($deadlineDate)) {
        try {
            $daysRemaining = now()->startOfDay()->diffInDays($deadlineDate->startOfDay(), false);
            if (!is_numeric($daysRemaining) || abs((int) $daysRemaining) > 999) {
                $daysRemaining = null;
            } else {
                $daysRemaining = (int) $daysRemaining;
            }
        } catch (\Throwable $e) {
            $daysRemaining = null;
        }
    }

    $budgetRange = null;
    if (!is_null($request->budget_min) || !is_null($request->budget_max)) {
        $min = $request->budget_min ? number_format($request->budget_min) : null;
        $max = $request->budget_max ? number_format($request->budget_max) : null;
        $budgetRange = trim(($min ? $min : '') . ' - ' . ($max ? $max : ''));
        $budgetRange = trim($budgetRange, " -");
    }

    $address = null;
    if (is_array($request->data ?? null)) {
        $address = $request->data['address'] ?? null;
    }
@endphp

<div class="bg-white dark:bg-secondary-900 rounded-xl shadow-md overflow-hidden card-hover border border-gray-100 dark:border-secondary-800">
    <div class="flex flex-col md:flex-row">
        <div class="p-4 md:p-5 flex items-center justify-center md:w-52 flex-shrink-0 bg-white dark:bg-secondary-900 border-b md:border-b-0 md:border-r border-gray-100 dark:border-secondary-800">
            <div class="w-20 h-20 md:w-24 md:h-24 rounded-full border-4 border-primary-600 flex items-center justify-center text-center">
                <div>
                    <div class="text-2xl font-extrabold text-primary-700 dark:text-primary-300 leading-none tracking-tight tabular-nums">
                        <span class="block w-[6ch] overflow-hidden text-ellipsis whitespace-nowrap">
                            {{ is_null($daysRemaining) ? '--' : number_format($daysRemaining) }}
                        </span>
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-300">يوم متبقي</div>
                </div>
            </div>
        </div>

        <div class="flex-1 p-5">
            <div class="flex items-start justify-between gap-4">
                <h3 class="text-lg md:text-xl font-bold text-gray-900 dark:text-white leading-snug">
                    <a href="{{ route('public.execution.show', $request) }}" class="hover:text-primary-600 transition-colors">
                        {{ $title }}
                    </a>
                </h3>

                <div class="flex items-start gap-3">
                    <div class="w-16 h-16 rounded-lg overflow-hidden bg-gray-100 dark:bg-secondary-800 flex items-center justify-center">
                        <i class="fas fa-gavel text-gray-400 dark:text-gray-300 text-xl"></i>
                    </div>
                    <div class="w-16 h-16 rounded-lg bg-gray-50 dark:bg-secondary-800 border border-gray-200 dark:border-secondary-700 flex items-center justify-center">
                        <div class="text-[10px] text-gray-500 dark:text-gray-300 text-center leading-tight">
                            QR
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-2 text-sm text-gray-700 dark:text-gray-200">
                <div class="flex items-center gap-2 rtl:flex-row-reverse">
                    <i class="fas fa-calendar text-primary-700"></i>
                    <span class="text-gray-500 dark:text-gray-300">تاريخ النشر</span>
                    <span class="font-semibold">{{ $publishDate ?? '—' }}</span>
                </div>

                <div class="flex items-center gap-2 rtl:flex-row-reverse">
                    <i class="fas fa-gavel text-primary-700"></i>
                    <span class="text-gray-500 dark:text-gray-300">العروض</span>
                    <span class="font-semibold">{{ $request->bids_count ? number_format($request->bids_count) : '—' }}</span>
                </div>

                <div class="flex items-center gap-2 rtl:flex-row-reverse">
                    <i class="fas fa-briefcase text-primary-700"></i>
                    <span class="text-gray-500 dark:text-gray-300">النوع</span>
                    <span class="font-semibold">{{ $request->type ?? '—' }}</span>
                </div>

                <div class="flex items-center gap-2 rtl:flex-row-reverse">
                    <i class="fas fa-clock text-primary-700"></i>
                    <span class="text-gray-500 dark:text-gray-300">نهاية التقديم</span>
                    <span class="font-semibold">{{ $deadlineDate ? $deadlineDate->format('d-m-Y') : '—' }}</span>
                </div>

                <div class="flex items-center gap-2 rtl:flex-row-reverse md:col-span-2">
                    <i class="fas fa-map-marker-alt text-primary-700"></i>
                    <span class="text-gray-500 dark:text-gray-300">المكان</span>
                    <span class="font-semibold">{{ $address ?? '—' }}</span>
                </div>
            </div>

            <div class="mt-4 rounded-xl border border-gray-100 dark:border-secondary-800 bg-gray-50 dark:bg-secondary-800/60 p-3">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 text-xs text-gray-600 dark:text-gray-200">
                    <div class="flex items-center gap-2 rtl:flex-row-reverse">
                        <span class="w-2.5 h-2.5 rounded-full bg-primary-600"></span>
                        <span class="text-gray-500 dark:text-gray-300">نُشر</span>
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $publishDate ?? '—' }}</span>
                    </div>
                    <div class="flex items-center gap-2 rtl:flex-row-reverse">
                        <span class="w-2.5 h-2.5 rounded-full bg-secondary-600"></span>
                        <span class="text-gray-500 dark:text-gray-300">نهاية التقديم</span>
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $deadlineDate ? $deadlineDate->format('d-m-Y') : '—' }}</span>
                    </div>
                    <div class="flex items-center gap-2 rtl:flex-row-reverse">
                        <span class="w-2.5 h-2.5 rounded-full bg-gray-400"></span>
                        <span class="text-gray-500 dark:text-gray-300">الميزانية</span>
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $budgetRange ? ($budgetRange . ' ريال') : '—' }}</span>
                    </div>
                </div>
            </div>

            <div class="mt-4 flex items-center justify-between">
                <a href="{{ route('public.execution.show', $request) }}"
                   class="text-primary-600 hover:text-primary-700 text-sm font-semibold">
                    عرض التفاصيل
                </a>
            </div>
        </div>
    </div>
</div>
