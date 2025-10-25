@extends('facility.layouts.app')

@section('title', 'معدلات الضرائب')

@section('content')
<div class="container mx-auto px-4 my-10">
    <div class="flex justify-center">
        <div class="w-full max-w-7xl">
            <div class="bg-white rounded-lg shadow-lg">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 rounded-t-lg">
                    <div class="flex justify-between items-center">
                        <h4 class="text-lg font-semibold text-gray-800 mb-0">معدلات الضرائب</h4>
                        <div class="flex space-x-2 rtl:space-x-reverse">
                            <a href="{{ route('facility.accounting.tax-rates.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-colors">
                                <i class="fas fa-plus"></i>
                                <span>إضافة معدل جديد</span>
                            </a>
                            <a href="{{ route('facility.accounting.tax-rates.create-default') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-colors">
                                <i class="fas fa-magic"></i>
                                <span>إنشاء معدلات افتراضية</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <!-- فلترة وبحث -->
                    <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
                        <div>
                            <input type="text" name="search" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="البحث في معدلات الضرائب..." value="{{ request('search') }}">
                        </div>
                        <div>
                            <select name="is_active" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">جميع الحالات</option>
                                <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>نشط</option>
                                <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>غير نشط</option>
                            </select>
                        </div>
                        <div>
                            <input type="number" step="0.01" name="min_rate" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="أقل معدل" value="{{ request('min_rate') }}">
                        </div>
                        <div>
                            <input type="number" step="0.01" name="max_rate" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="أعلى معدل" value="{{ request('max_rate') }}">
                        </div>
                        <div class="flex space-x-2 rtl:space-x-reverse">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-colors">
                                <i class="fas fa-search"></i>
                                <span>بحث</span>
                            </button>
                            <a href="{{ route('facility.accounting.tax-rates.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-colors">
                                <i class="fas fa-times"></i>
                                <span>مسح</span>
                            </a>
                        </div>
                    </form>

                    @if($taxRates->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">اسم المعدل</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المعدل</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">النسبة المئوية</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الوصف</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">عدد الاستخدامات</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تاريخ الإنشاء</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($taxRates as $taxRate)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">{{ $taxRate->name }}</div>
                                                    @if($taxRate->is_default)
                                                        <div class="text-sm text-green-600">معدل افتراضي</div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">{{ $taxRate->rate }}</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="w-full bg-gray-200 rounded-full h-5">
                                                    <div class="h-5 rounded-full flex items-center justify-center text-xs font-medium text-white {{ $taxRate->rate > 15 ? 'bg-red-600' : ($taxRate->rate > 10 ? 'bg-yellow-600' : 'bg-green-600') }}" 
                                                         style="width: {{ min(100, ($taxRate->rate / 20) * 100) }}%">
                                                        {{ $taxRate->rate }}%
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-500">{{ $taxRate->description ?: 'لا يوجد وصف' }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($taxRate->is_active)
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">نشط</span>
                                                @else
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">غير نشط</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600">{{ $taxRate->usage_count ?? 0 }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $taxRate->created_at->format('Y-m-d') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2 rtl:space-x-reverse">
                                                    <a href="{{ route('facility.accounting.tax-rates.show', $taxRate) }}" class="text-blue-600 hover:text-blue-900 p-1 rounded">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('facility.accounting.tax-rates.edit', $taxRate) }}" class="text-yellow-600 hover:text-yellow-900 p-1 rounded">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    @if($taxRate->canBeDeleted())
                                                        <form method="POST" action="{{ route('facility.accounting.tax-rates.destroy', $taxRate) }}" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا المعدل؟')">
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
                            {{ $taxRates->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-percentage text-6xl text-gray-400 mb-4"></i>
                            <h5 class="text-lg font-medium text-gray-900 mb-2">لا توجد معدلات ضرائب</h5>
                            <p class="text-gray-500 mb-6">ابدأ بإنشاء معدلات الضرائب</p>
                            <div class="flex justify-center space-x-4 rtl:space-x-reverse">
                                <a href="{{ route('facility.accounting.tax-rates.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg inline-flex items-center space-x-2 rtl:space-x-reverse transition-colors">
                                    <i class="fas fa-plus"></i>
                                    <span>إضافة معدل جديد</span>
                                </a>
                                <a href="{{ route('facility.accounting.tax-rates.create-default') }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg inline-flex items-center space-x-2 rtl:space-x-reverse transition-colors">
                                    <i class="fas fa-magic"></i>
                                    <span>إنشاء معدلات افتراضية</span>
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
    document.querySelectorAll('select[name="is_active"]').forEach(select => {
        select.addEventListener('change', function() {
            this.form.submit();
        });
    });
</script>
@endpush

