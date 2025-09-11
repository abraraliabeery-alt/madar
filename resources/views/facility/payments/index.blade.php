@extends('facility.layouts.app')

@section('title', 'إدارة المدفوعات')

@section('content')
<div class="container mx-auto px-4 my-10">
    <div class="flex justify-center">
        <div class="w-full max-w-7xl">
            <div class="bg-white rounded-lg shadow-lg">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 rounded-t-lg">
                    <div class="flex justify-between items-center">
                        <h4 class="text-lg font-semibold text-gray-800 mb-0">إدارة المدفوعات</h4>
                        <div class="flex space-x-2 rtl:space-x-reverse">
                            <a href="{{ route('facility.payments.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-colors">
                                <i class="fas fa-plus"></i>
                                <span>إضافة دفعة جديدة</span>
                            </a>
                            <a href="{{ route('facility.payments.statistics') }}" class="bg-cyan-600 hover:bg-cyan-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-colors">
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
                            <select name="method" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">جميع الطرق</option>
                                <option value="cash" {{ request('method') == 'cash' ? 'selected' : '' }}>نقداً</option>
                                <option value="bank_transfer" {{ request('method') == 'bank_transfer' ? 'selected' : '' }}>تحويل بنكي</option>
                                <option value="credit_card" {{ request('method') == 'credit_card' ? 'selected' : '' }}>بطاقة ائتمان</option>
                                <option value="check" {{ request('method') == 'check' ? 'selected' : '' }}>شيك</option>
                                <option value="online" {{ request('method') == 'online' ? 'selected' : '' }}>دفع إلكتروني</option>
                            </select>
                        </div>
                        <div>
                            <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">جميع الحالات</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>معلق</option>
                                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>مؤكد</option>
                                <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>فشل</option>
                                <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>مسترد</option>
                            </select>
                        </div>
                        <div>
                            <input type="text" name="search" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="البحث في المدفوعات..." value="{{ request('search') }}">
                        </div>
                        <div>
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">بحث</button>
                        </div>
                    </form>

                    @if($payments->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المرجع</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الفاتورة</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">العقد</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المنتج</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الطريقة</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المبلغ</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">التاريخ</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($payments as $payment)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $payment->reference_number ?: 'PAY-' . $payment->id }}
                                                </div>
                                                @if($payment->payment_reference)
                                                    <div class="text-sm text-gray-500">{{ $payment->payment_reference }}</div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($payment->invoice)
                                                    <a href="{{ route('facility.invoices.show', $payment->invoice) }}" class="text-sm text-blue-600 hover:text-blue-900">
                                                        {{ $payment->invoice->invoice_number ?: 'INV-' . $payment->invoice->id }}
                                                    </a>
                                                @else
                                                    <span class="text-sm text-gray-500">-</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($payment->contract)
                                                    <a href="{{ route('facility.contracts.show', $payment->contract) }}" class="text-sm text-blue-600 hover:text-blue-900">
                                                        {{ $payment->contract->contract_number ?: 'CON-' . $payment->contract->id }}
                                                    </a>
                                                @else
                                                    <span class="text-sm text-gray-500">-</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($payment->contract && $payment->contract->product)
                                                    <a href="{{ route('facility.products.show', $payment->contract->product) }}" class="text-sm text-blue-600 hover:text-blue-900">
                                                        {{ $payment->contract->product->getTranslatedTitle() }}
                                                    </a>
                                                @else
                                                    <span class="text-sm text-gray-500">-</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                    {{ $payment->getPaymentMethodDisplayName() }}
                                                </span>
                                                @if($payment->bank_name)
                                                    <div class="text-sm text-gray-500 mt-1">{{ $payment->bank_name }}</div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ number_format($payment->amount, 2) }} {{ $payment->currency }}
                                                </div>
                                                @if($payment->processing_fee > 0)
                                                    <div class="text-sm text-gray-500">رسوم: {{ number_format($payment->processing_fee, 2) }}</div>
                                                @endif
                                                @if($payment->installment_number)
                                                    <div class="text-sm text-blue-600">قسط رقم {{ $payment->installment_number }}</div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $payment->payment_date->format('Y-m-d') }}</div>
                                                <div class="text-sm text-gray-500">{{ $payment->created_at->format('H:i') }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @switch($payment->status)
                                                    @case('pending')
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">معلق</span>
                                                        @break
                                                    @case('confirmed')
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">مؤكد</span>
                                                        @break
                                                    @case('failed')
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">فشل</span>
                                                        @break
                                                    @case('refunded')
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">مسترد</span>
                                                        @break
                                                @endswitch
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2 rtl:space-x-reverse">
                                                    <a href="{{ route('facility.payments.show', $payment) }}" class="text-blue-600 hover:text-blue-900 p-1 rounded">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('facility.payments.edit', $payment) }}" class="text-yellow-600 hover:text-yellow-900 p-1 rounded">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    @if($payment->status == 'pending')
                                                        <form method="POST" action="{{ route('facility.payments.confirm', $payment) }}" class="inline">
                                                            @csrf
                                                            <button type="submit" class="text-green-600 hover:text-green-900 p-1 rounded" title="تأكيد الدفعة">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        </form>
                                                        <form method="POST" action="{{ route('facility.payments.fail', $payment) }}" class="inline">
                                                            @csrf
                                                            <button type="submit" class="text-red-600 hover:text-red-900 p-1 rounded" title="إلغاء الدفعة">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                    @if($payment->status == 'confirmed')
                                                        <form method="POST" action="{{ route('facility.payments.refund', $payment) }}" class="inline" onsubmit="return confirm('هل أنت متأكد من استرداد هذه الدفعة؟')">
                                                            @csrf
                                                            <button type="submit" class="text-orange-600 hover:text-orange-900 p-1 rounded" title="استرداد الدفعة">
                                                                <i class="fas fa-undo"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                    <form method="POST" action="{{ route('facility.payments.destroy', $payment) }}" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذه الدفعة؟')">
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
                            {{ $payments->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-credit-card text-6xl text-gray-400 mb-4"></i>
                            <h5 class="text-lg font-medium text-gray-900 mb-2">لا توجد مدفوعات</h5>
                            <p class="text-gray-500 mb-6">ابدأ بإضافة دفعة جديدة</p>
                            <a href="{{ route('facility.payments.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg inline-flex items-center space-x-2 rtl:space-x-reverse transition-colors">
                                <i class="fas fa-plus"></i>
                                <span>إضافة دفعة جديدة</span>
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
    document.querySelectorAll('select[name="method"], select[name="status"]').forEach(select => {
        select.addEventListener('change', function() {
            this.form.submit();
        });
    });
</script>
@endpush
