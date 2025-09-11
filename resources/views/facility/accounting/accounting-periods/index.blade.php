@extends('facility.layouts.app')

@section('title', 'الفترات المحاسبية')

@section('content')
<div class="container mx-auto px-4 my-10">
    <div class="flex justify-center">
        <div class="w-full max-w-7xl">
            <div class="bg-white rounded-lg shadow-lg">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 rounded-t-lg">
                    <div class="flex justify-between items-center">
                        <h4 class="text-lg font-semibold text-gray-800 mb-0">الفترات المحاسبية</h4>
                        <div class="flex space-x-2 rtl:space-x-reverse">
                            <a href="{{ route('facility.accounting.accounting-periods.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-colors">
                                <i class="fas fa-plus"></i>
                                <span>إضافة فترة جديدة</span>
                            </a>
                            <a href="{{ route('facility.accounting.accounting-periods.create-year') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-colors">
                                <i class="fas fa-calendar-plus"></i>
                                <span>إنشاء سنة مالية</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <!-- فلترة وبحث -->
                    <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
                        <div>
                            <input type="text" name="search" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="البحث في الفترات..." value="{{ request('search') }}">
                        </div>
                        <div>
                            <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">جميع الحالات</option>
                                <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>مفتوحة</option>
                                <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>مغلقة</option>
                                <option value="locked" {{ request('status') == 'locked' ? 'selected' : '' }}>مقفلة</option>
                            </select>
                        </div>
                        <div>
                            <input type="date" name="start_date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="من تاريخ" value="{{ request('start_date') }}">
                        </div>
                        <div>
                            <input type="date" name="end_date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="إلى تاريخ" value="{{ request('end_date') }}">
                        </div>
                        <div class="flex space-x-2 rtl:space-x-reverse">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-colors">
                                <i class="fas fa-search"></i>
                                <span>بحث</span>
                            </button>
                            <a href="{{ route('facility.accounting.accounting-periods.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-colors">
                                <i class="fas fa-times"></i>
                                <span>مسح</span>
                            </a>
                        </div>
                    </form>

                    @if($periods->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">اسم الفترة</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تاريخ البداية</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تاريخ النهاية</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المدة (أيام)</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">عدد القيود</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">إجمالي المبالغ</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($periods as $period)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">{{ $period->name }}</div>
                                                    @if($period->is_current)
                                                        <div class="text-sm text-green-600">الفترة الحالية</div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $period->start_date->format('Y-m-d') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $period->end_date->format('Y-m-d') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">{{ $period->duration_days }} يوم</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($period->status === 'open')
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">مفتوحة</span>
                                                @elseif($period->status === 'closed')
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">مغلقة</span>
                                                @else
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">مقفلة</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600">{{ $period->entries_count ?? 0 }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium {{ ($period->total_amount ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                                    {{ $period->formatted_total_amount ?? '0.00 ر.س' }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2 rtl:space-x-reverse">
                                                    <a href="{{ route('facility.accounting.accounting-periods.show', $period) }}" class="text-blue-600 hover:text-blue-900 p-1 rounded">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('facility.accounting.accounting-periods.edit', $period) }}" class="text-yellow-600 hover:text-yellow-900 p-1 rounded">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    @if($period->status === 'open')
                                                        <a href="{{ route('facility.accounting.accounting-periods.close', $period) }}" 
                                                           class="text-orange-600 hover:text-orange-900 p-1 rounded" 
                                                           onclick="return confirm('هل أنت متأكد من إغلاق هذه الفترة؟')">
                                                            <i class="fas fa-lock"></i>
                                                        </a>
                                                    @elseif($period->status === 'closed')
                                                        <a href="{{ route('facility.accounting.accounting-periods.lock', $period) }}" 
                                                           class="text-red-600 hover:text-red-900 p-1 rounded" 
                                                           onclick="return confirm('هل أنت متأكد من قفل هذه الفترة نهائياً؟')">
                                                            <i class="fas fa-lock"></i>
                                                        </a>
                                                    @endif
                                                    @if($period->canBeDeleted())
                                                        <form method="POST" action="{{ route('facility.accounting.accounting-periods.destroy', $period) }}" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذه الفترة؟')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-600 hover:text-red-900 p-1 rounded">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="flex justify-center mt-6">
                            {{ $periods->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-calendar-alt text-6xl text-gray-400 mb-4"></i>
                            <h5 class="text-lg font-medium text-gray-900 mb-2">لا توجد فترات محاسبية</h5>
                            <p class="text-gray-500 mb-6">ابدأ بإنشاء فترة محاسبية جديدة</p>
                            <div class="flex justify-center space-x-4 rtl:space-x-reverse">
                                <a href="{{ route('facility.accounting.accounting-periods.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg inline-flex items-center space-x-2 rtl:space-x-reverse transition-colors">
                                    <i class="fas fa-plus"></i>
                                    <span>إضافة فترة جديدة</span>
                                </a>
                                <a href="{{ route('facility.accounting.accounting-periods.create-year') }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg inline-flex items-center space-x-2 rtl:space-x-reverse transition-colors">
                                    <i class="fas fa-calendar-plus"></i>
                                    <span>إنشاء سنة مالية</span>
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-submit form on filter change
    document.querySelectorAll('select[name="status"]').forEach(select => {
        select.addEventListener('change', function() {
            this.form.submit();
        });
    });
</script>
@endpush

