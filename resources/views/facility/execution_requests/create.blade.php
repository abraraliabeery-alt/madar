@extends('facility.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800 mb-1">طلب تنفيذ جديد</h1>
            <p class="text-sm text-gray-500">أنشئ طلباً عاماً لاستقبال عروض من منفِّذين (مقاولات، صيانة، تصميم، وغيرها) مع دعم كامل لتعدد اللغات.</p>
        </div>
    </div>

    <div class="bg-white shadow-sm rounded-xl p-6 max-w-5xl">
        <form method="POST" action="{{ route('facility.execution-requests.store') }}" class="space-y-6">
            @csrf

            {{-- تبويبات اللغات للعنوان والوصف --}}
            <div>
                <div class="border-b border-gray-200 mb-4 flex flex-wrap gap-2">
                    @foreach($locales as $code => $locale)
                        <button type="button" data-locale-tab="{{ $code }}" class="locale-tab px-3 py-1.5 text-xs rounded-t-md border-b-2 transition-all
                            {{ $loop->first ? 'border-indigo-500 text-indigo-600 bg-indigo-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">
                            {{ $locale['native'] ?? strtoupper($code) }}
                        </button>
                    @endforeach
                </div>

                @foreach($locales as $code => $locale)
                    <div data-locale-panel="{{ $code }}" class="locale-panel {{ $loop->first ? '' : 'hidden' }}">
                        <input type="hidden" name="translations[{{ $loop->index }}][locale]" value="{{ $code }}">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                العنوان ({{ $locale['native'] ?? strtoupper($code) }})
                            </label>
                            <input type="text" name="translations[{{ $loop->index }}][title]" value="{{ old('translations.'.$loop->index.'.title') }}" class="w-full border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-sm" {{ $loop->first ? 'required' : '' }}>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                الوصف ({{ $locale['native'] ?? strtoupper($code) }})
                            </label>
                            <textarea name="translations[{{ $loop->index }}][description]" rows="4" class="w-full border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-sm" placeholder="وصف مختصر لما يجب على المنفِّذ تنفيذه في هذه اللغة">{{ old('translations.'.$loop->index.'.description') }}</textarea>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">النوع (اختياري)</label>
                    <input type="text" name="type" value="{{ old('type') }}" class="w-full border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-sm" placeholder="مثال: مقاولات، صيانة، تصميم">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">الأولوية</label>
                    <select name="priority" class="w-full border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                        <option value="normal" {{ old('priority','normal') === 'normal' ? 'selected' : '' }}>عادية</option>
                        <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>مرتفعة</option>
                        <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>منخفضة</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">تاريخ مستهدف (اختياري)</label>
                    <input type="date" name="due_date" value="{{ old('due_date') }}" class="w-full border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">الميزانية الدنيا (اختياري)</label>
                    <input type="number" step="0.01" name="budget_min" value="{{ old('budget_min') }}" class="w-full border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">الميزانية القصوى (اختياري)</label>
                    <input type="number" step="0.01" name="budget_max" value="{{ old('budget_max') }}" class="w-full border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                </div>
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit" class="inline-flex items-center px-5 py-2.5 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700">
                    <i class="fas fa-save ml-2"></i>
                    حفظ الطلب
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tabs = document.querySelectorAll('[data-locale-tab]');
        const panels = document.querySelectorAll('[data-locale-panel]');

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                const code = tab.getAttribute('data-locale-tab');

                tabs.forEach(t => t.classList.remove('border-indigo-500', 'text-indigo-600', 'bg-indigo-50'));
                tabs.forEach(t => t.classList.add('border-transparent', 'text-gray-500'));

                tab.classList.add('border-indigo-500', 'text-indigo-600', 'bg-indigo-50');
                tab.classList.remove('border-transparent', 'text-gray-500');

                panels.forEach(panel => {
                    panel.classList.toggle('hidden', panel.getAttribute('data-locale-panel') !== code);
                });
            });
        });
    });
</script>
@endpush
@endsection
