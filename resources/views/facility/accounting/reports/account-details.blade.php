@extends('facility.layouts.app')

@section('title', 'تفاصيل الحساب')

@section('content')
<div class="container mx-auto px-4 my-10">
    <div class="flex justify-center">
        <div class="w-full max-w-7xl">
            <div class="bg-white rounded-lg shadow-lg">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 rounded-t-lg">
                    <div class="flex justify-between items-center">
                        <h4 class="text-lg font-semibold text-gray-800 mb-0">تفاصيل الحساب</h4>
                        <div class="flex space-x-2 rtl:space-x-reverse">
                            <a href="{{ route('facility.accounting.reports.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-colors">
                                <i class="fas fa-arrow-right"></i>
                                <span>العودة للتقارير</span>
                            </a>
                            <a href="{{ route('facility.accounting.reports.export-account-details', request()->query()) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-colors">
                                <i class="fas fa-download"></i>
                                <span>تصدير PDF</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <!-- معلومات التقرير -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="bg-white border border-gray-200 rounded-lg">
                            <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 rounded-t-lg">
                                <h6 class="text-lg font-semibold text-gray-800 mb-0">معلومات التقرير</h6>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <strong class="text-gray-700">الحساب:</strong><br>
                                        <span class="text-gray-600">{{ $account->account_name ?? 'غير محدد' }}</span>
                                    </div>
                                    <div>
                                        <strong class="text-gray-700">الفترة:</strong><br>
                                        <span class="text-gray-600">{{ $period->name ?? 'جميع الفترات' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white border border-gray-200 rounded-lg">
                            <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 rounded-t-lg">
                                <h6 class="text-lg font-semibold text-gray-800 mb-0">ملخص سريع</h6>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <strong class="text-gray-700">الرصيد الافتتاحي:</strong><br>
                                        <span class="text-blue-600 text-xl font-semibold">{{ $summary['opening_balance'] ?? '0.00' }} ر.س</span>
                                    </div>
                                    <div>
                                        <strong class="text-gray-700">الرصيد الختامي:</strong><br>
                                        <span class="text-{{ ($summary['closing_balance'] ?? 0) >= 0 ? 'green' : 'red' }}-600 text-xl font-semibold">
                                            {{ $summary['closing_balance'] ?? '0.00' }} ر.س
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- تفاصيل الحساب -->
                    <div class="bg-white border border-gray-200 rounded-lg">
                        <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 rounded-t-lg">
                            <h5 class="text-lg font-semibold text-gray-800 mb-0">تفاصيل الحساب</h5>
                        </div>
                        <div class="p-6">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">التاريخ</th>
                                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الوصف</th>
                                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">نوع القيد</th>
                                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المبلغ</th>
                                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الرصيد</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @if(isset($entries) && $entries->count() > 0)
                                            @foreach($entries as $entry)
                                                <tr class="hover:bg-gray-50">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $entry->entry_date ? $entry->entry_date->format('Y-m-d') : 'غير محدد' }}</td>
                                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $entry->description ?? '' }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-{{ $entry->entry_type === 'debit' ? 'blue' : 'green' }}-100 text-{{ $entry->entry_type === 'debit' ? 'blue' : 'green' }}-800">
                                                            {{ $entry->entry_type === 'debit' ? 'مدين' : 'دائن' }}
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="{{ $entry->entry_type === 'debit' ? 'text-blue-600' : 'text-green-600' }}">
                                                            {{ $entry->formatted_amount ?? '0.00 ر.س' }}
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="{{ ($entry->running_balance ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                                            {{ $entry->formatted_running_balance ?? '0.00 ر.س' }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">لا توجد حركات</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection