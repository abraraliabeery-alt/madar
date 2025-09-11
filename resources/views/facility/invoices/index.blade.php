@extends('facility.layouts.app')

@section('title', 'إدارة الفواتير')

@section('content')
<div class="container mx-auto px-4 my-10">
    <div class="flex justify-center">
        <div class="w-full max-w-7xl">
            <div class="bg-white rounded-lg shadow-lg">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 rounded-t-lg">
                    <div class="flex justify-between items-center">
                        <h4 class="text-lg font-semibold text-gray-800 mb-0">إدارة الفواتير</h4>
                        <div class="flex space-x-2 rtl:space-x-reverse">
                            <a href="{{ route('facility.invoices.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-colors">
                                <i class="fas fa-plus"></i>
                                <span>إضافة فاتورة جديدة</span>
                            </a>
                            <a href="{{ route('facility.invoices.generate') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-colors">
                                <i class="fas fa-magic"></i>
                                <span>إنشاء فواتير تلقائية</span>
                            </a>
                            <a href="{{ route('facility.invoices.statistics') }}" class="bg-cyan-600 hover:bg-cyan-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-colors">
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
                                <option value="rent" {{ request('type') == 'rent' ? 'selected' : '' }}>إيجار</option>
                                <option value="sale" {{ request('type') == 'sale' ? 'selected' : '' }}>بيع</option>
                                <option value="deposit" {{ request('type') == 'deposit' ? 'selected' : '' }}>عربون</option>
                                <option value="commission" {{ request('type') == 'commission' ? 'selected' : '' }}>عمولة</option>
                                <option value="refund" {{ request('type') == 'refund' ? 'selected' : '' }}>استرداد</option>
                            </select>
                        </div>
                        <div>
                            <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">جميع الحالات</option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>مسودة</option>
                                <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>مرسل</option>
                                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>مدفوع</option>
                                <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>متأخر</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                            </select>
                        </div>
                        <div>
                            <input type="text" name="search" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="البحث في الفواتير..." value="{{ request('search') }}">
                        </div>
                        <div>
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">بحث</button>
                        </div>
                    </form>

                    @if($invoices->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">رقم الفاتورة</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">العقد</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المنتج</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">النوع</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المبلغ</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المدفوع</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المتبقي</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تاريخ الاستحقاق</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($invoices as $invoice)
                                        <tr class="{{ $invoice->isOverdue() ? 'bg-red-50' : 'hover:bg-gray-50' }}">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $invoice->invoice_number ?: 'INV-' . $invoice->id }}
                                                </div>
                                                @if($invoice->installment_number)
                                                    <div class="text-sm text-gray-500">قسط رقم {{ $invoice->installment_number }}</div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <a href="{{ route('facility.contracts.show', $invoice->contract) }}" class="text-sm text-blue-600 hover:text-blue-900">
                                                    {{ $invoice->contract->contract_number ?: 'CON-' . $invoice->contract->id }}
                                                </a>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <a href="{{ route('facility.products.show', $invoice->contract->product) }}" class="text-sm text-blue-600 hover:text-blue-900">
                                                    {{ $invoice->contract->product->getTranslatedTitle() }}
                                                </a>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                                    {{ $invoice->invoice_type == 'sale' ? 'bg-green-100 text-green-800' : 
                                                       ($invoice->invoice_type == 'rent' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800') }}">
                                                    @switch($invoice->invoice_type)
                                                        @case('rent') إيجار @break
                                                        @case('sale') بيع @break
                                                        @case('deposit') عربون @break
                                                        @case('commission') عمولة @break
                                                        @case('refund') استرداد @break
                                                    @endswitch
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ number_format($invoice->amount, 2) }} {{ $invoice->currency }}
                                                </div>
                                                @if($invoice->tax_amount > 0)
                                                    <div class="text-sm text-gray-500">ضريبة: {{ number_format($invoice->tax_amount, 2) }}</div>
                                                @endif
                                                @if($invoice->late_fee_amount > 0)
                                                    <div class="text-sm text-red-600">رسوم تأخير: {{ number_format($invoice->late_fee_amount, 2) }}</div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600">
                                                {{ number_format($invoice->paid_amount, 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm {{ $invoice->remaining_amount > 0 ? 'text-red-600' : 'text-green-600' }}">
                                                {{ number_format($invoice->remaining_amount, 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm {{ $invoice->isOverdue() ? 'text-red-600' : 'text-gray-900' }}">
                                                    {{ $invoice->due_date->format('Y-m-d') }}
                                                </div>
                                                @if($invoice->isOverdue())
                                                    <div class="text-sm text-red-600">{{ $invoice->getDaysUntilDue() }} يوم متأخر</div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @switch($invoice->status)
                                                    @case('draft')
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">مسودة</span>
                                                        @break
                                                    @case('sent')
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">مرسل</span>
                                                        @break
                                                    @case('paid')
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">مدفوع</span>
                                                        @break
                                                    @case('overdue')
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">متأخر</span>
                                                        @break
                                                    @case('cancelled')
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">ملغي</span>
                                                        @break
                                                @endswitch
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2 rtl:space-x-reverse">
                                                    <a href="{{ route('facility.invoices.show', $invoice) }}" class="text-blue-600 hover:text-blue-900 p-1 rounded">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('facility.invoices.edit', $invoice) }}" class="text-yellow-600 hover:text-yellow-900 p-1 rounded">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    @if($invoice->needsReminder())
                                                        <form method="POST" action="{{ route('facility.invoices.reminder', $invoice) }}" class="inline">
                                                            @csrf
                                                            <button type="submit" class="text-orange-600 hover:text-orange-900 p-1 rounded" title="إرسال تذكير">
                                                                <i class="fas fa-bell"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                    <form method="POST" action="{{ route('facility.invoices.destroy', $invoice) }}" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذه الفاتورة؟')">
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
                            {{ $invoices->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-file-invoice text-6xl text-gray-400 mb-4"></i>
                            <h5 class="text-lg font-medium text-gray-900 mb-2">لا توجد فواتير</h5>
                            <p class="text-gray-500 mb-6">ابدأ بإنشاء فاتورة جديدة</p>
                            <a href="{{ route('facility.invoices.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg inline-flex items-center space-x-2 rtl:space-x-reverse transition-colors">
                                <i class="fas fa-plus"></i>
                                <span>إضافة فاتورة جديدة</span>
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
