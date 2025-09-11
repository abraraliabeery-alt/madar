@extends('facility.layouts.app')

@section('title', 'إدارة العقود')

@section('content')
<div class="container mx-auto px-4 my-10">
    <div class="flex justify-center">
        <div class="w-full max-w-7xl">
            <div class="bg-white rounded-lg shadow-lg">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 rounded-t-lg">
                    <div class="flex justify-between items-center">
                        <h4 class="text-lg font-semibold text-gray-800 mb-0">إدارة العقود</h4>
                        <div class="flex space-x-2 rtl:space-x-reverse">
                            <a href="{{ route('facility.contracts.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-colors">
                                <i class="fas fa-plus"></i>
                                <span>إضافة عقد جديد</span>
                            </a>
                            <a href="{{ route('facility.contracts.statistics') }}" class="bg-cyan-600 hover:bg-cyan-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-colors">
                                <i class="fas fa-chart-bar"></i>
                                <span>الإحصائيات</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- فلترة وبحث -->
                <div class="p-6">
                    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                        <div>
                            <select name="type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">جميع الأنواع</option>
                                <option value="sale" {{ request('type') == 'sale' ? 'selected' : '' }}>بيع</option>
                                <option value="rent" {{ request('type') == 'rent' ? 'selected' : '' }}>إيجار</option>
                            </select>
                        </div>
                        <div>
                            <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">جميع الحالات</option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>مسودة</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>مكتمل</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                            </select>
                        </div>
                        <div>
                            <input type="text" name="search" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="البحث في العقود..." value="{{ request('search') }}">
                        </div>
                        <div>
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">بحث</button>
                        </div>
                    </form>

                    @if($contracts->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">رقم العقد</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المنتج</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">العميل</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">النوع</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المبلغ الإجمالي</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المدة</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">التقدم</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($contracts as $contract)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $contract->contract_number ?: 'CON-' . $contract->id }}
                                                </div>
                                                @if($contract->is_verified)
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">موثق</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <a href="{{ route('facility.products.show', $contract->product) }}" class="text-sm text-blue-600 hover:text-blue-900">
                                                    {{ $contract->product->getTranslatedTitle() }}
                                                </a>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $contract->user->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $contract->user->email }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $contract->contract_type == 'sale' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                                    {{ $contract->contract_type == 'sale' ? 'بيع' : 'إيجار' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ number_format($contract->total_amount, 2) }} {{ $contract->currency }}
                                                </div>
                                                @if($contract->deposit_amount)
                                                    <div class="text-sm text-gray-500">عربون: {{ number_format($contract->deposit_amount, 2) }}</div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                @if($contract->contract_duration_months)
                                                    {{ $contract->contract_duration_months }} شهر
                                                @elseif($contract->end_date)
                                                    {{ $contract->start_date->diffInMonths($contract->end_date) }} شهر
                                                @else
                                                    غير محدد
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="w-24 bg-gray-200 rounded-full h-2 mr-2">
                                                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $contract->getProgressPercentage() }}%"></div>
                                                    </div>
                                                    <span class="text-sm text-gray-600">{{ round($contract->getProgressPercentage()) }}%</span>
                                                </div>
                                                <div class="text-sm text-gray-500 mt-1">
                                                    {{ $contract->paid_installments }}/{{ $contract->total_installments }} قسط
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @switch($contract->status)
                                                    @case('draft')
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">مسودة</span>
                                                        @break
                                                    @case('active')
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">نشط</span>
                                                        @break
                                                    @case('completed')
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">مكتمل</span>
                                                        @break
                                                    @case('cancelled')
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">ملغي</span>
                                                        @break
                                                @endswitch
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2 rtl:space-x-reverse">
                                                    <a href="{{ route('facility.contracts.show', $contract) }}" class="text-blue-600 hover:text-blue-900 p-1 rounded">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('facility.contracts.edit', $contract) }}" class="text-yellow-600 hover:text-yellow-900 p-1 rounded">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    @if($contract->status == 'active' && $contract->canBeRenewed())
                                                        <form method="POST" action="{{ route('facility.contracts.renew', $contract) }}" class="inline">
                                                            @csrf
                                                            <button type="submit" class="text-green-600 hover:text-green-900 p-1 rounded" title="تجديد العقد">
                                                                <i class="fas fa-redo"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                    <form method="POST" action="{{ route('facility.contracts.destroy', $contract) }}" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا العقد؟')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900 p-1 rounded">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="flex justify-center mt-6">
                            {{ $contracts->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-file-contract text-6xl text-gray-400 mb-4"></i>
                            <h5 class="text-lg font-medium text-gray-900 mb-2">لا توجد عقود</h5>
                            <p class="text-gray-500 mb-6">ابدأ بإنشاء عقد جديد</p>
                            <a href="{{ route('facility.contracts.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg inline-flex items-center space-x-2 rtl:space-x-reverse transition-colors">
                                <i class="fas fa-plus"></i>
                                <span>إضافة عقد جديد</span>
                            </a>
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
    document.querySelectorAll('select[name="type"], select[name="status"]').forEach(select => {
        select.addEventListener('change', function() {
            this.form.submit();
        });
    });
</script>
@endpush