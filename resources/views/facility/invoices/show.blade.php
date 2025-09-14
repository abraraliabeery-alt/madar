@extends('facility.layouts.app')

@section('title', 'تفاصيل الفاتورة')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('facility.invoices.index') }}">الفواتير</a></li>
    <li class="breadcrumb-item active">تفاصيل الفاتورة</li>
@endsection

@section('content')
<div class="w-full px-4 my-6">
    <div class="bg-white rounded-lg shadow-lg">
        <div class="bg-gradient-to-r from-primary-500 to-primary-600 text-white px-6 py-4 rounded-t-lg">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-semibold">تفاصيل الفاتورة - {{ $invoice->invoice_number ?: 'INV-' . $invoice->id }}</h3>
                <div class="flex space-x-2 space-x-reverse">
                    <a href="{{ route('facility.invoices.edit', $invoice) }}" 
                       class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-edit mr-2"></i>
                        تعديل
                    </a>
                    <a href="{{ route('facility.invoices.index') }}" 
                       class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        العودة
                    </a>
                </div>
            </div>
        </div>

        <div class="p-6">
            <!-- معلومات الفاتورة -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <div>
                    <h5 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-file-invoice text-primary-600 mr-2"></i>
                        معلومات الفاتورة
                    </h5>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between">
                            <span class="text-gray-600">رقم الفاتورة:</span>
                            <span class="font-semibold">{{ $invoice->invoice_number ?: 'INV-' . $invoice->id }}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">نوع الفاتورة:</span>
                            <span class="font-semibold">
                                @switch($invoice->invoice_type)
                                    @case('rent') إيجار @break
                                    @case('sale') بيع @break
                                    @case('deposit') عربون @break
                                    @case('commission') عمولة @break
                                    @case('refund') استرداد @break
                                    @default {{ $invoice->invoice_type }}
                                @endswitch
                            </span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">المبلغ:</span>
                            <span class="font-semibold text-lg">{{ number_format($invoice->amount, 2) }} SAR</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">المبلغ المدفوع:</span>
                            <span class="font-semibold text-green-600">{{ number_format($invoice->paid_amount, 2) }} SAR</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">المبلغ المتبقي:</span>
                            <span class="font-semibold text-red-600">{{ number_format($invoice->remaining_amount, 2) }} SAR</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">تاريخ الاستحقاق:</span>
                            <span class="font-semibold">{{ $invoice->due_date ? $invoice->due_date->format('Y-m-d') : 'غير محدد' }}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">الحالة:</span>
                            <span class="px-3 py-1 rounded-full text-sm font-semibold
                                @if($invoice->status == 'paid') bg-green-100 text-green-800
                                @elseif($invoice->status == 'overdue') bg-red-100 text-red-800
                                @elseif($invoice->status == 'sent') bg-yellow-100 text-yellow-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                @switch($invoice->status)
                                    @case('draft') مسودة @break
                                    @case('sent') مرسلة @break
                                    @case('paid') مدفوعة @break
                                    @case('overdue') متأخرة @break
                                    @default {{ $invoice->status }}
                                @endswitch
                            </span>
                        </div>
                    </div>
                </div>

                <div>
                    <h5 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-handshake text-primary-600 mr-2"></i>
                        معلومات العقد
                    </h5>
                    
                    @if($invoice->contract)
                        <div class="space-y-4">
                            <div class="flex justify-between">
                                <span class="text-gray-600">رقم العقد:</span>
                                <span class="font-semibold">{{ $invoice->contract->contract_number ?: 'CON-' . $invoice->contract->id }}</span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="text-gray-600">المنتج:</span>
                                <span class="font-semibold">{{ $invoice->contract->product->getTranslatedTitle() }}</span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="text-gray-600">العميل:</span>
                                <span class="font-semibold">{{ $invoice->contract->user->name }}</span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="text-gray-600">إجمالي العقد:</span>
                                <span class="font-semibold">{{ number_format($invoice->contract->total_amount, 2) }} SAR</span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="text-gray-600">المبلغ المدفوع:</span>
                                <span class="font-semibold text-green-600">{{ number_format($invoice->contract->paid_amount, 2) }} SAR</span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="text-gray-600">المبلغ المتبقي:</span>
                                <span class="font-semibold text-red-600">{{ number_format($invoice->contract->remaining_amount, 2) }} SAR</span>
                            </div>
                        </div>
                    @else
                        <p class="text-gray-500">لا يوجد عقد مرتبط</p>
                    @endif
                </div>
            </div>

            <!-- تفاصيل إضافية -->
            @if($invoice->installment_number || $invoice->late_fee_amount || $invoice->discount_amount || $invoice->tax_rate)
                <div class="mb-8">
                    <h5 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-cog text-primary-600 mr-2"></i>
                        التفاصيل الإضافية
                    </h5>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        @if($invoice->installment_number)
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="text-sm text-gray-600">رقم القسط</div>
                                <div class="font-semibold">{{ $invoice->installment_number }}</div>
                            </div>
                        @endif
                        
                        @if($invoice->late_fee_amount)
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="text-sm text-gray-600">رسوم التأخير</div>
                                <div class="font-semibold text-red-600">{{ number_format($invoice->late_fee_amount, 2) }} SAR</div>
                            </div>
                        @endif
                        
                        @if($invoice->discount_amount)
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="text-sm text-gray-600">الخصم</div>
                                <div class="font-semibold text-green-600">{{ number_format($invoice->discount_amount, 2) }} SAR</div>
                            </div>
                        @endif
                        
                        @if($invoice->tax_rate)
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="text-sm text-gray-600">معدل الضريبة</div>
                                <div class="font-semibold">{{ $invoice->tax_rate }}%</div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- المدفوعات -->
            @if($invoice->payments && $invoice->payments->count() > 0)
                <div class="mb-8">
                    <h5 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-credit-card text-primary-600 mr-2"></i>
                        المدفوعات ({{ $invoice->payments->count() }})
                    </h5>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">رقم الدفعة</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المبلغ</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">طريقة الدفع</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">التاريخ</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($invoice->payments as $payment)
                                    <tr>
                                        <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                            {{ $payment->reference_number ?: 'PAY-' . $payment->id }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900">
                                            {{ number_format($payment->amount, 2) }} SAR
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900">
                                            @switch($payment->payment_method)
                                                @case('cash') نقداً @break
                                                @case('bank_transfer') تحويل بنكي @break
                                                @case('credit_card') بطاقة ائتمان @break
                                                @case('check') شيك @break
                                                @case('online') عبر الإنترنت @break
                                                @default {{ $payment->payment_method }}
                                            @endswitch
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900">
                                            {{ $payment->payment_date ? $payment->payment_date->format('Y-m-d') : 'غير محدد' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            <span class="px-2 py-1 text-xs rounded-full
                                                @if($payment->status == 'confirmed') bg-green-100 text-green-800
                                                @elseif($payment->status == 'pending') bg-yellow-100 text-yellow-800
                                                @elseif($payment->status == 'failed') bg-red-100 text-red-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                @switch($payment->status)
                                                    @case('pending') معلق @break
                                                    @case('confirmed') مؤكد @break
                                                    @case('failed') فشل @break
                                                    @case('refunded') مسترد @break
                                                    @default {{ $payment->status }}
                                                @endswitch
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- ملاحظات -->
            @if($invoice->notes || $invoice->payment_terms)
                <div class="mb-8">
                    <h5 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-sticky-note text-primary-600 mr-2"></i>
                        ملاحظات وشروط
                    </h5>
                    
                    <div class="space-y-4">
                        @if($invoice->payment_terms)
                            <div>
                                <h6 class="font-medium text-gray-700 mb-2">شروط الدفع:</h6>
                                <p class="text-gray-600 bg-gray-50 p-3 rounded-lg">{{ $invoice->payment_terms }}</p>
                            </div>
                        @endif
                        
                        @if($invoice->notes)
                            <div>
                                <h6 class="font-medium text-gray-700 mb-2">ملاحظات:</h6>
                                <p class="text-gray-600 bg-gray-50 p-3 rounded-lg">{{ $invoice->notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- أزرار التحكم -->
            <div class="flex justify-end space-x-4 space-x-reverse pt-6 border-t border-gray-200">
                <a href="{{ route('facility.invoices.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    العودة للقائمة
                </a>
                <a href="{{ route('facility.invoices.edit', $invoice) }}" 
                   class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-2 rounded-lg transition-colors">
                    <i class="fas fa-edit mr-2"></i>
                    تعديل الفاتورة
                </a>
            </div>
        </div>
    </div>
</div>
@endsection


