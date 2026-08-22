@extends('client.layouts.app')

@section('title', 'ط¹ظ‚ظˆط¯ظٹ')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-900">ط¹ظ‚ظˆط¯ظٹ</h1>
                <a href="{{ route('client.contracts.statistics') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-chart-bar ml-2"></i>
                    ط§ظ„ط¥ط­طµط§ط¦ظٹط§طھ
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <select name="type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">ط¬ظ…ظٹط¹ ط§ظ„ط£ظ†ظˆط§ط¹</option>
                        <option value="sale" {{ request('type') == 'sale' ? 'selected' : '' }}>ط¨ظٹط¹</option>
                        <option value="rent" {{ request('type') == 'rent' ? 'selected' : '' }}>ط¥ظٹط¬ط§ط±</option>
                    </select>
                </div>
                <div>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">ط¬ظ…ظٹط¹ ط§ظ„ط­ط§ظ„ط§طھ</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>ظ…ط³ظˆط¯ط©</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>ظ†ط´ط·</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>ظ…ظƒطھظ…ظ„</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>ظ…ظ„ط؛ظٹ</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <input type="text" name="search" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                           placeholder="ط§ظ„ط¨ط­ط« ظپظٹ ط§ظ„ط¹ظ‚ظˆط¯..." value="{{ request('search') }}">
                </div>
                <div>
                    <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        ط¨ط­ط«
                    </button>
                </div>
            </form>
        </div>

        @if($contracts->count() > 0)
            <!-- Contracts Table -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">ط±ظ‚ظ… ط§ظ„ط¹ظ‚ط¯</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">ط§ظ„ظ…ظ†طھط¬</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">ط§ظ„ظ…ط§ظ„ظƒ</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">ط§ظ„ظ†ظˆط¹</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">ط§ظ„ظ…ط¨ظ„ط؛</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">ط§ظ„ظ…ط¯ظپظˆط¹</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">ط§ظ„ظ…طھط¨ظ‚ظٹ</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">ط§ظ„ط­ط§ظ„ط©</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">ط§ظ„ط¥ط¬ط±ط§ط،ط§طھ</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($contracts as $contract)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $contract->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $contract->contract_number ?? 'ط؛ظٹط± ظ…ط­ط¯ط¯' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @if($contract->product->image)
                                                <img src="{{ asset('storage/' . $contract->product->image) }}" 
                                                     class="h-10 w-10 rounded-lg object-cover ml-3" alt="طµظˆط±ط© ط§ظ„ظ…ظ†طھط¬">
                                            @endif
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $contract->product->getTranslatedTitle() }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $contract->product->address }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $contract->owner->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $contract->owner->email }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                            {{ $contract->contract_type == 'sale' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                            {{ $contract->contract_type == 'sale' ? 'ط¨ظٹط¹' : 'ط¥ظٹط¬ط§ط±' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center text-sm font-medium text-gray-900">
                                            {{ number_format($contract->total_amount, 2) }}
                                            <img src="{{ asset('Saudi_Riyal_Symbol.svg') }}" alt="SAR" class="w-4 h-4 mr-1">
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $totalPaid = $contract->getTotalPaidAmount();
                                        @endphp
                                        <div class="flex items-center text-sm text-green-600">
                                            {{ number_format($totalPaid, 2) }}
                                            <img src="{{ asset('Saudi_Riyal_Symbol.svg') }}" alt="SAR" class="w-3 h-3 mr-1">
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $remaining = $contract->getRemainingAmount();
                                        @endphp
                                        <div class="flex items-center text-sm {{ $remaining > 0 ? 'text-yellow-600' : 'text-green-600' }}">
                                            {{ number_format($remaining, 2) }}
                                            <img src="{{ asset('Saudi_Riyal_Symbol.svg') }}" alt="SAR" class="w-3 h-3 mr-1">
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @switch($contract->status)
                                            @case('draft')
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">ظ…ط³ظˆط¯ط©</span>
                                                @break
                                            @case('active')
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">ظ†ط´ط·</span>
                                                @break
                                            @case('completed')
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">ظ…ظƒطھظ…ظ„</span>
                                                @break
                                            @case('cancelled')
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">ظ…ظ„ط؛ظٹ</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-2 space-x-reverse">
                                            <a href="{{ route('client.contracts.show', $contract) }}" 
                                               class="inline-flex items-center p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-colors" 
                                               title="ط¹ط±ط¶">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('client.contracts.financial-report', $contract) }}" 
                                               class="inline-flex items-center p-2 text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 rounded-lg transition-colors" 
                                               title="ط§ظ„طھظ‚ط±ظٹط± ط§ظ„ظ…ط§ظ„ظٹ">
                                                <i class="fas fa-chart-line"></i>
                                            </a>
                                            <div class="relative" x-data="{ open: false }">
                                                <button @click="open = !open" 
                                                        class="inline-flex items-center p-2 text-gray-600 hover:text-gray-800 hover:bg-gray-50 rounded-lg transition-colors">
                                                    <i class="fas fa-cog"></i>
                                                </button>
                                                <div x-show="open" @click.away="open = false" 
                                                     class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10 border border-gray-200">
                                                    <div class="py-1">
                                                        <a href="{{ route('client.contracts.invoices', $contract) }}" 
                                                           class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                            <i class="fas fa-file-invoice ml-3"></i>
                                                            ط§ظ„ظپظˆط§طھظٹط±
                                                        </a>
                                                        <a href="{{ route('client.contracts.payments', $contract) }}" 
                                                           class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                            <i class="fas fa-credit-card ml-3"></i>
                                                            ط§ظ„ظ…ط¯ظپظˆط¹ط§طھ
                                                        </a>
                                                        <a href="{{ route('client.contracts.payment-page', $contract) }}" 
                                                           class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                            <i class="fas fa-credit-card ml-3"></i>
                                                            ط¯ظپط¹ ظپط§طھظˆط±ط©
                                                        </a>
                                                        @if($contract->status === 'draft')
                                                            <div class="border-t border-gray-200"></div>
                                                            <button class="flex items-center w-full px-4 py-2 text-sm text-green-700 hover:bg-gray-100 confirm-contract" 
                                                                    data-contract-id="{{ $contract->id }}">
                                                                <i class="fas fa-check ml-3"></i>
                                                                طھط£ظƒظٹط¯ ط§ظ„ط¹ظ‚ط¯
                                                            </button>
                                                            <button class="flex items-center w-full px-4 py-2 text-sm text-red-700 hover:bg-gray-100 cancel-contract" 
                                                                    data-contract-id="{{ $contract->id }}">
                                                                <i class="fas fa-times ml-3"></i>
                                                                ط¥ظ„ط؛ط§ط، ط§ظ„ط¹ظ‚ط¯
                                                            </button>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="flex justify-center mt-6">
                {{ $contracts->appends(request()->query())->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                <i class="fas fa-file-contract text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">ظ„ط§ طھظˆط¬ط¯ ط¹ظ‚ظˆط¯</h3>
                <p class="text-gray-500 mb-6">ط§ط¨ط¯ط£ ط¨ط·ظ„ط¨ ط¹ظ‚ط¯ ط¬ط¯ظٹط¯</p>
                <a href="{{ route('client.offers.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-search ml-2"></i>
                    طھطµظپط­ ط§ظ„ط¹ط±ظˆط¶
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Modal طھط£ظƒظٹط¯ ط§ظ„ط¹ظ‚ط¯ -->
<div x-data="{ show: false }" x-show="show" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="show = false"></div>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fas fa-check text-green-600"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:mr-4 sm:text-right">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">طھط£ظƒظٹط¯ ط§ظ„ط¹ظ‚ط¯</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">ظ‡ظ„ ط£ظ†طھ ظ…طھط£ظƒط¯ ظ…ظ† طھط£ظƒظٹط¯ ظ‡ط°ط§ ط§ظ„ط¹ظ‚ط¯طں</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" @click="show = false; confirmContract()" 
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                    طھط£ظƒظٹط¯
                </button>
                <button type="button" @click="show = false" 
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    ط¥ظ„ط؛ط§ط،
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal ط¥ظ„ط؛ط§ط، ط§ظ„ط¹ظ‚ط¯ -->
<div x-data="{ show: false }" x-show="show" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="show = false"></div>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fas fa-times text-red-600"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:mr-4 sm:text-right">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">ط¥ظ„ط؛ط§ط، ط§ظ„ط¹ظ‚ط¯</h3>
                        <div class="mt-2">
                            <form id="cancelContractForm">
                                <div>
                                    <label for="cancelReason" class="block text-sm font-medium text-gray-700">ط³ط¨ط¨ ط§ظ„ط¥ظ„ط؛ط§ط، <span class="text-red-500">*</span></label>
                                    <textarea name="reason" id="cancelReason" rows="3" required
                                              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" @click="show = false; cancelContract()" 
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                    ط¥ظ„ط؛ط§ط، ط§ظ„ط¹ظ‚ط¯
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
    let currentContractId = null;

    // طھط£ظƒظٹط¯ ط§ظ„ط¹ظ‚ط¯
    document.querySelectorAll('.confirm-contract').forEach(button => {
        button.addEventListener('click', function() {
            currentContractId = this.dataset.contractId;
            // Show modal using Alpine.js
            const modal = document.querySelector('[x-data*="show: false"]');
            if (modal) {
                modal._x_dataStack[0].show = true;
            }
        });
    });

    function confirmContract() {
        if (currentContractId) {
            fetch(`/client/contracts/${currentContractId}/confirm`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    }

    // ط¥ظ„ط؛ط§ط، ط§ظ„ط¹ظ‚ط¯
    document.querySelectorAll('.cancel-contract').forEach(button => {
        button.addEventListener('click', function() {
            currentContractId = this.dataset.contractId;
            // Show modal using Alpine.js
            const modal = document.querySelector('[x-data*="show: false"]:last-child');
            if (modal) {
                modal._x_dataStack[0].show = true;
            }
        });
    });

    function cancelContract() {
        if (currentContractId) {
            const formData = new FormData(document.getElementById('cancelContractForm'));
            
            fetch(`/client/contracts/${currentContractId}/cancel`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    }

    // Auto-submit form on filter change
    document.querySelectorAll('select[name="type"], select[name="status"]').forEach(select => {
        select.addEventListener('change', function() {
            this.form.submit();
        });
    });
</script>
@endpush
