@extends('layouts.app')

@section('title', 'دفع فاتورة')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-900">دفع فاتورة - {{ $contract->contract_number }}</h1>
                <a href="{{ route('client.contracts.show', $contract) }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                    <i class="fas fa-arrow-right ml-2"></i>
                    العودة للعقد
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- معلومات العقد -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">معلومات العقد</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center py-2 border-b border-gray-200">
                            <span class="font-medium text-gray-700">رقم العقد:</span>
                            <span class="text-gray-900">{{ $contract->contract_number ?? 'غير محدد' }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-200">
                            <span class="font-medium text-gray-700">المنتج:</span>
                            <span class="text-gray-900 text-right">{{ $contract->product->getTranslatedTitle() }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-200">
                            <span class="font-medium text-gray-700">النوع:</span>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                {{ $contract->contract_type == 'sale' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ $contract->contract_type == 'sale' ? 'بيع' : 'إيجار' }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-200">
                            <span class="font-medium text-gray-700">المبلغ الإجمالي:</span>
                            <div class="flex items-center text-gray-900">
                                {{ number_format($contract->total_amount, 2) }}
                                <img src="{{ asset('Saudi_Riyal_Symbol.svg') }}" alt="SAR" class="w-4 h-4 mr-1">
                            </div>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-200">
                            <span class="font-medium text-gray-700">المدفوع:</span>
                            <div class="flex items-center text-green-600">
                                {{ number_format($contract->getTotalPaidAmount(), 2) }}
                                <img src="{{ asset('Saudi_Riyal_Symbol.svg') }}" alt="SAR" class="w-4 h-4 mr-1">
                            </div>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="font-medium text-gray-700">المتبقي:</span>
                            <div class="flex items-center text-yellow-600">
                                {{ number_format($contract->getRemainingAmount(), 2) }}
                                <img src="{{ asset('Saudi_Riyal_Symbol.svg') }}" alt="SAR" class="w-4 h-4 mr-1">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- الفواتير المتاحة للدفع -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">الفواتير المتاحة للدفع</h3>
                    
                    @if($contract->invoices->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">رقم الفاتورة</th>
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
                                    @foreach($contract->invoices as $invoice)
                                        @if($invoice->remaining_amount > 0)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{ $invoice->invoice_number }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    @switch($invoice->invoice_type)
                                                        @case('rent') فاتورة إيجار @break
                                                        @case('sale') فاتورة بيع @break
                                                        @case('deposit') فاتورة العربون @break
                                                        @case('commission') فاتورة العمولة @break
                                                        @case('refund') فاتورة استرداد @break
                                                    @endswitch
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center text-sm text-gray-900">
                                                        {{ number_format($invoice->amount, 2) }}
                                                        <img src="{{ asset('Saudi_Riyal_Symbol.svg') }}" alt="SAR" class="w-3 h-3 mr-1">
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center text-sm text-green-600">
                                                        {{ number_format($invoice->paid_amount, 2) }}
                                                        <img src="{{ asset('Saudi_Riyal_Symbol.svg') }}" alt="SAR" class="w-3 h-3 mr-1">
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center text-sm text-yellow-600">
                                                        {{ number_format($invoice->remaining_amount, 2) }}
                                                        <img src="{{ asset('Saudi_Riyal_Symbol.svg') }}" alt="SAR" class="w-3 h-3 mr-1">
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $invoice->due_date ? $invoice->due_date->format('Y-m-d') : 'غير محدد' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @switch($invoice->status)
                                                        @case('draft') <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">مسودة</span> @break
                                                        @case('sent') <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">مرسل</span> @break
                                                        @case('paid') <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">مدفوع</span> @break
                                                        @case('overdue') <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">متأخر</span> @break
                                                        @case('cancelled') <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">ملغي</span> @break
                                                    @endswitch
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <button class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors pay-invoice-btn" 
                                                            data-invoice-id="{{ $invoice->id }}"
                                                            data-amount="{{ $invoice->remaining_amount }}"
                                                            data-currency="SAR">
                                                        <i class="fas fa-credit-card ml-2"></i>
                                                        دفع
                                                    </button>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-file-invoice text-6xl text-gray-300 mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد فواتير</h3>
                            <p class="text-gray-500">لا توجد فواتير متاحة للدفع حالياً</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- سجل المدفوعات -->
        @if($contract->payments->count() > 0)
            <div class="mt-6">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">سجل المدفوعات</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">رقم المرجع</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">طريقة الدفع</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المبلغ</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تاريخ الدفع</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">ملاحظات</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($contract->payments as $payment)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $payment->reference_number ?? 'غير محدد' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            @switch($payment->payment_method)
                                                @case('cash') نقداً @break
                                                @case('bank_transfer') تحويل بنكي @break
                                                @case('credit_card') بطاقة ائتمان @break
                                                @case('check') شيك @break
                                                @case('online') عبر الإنترنت @break
                                            @endswitch
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center text-sm text-gray-900">
                                                {{ number_format($payment->amount, 2) }}
                                                <img src="{{ asset('Saudi_Riyal_Symbol.svg') }}" alt="SAR" class="w-3 h-3 mr-1">
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $payment->payment_date->format('Y-m-d') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @switch($payment->status)
                                                @case('pending') <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">معلق</span> @break
                                                @case('confirmed') <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">مؤكد</span> @break
                                                @case('failed') <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">فشل</span> @break
                                                @case('refunded') <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">مسترد</span> @break
                                            @endswitch
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $payment->notes ?? '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Modal الدفع -->
<div x-data="{ show: false }" x-show="show" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="show = false"></div>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fas fa-credit-card text-blue-600"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:mr-4 sm:text-right w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">دفع فاتورة</h3>
                        <form id="paymentForm">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="payment_method" class="block text-sm font-medium text-gray-700">طريقة الدفع <span class="text-red-500">*</span></label>
                                    <select name="payment_method" id="payment_method" required
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <option value="">اختر طريقة الدفع</option>
                                        <option value="cash">نقداً</option>
                                        <option value="bank_transfer">تحويل بنكي</option>
                                        <option value="credit_card">بطاقة ائتمان</option>
                                        <option value="check">شيك</option>
                                        <option value="online">عبر الإنترنت</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="amount" class="block text-sm font-medium text-gray-700">المبلغ <span class="text-red-500">*</span></label>
                                    <input type="number" name="amount" id="amount" step="0.01" min="0" required
                                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>
                                <div>
                                    <label for="payment_date" class="block text-sm font-medium text-gray-700">تاريخ الدفع <span class="text-red-500">*</span></label>
                                    <input type="date" name="payment_date" id="payment_date" required
                                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>
                                <div>
                                    <label for="reference_number" class="block text-sm font-medium text-gray-700">رقم المرجع</label>
                                    <input type="text" name="reference_number" id="reference_number"
                                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>
                                <div>
                                    <label for="bank_name" class="block text-sm font-medium text-gray-700">اسم البنك</label>
                                    <input type="text" name="bank_name" id="bank_name"
                                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>
                                <div>
                                    <label for="check_number" class="block text-sm font-medium text-gray-700">رقم الشيك</label>
                                    <input type="text" name="check_number" id="check_number"
                                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>
                            </div>
                            <div class="mt-4">
                                <label for="notes" class="block text-sm font-medium text-gray-700">ملاحظات</label>
                                <textarea name="notes" id="notes" rows="3"
                                          class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" @click="show = false; submitPayment()" 
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                    تسجيل الدفعة
                </button>
                <button type="button" @click="show = false" 
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    إلغاء
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let currentInvoiceId = null;

    // فتح modal الدفع
    document.querySelectorAll('.pay-invoice-btn').forEach(button => {
        button.addEventListener('click', function() {
            currentInvoiceId = this.dataset.invoiceId;
            const amount = this.dataset.amount;
            
            document.getElementById('amount').value = amount;
            document.getElementById('amount').max = amount;
            
            // Show modal using Alpine.js
            const modal = document.querySelector('[x-data*="show: false"]');
            if (modal) {
                modal._x_dataStack[0].show = true;
            }
        });
    });

    function submitPayment() {
        if (!currentInvoiceId) {
            alert('خطأ: لم يتم تحديد الفاتورة');
            return;
        }

        const formData = new FormData(document.getElementById('paymentForm'));
        formData.append('invoice_id', currentInvoiceId);
        formData.append('currency', 'SAR');

        fetch(`/client/contracts/{{ $contract->id }}/pay-invoice`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('تم تسجيل الدفعة بنجاح');
                location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ أثناء تسجيل الدفعة');
        });
    }

    // تعيين التاريخ الحالي كافتراضي
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('payment_date').value = today;
    });
</script>
@endpush