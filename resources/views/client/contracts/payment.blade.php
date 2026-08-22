@extends('client.layouts.app')

@section('title', 'ط¯ظپط¹ ظپط§طھظˆط±ط©')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-900">ط¯ظپط¹ ظپط§طھظˆط±ط© - {{ $contract->contract_number }}</h1>
                <a href="{{ route('client.contracts.show', $contract) }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                    <i class="fas fa-arrow-right ml-2"></i>
                    ط§ظ„ط¹ظˆط¯ط© ظ„ظ„ط¹ظ‚ط¯
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- ظ…ط¹ظ„ظˆظ…ط§طھ ط§ظ„ط¹ظ‚ط¯ -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">ظ…ط¹ظ„ظˆظ…ط§طھ ط§ظ„ط¹ظ‚ط¯</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center py-2 border-b border-gray-200">
                            <span class="font-medium text-gray-700">ط±ظ‚ظ… ط§ظ„ط¹ظ‚ط¯:</span>
                            <span class="text-gray-900">{{ $contract->contract_number ?? 'ط؛ظٹط± ظ…ط­ط¯ط¯' }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-200">
                            <span class="font-medium text-gray-700">ط§ظ„ظ…ظ†طھط¬:</span>
                            <span class="text-gray-900 text-right">{{ $contract->product->getTranslatedTitle() }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-200">
                            <span class="font-medium text-gray-700">ط§ظ„ظ†ظˆط¹:</span>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                {{ $contract->contract_type == 'sale' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ $contract->contract_type == 'sale' ? 'ط¨ظٹط¹' : 'ط¥ظٹط¬ط§ط±' }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-200">
                            <span class="font-medium text-gray-700">ط§ظ„ظ…ط¨ظ„ط؛ ط§ظ„ط¥ط¬ظ…ط§ظ„ظٹ:</span>
                            <div class="flex items-center text-gray-900">
                                {{ number_format($contract->total_amount, 2) }}
                                <img src="{{ asset('Saudi_Riyal_Symbol.svg') }}" alt="SAR" class="w-4 h-4 mr-1">
                            </div>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-200">
                            <span class="font-medium text-gray-700">ط§ظ„ظ…ط¯ظپظˆط¹:</span>
                            <div class="flex items-center text-green-600">
                                {{ number_format($contract->getTotalPaidAmount(), 2) }}
                                <img src="{{ asset('Saudi_Riyal_Symbol.svg') }}" alt="SAR" class="w-4 h-4 mr-1">
                            </div>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="font-medium text-gray-700">ط§ظ„ظ…طھط¨ظ‚ظٹ:</span>
                            <div class="flex items-center text-yellow-600">
                                {{ number_format($contract->getRemainingAmount(), 2) }}
                                <img src="{{ asset('Saudi_Riyal_Symbol.svg') }}" alt="SAR" class="w-4 h-4 mr-1">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ط§ظ„ظپظˆط§طھظٹط± ط§ظ„ظ…طھط§ط­ط© ظ„ظ„ط¯ظپط¹ -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">ط§ظ„ظپظˆط§طھظٹط± ط§ظ„ظ…طھط§ط­ط© ظ„ظ„ط¯ظپط¹</h3>
                    
                    @if($contract->invoices->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">ط±ظ‚ظ… ط§ظ„ظپط§طھظˆط±ط©</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">ط§ظ„ظ†ظˆط¹</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">ط§ظ„ظ…ط¨ظ„ط؛</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">ط§ظ„ظ…ط¯ظپظˆط¹</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">ط§ظ„ظ…طھط¨ظ‚ظٹ</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">طھط§ط±ظٹط® ط§ظ„ط§ط³طھط­ظ‚ط§ظ‚</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">ط§ظ„ط­ط§ظ„ط©</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">ط§ظ„ط¥ط¬ط±ط§ط،ط§طھ</th>
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
                                                        @case('rent') ظپط§طھظˆط±ط© ط¥ظٹط¬ط§ط± @break
                                                        @case('sale') ظپط§طھظˆط±ط© ط¨ظٹط¹ @break
                                                        @case('deposit') ظپط§طھظˆط±ط© ط§ظ„ط¹ط±ط¨ظˆظ† @break
                                                        @case('commission') ظپط§طھظˆط±ط© ط§ظ„ط¹ظ…ظˆظ„ط© @break
                                                        @case('refund') ظپط§طھظˆط±ط© ط§ط³طھط±ط¯ط§ط¯ @break
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
                                                    {{ $invoice->due_date ? $invoice->due_date->format('Y-m-d') : 'ط؛ظٹط± ظ…ط­ط¯ط¯' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @switch($invoice->status)
                                                        @case('draft') <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">ظ…ط³ظˆط¯ط©</span> @break
                                                        @case('sent') <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">ظ…ط±ط³ظ„</span> @break
                                                        @case('paid') <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">ظ…ط¯ظپظˆط¹</span> @break
                                                        @case('overdue') <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">ظ…طھط£ط®ط±</span> @break
                                                        @case('cancelled') <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">ظ…ظ„ط؛ظٹ</span> @break
                                                    @endswitch
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <button class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors pay-invoice-btn" 
                                                            data-invoice-id="{{ $invoice->id }}"
                                                            data-amount="{{ $invoice->remaining_amount }}"
                                                            data-currency="SAR">
                                                        <i class="fas fa-credit-card ml-2"></i>
                                                        ط¯ظپط¹
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
                            <h3 class="text-lg font-medium text-gray-900 mb-2">ظ„ط§ طھظˆط¬ط¯ ظپظˆط§طھظٹط±</h3>
                            <p class="text-gray-500">ظ„ط§ طھظˆط¬ط¯ ظپظˆط§طھظٹط± ظ…طھط§ط­ط© ظ„ظ„ط¯ظپط¹ ط­ط§ظ„ظٹط§ظ‹</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- ط³ط¬ظ„ ط§ظ„ظ…ط¯ظپظˆط¹ط§طھ -->
        @if($contract->payments->count() > 0)
            <div class="mt-6">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">ط³ط¬ظ„ ط§ظ„ظ…ط¯ظپظˆط¹ط§طھ</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">ط±ظ‚ظ… ط§ظ„ظ…ط±ط¬ط¹</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">ط·ط±ظٹظ‚ط© ط§ظ„ط¯ظپط¹</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">ط§ظ„ظ…ط¨ظ„ط؛</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">طھط§ط±ظٹط® ط§ظ„ط¯ظپط¹</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">ط§ظ„ط­ط§ظ„ط©</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">ظ…ظ„ط§ط­ط¸ط§طھ</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($contract->payments as $payment)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $payment->reference_number ?? 'ط؛ظٹط± ظ…ط­ط¯ط¯' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            @switch($payment->payment_method)
                                                @case('cash') ظ†ظ‚ط¯ط§ظ‹ @break
                                                @case('bank_transfer') طھط­ظˆظٹظ„ ط¨ظ†ظƒظٹ @break
                                                @case('credit_card') ط¨ط·ط§ظ‚ط© ط§ط¦طھظ…ط§ظ† @break
                                                @case('check') ط´ظٹظƒ @break
                                                @case('online') ط¹ط¨ط± ط§ظ„ط¥ظ†طھط±ظ†طھ @break
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
                                                @case('pending') <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">ظ…ط¹ظ„ظ‚</span> @break
                                                @case('confirmed') <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">ظ…ط¤ظƒط¯</span> @break
                                                @case('failed') <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">ظپط´ظ„</span> @break
                                                @case('refunded') <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">ظ…ط³طھط±ط¯</span> @break
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

<!-- Modal ط§ظ„ط¯ظپط¹ -->
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
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">ط¯ظپط¹ ظپط§طھظˆط±ط©</h3>
                        <form id="paymentForm">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="payment_method" class="block text-sm font-medium text-gray-700">ط·ط±ظٹظ‚ط© ط§ظ„ط¯ظپط¹ <span class="text-red-500">*</span></label>
                                    <select name="payment_method" id="payment_method" required
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <option value="">ط§ط®طھط± ط·ط±ظٹظ‚ط© ط§ظ„ط¯ظپط¹</option>
                                        <option value="cash">ظ†ظ‚ط¯ط§ظ‹</option>
                                        <option value="bank_transfer">طھط­ظˆظٹظ„ ط¨ظ†ظƒظٹ</option>
                                        <option value="credit_card">ط¨ط·ط§ظ‚ط© ط§ط¦طھظ…ط§ظ†</option>
                                        <option value="check">ط´ظٹظƒ</option>
                                        <option value="online">ط¹ط¨ط± ط§ظ„ط¥ظ†طھط±ظ†طھ</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="amount" class="block text-sm font-medium text-gray-700">ط§ظ„ظ…ط¨ظ„ط؛ <span class="text-red-500">*</span></label>
                                    <input type="number" name="amount" id="amount" step="0.01" min="0" required
                                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>
                                <div>
                                    <label for="payment_date" class="block text-sm font-medium text-gray-700">طھط§ط±ظٹط® ط§ظ„ط¯ظپط¹ <span class="text-red-500">*</span></label>
                                    <input type="date" name="payment_date" id="payment_date" required
                                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>
                                <div>
                                    <label for="reference_number" class="block text-sm font-medium text-gray-700">ط±ظ‚ظ… ط§ظ„ظ…ط±ط¬ط¹</label>
                                    <input type="text" name="reference_number" id="reference_number"
                                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>
                                <div>
                                    <label for="bank_name" class="block text-sm font-medium text-gray-700">ط§ط³ظ… ط§ظ„ط¨ظ†ظƒ</label>
                                    <input type="text" name="bank_name" id="bank_name"
                                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>
                                <div>
                                    <label for="check_number" class="block text-sm font-medium text-gray-700">ط±ظ‚ظ… ط§ظ„ط´ظٹظƒ</label>
                                    <input type="text" name="check_number" id="check_number"
                                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>
                            </div>
                            <div class="mt-4">
                                <label for="notes" class="block text-sm font-medium text-gray-700">ظ…ظ„ط§ط­ط¸ط§طھ</label>
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
                    طھط³ط¬ظٹظ„ ط§ظ„ط¯ظپط¹ط©
                </button>
                <button type="button" @click="show = false" 
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    ط¥ظ„ط؛ط§ط،
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let currentInvoiceId = null;

    // ظپطھط­ modal ط§ظ„ط¯ظپط¹
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
            alert('ط®ط·ط£: ظ„ظ… ظٹطھظ… طھط­ط¯ظٹط¯ ط§ظ„ظپط§طھظˆط±ط©');
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
                alert('طھظ… طھط³ط¬ظٹظ„ ط§ظ„ط¯ظپط¹ط© ط¨ظ†ط¬ط§ط­');
                location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('ط­ط¯ط« ط®ط·ط£ ط£ط«ظ†ط§ط، طھط³ط¬ظٹظ„ ط§ظ„ط¯ظپط¹ط©');
        });
    }

    // طھط¹ظٹظٹظ† ط§ظ„طھط§ط±ظٹط® ط§ظ„ط­ط§ظ„ظٹ ظƒط§ظپطھط±ط§ط¶ظٹ
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('payment_date').value = today;
    });
</script>
@endpush
