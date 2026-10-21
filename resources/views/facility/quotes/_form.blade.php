@php
$items = old('items', $quote->exists ? $quote->items->toArray() : [[]]);
$terms = old('terms', $quote->terms->toArray());
$discounts = old('discounts', $quote->discounts->toArray());
$maxItem = count($items) > 0 ? max(array_keys($items)) : -1;
$maxTerm = count($terms) > 0 ? max(array_keys($terms)) : -1;
$maxDiscount = count($discounts) > 0 ? max(array_keys($discounts)) : -1;
@endphp

<div class="container mx-auto px-4 my-10">
    <div class="w-full max-w-6xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">
            {{ $quote->exists ? 'تعديل عرض السعر' : 'إنشاء عرض سعر' }}
        </h1>

        <form id="quoteForm" action="{{ $quote->exists ? route('facility.quotes.update', $quote) : route('facility.quotes.store') }}" method="POST">
            @csrf
            @if($quote->exists) @method('PUT') @endif

            {{-- Client & Header --}}
            <div class="bg-white rounded-md border border-gray-200 p-5 mb-6">
                <h5 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">بيانات العرض</h5>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">رقم العرض</label>
                        <input type="text" value="{{ $quoteNumber }}" disabled class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">عنوان العرض <span class="text-red-500">*</span></label>
                        <input type="text" name="title" value="{{ old('title', $quote->title) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-teal-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">اسم العميل</label>
                        <input type="text" name="client_name" value="{{ old('client_name', $quote->client_name) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-teal-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">البريد الإلكتروني</label>
                        <input type="email" name="client_email" value="{{ old('client_email', $quote->client_email) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-teal-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">رقم الجوال</label>
                        <input type="text" name="client_phone" value="{{ old('client_phone', $quote->client_phone) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-teal-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">الحالة</label>
                        <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-teal-500">
                            @foreach($statuses as $status)
                                <option value="{{ $status->value }}" {{ old('status', $quote->status?->value ?? 'draft') === $status->value ? 'selected' : '' }}>{{ $status->label() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">ملاحظات</label>
                        <textarea name="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-teal-500">{{ old('notes', $quote->notes) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Items --}}
            <div class="bg-white rounded-md border border-gray-200 p-5 mb-6">
                <div class="flex justify-between items-center mb-4 pb-2 border-b border-gray-200">
                    <h5 class="text-lg font-semibold text-gray-800">بنود السعر</h5>
                    <button type="button" onclick="addItem()" class="inline-flex items-center gap-1 bg-gray-100 hover:bg-gray-200 text-gray-800 text-sm font-medium py-2 px-3 rounded-md">+ إضافة بند</button>
                </div>
                <div id="itemsList" class="space-y-4">
                    @foreach($items as $i => $item)
                        <div class="quote-item grid grid-cols-1 md:grid-cols-12 gap-4 p-4 bg-gray-50 rounded-md" data-index="{{ $i }}">
                            <div class="md:col-span-3">
                                <label class="block text-xs text-gray-500 mb-1">الاسم</label>
                                <input type="text" name="items[{{ $i }}][name]" value="{{ $item['name'] ?? '' }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs text-gray-500 mb-1">الكمية</label>
                                <input type="number" step="0.01" name="items[{{ $i }}][quantity]" value="{{ $item['quantity'] ?? 1 }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm item-qty">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs text-gray-500 mb-1">الوحدة</label>
                                <input type="text" name="items[{{ $i }}][unit]" value="{{ $item['unit'] ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm item-unit">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs text-gray-500 mb-1">سعر الوحدة</label>
                                <input type="number" step="0.01" name="items[{{ $i }}][unit_price]" value="{{ $item['unit_price'] ?? 0 }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm item-price">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs text-gray-500 mb-1">الإجمالي</label>
                                <input type="text" readonly class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm bg-gray-100 item-total" value="0.00">
                            </div>
                            <div class="md:col-span-1 flex items-end gap-2">
                                <button type="button" onclick="suggestPrice({{ $i }})" class="w-full py-2 bg-teal-600 hover:bg-teal-700 text-white text-xs rounded-md">AI</button>
                            </div>
                            <div class="md:col-span-12">
                                <label class="block text-xs text-gray-500 mb-1">الوصف والمواصفات</label>
                                <textarea name="items[{{ $i }}][description]" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">{{ $item['description'] ?? '' }}</textarea>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Terms --}}
            <div class="bg-white rounded-md border border-gray-200 p-5 mb-6">
                <div class="flex justify-between items-center mb-4 pb-2 border-b border-gray-200">
                    <h5 class="text-lg font-semibold text-gray-800">الشروط والأحكام</h5>
                    <button type="button" onclick="addTerm()" class="inline-flex items-center gap-1 bg-gray-100 hover:bg-gray-200 text-gray-800 text-sm font-medium py-2 px-3 rounded-md">+ إضافة شرط</button>
                </div>
                <div id="termsList" class="space-y-4">
                    @foreach($terms as $i => $term)
                        <div class="quote-term grid grid-cols-1 md:grid-cols-12 gap-4 p-4 bg-gray-50 rounded-md" data-index="{{ $i }}">
                            <div class="md:col-span-3">
                                <label class="block text-xs text-gray-500 mb-1">العنوان</label>
                                <input type="text" name="terms[{{ $i }}][title]" value="{{ $term['title'] ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                            </div>
                            <div class="md:col-span-8">
                                <label class="block text-xs text-gray-500 mb-1">الشرط</label>
                                <textarea name="terms[{{ $i }}][content]" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">{{ $term['content'] ?? '' }}</textarea>
                            </div>
                            <div class="md:col-span-1 flex items-end">
                                <button type="button" onclick="removeTerm(this)" class="w-full py-2 bg-rose-100 hover:bg-rose-200 text-rose-700 text-xs rounded-md">×</button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Discounts --}}
            <div class="bg-white rounded-md border border-gray-200 p-5 mb-6">
                <div class="flex justify-between items-center mb-4 pb-2 border-b border-gray-200">
                    <h5 class="text-lg font-semibold text-gray-800">جدول الدفعات</h5>
                    <button type="button" onclick="addDiscount()" class="inline-flex items-center gap-1 bg-gray-100 hover:bg-gray-200 text-gray-800 text-sm font-medium py-2 px-3 rounded-md">+ إضافة دفعة</button>
                </div>
                <div id="discountsList" class="space-y-4">
                    @foreach($discounts as $i => $discount)
                        <div class="quote-discount grid grid-cols-1 md:grid-cols-12 gap-4 p-4 bg-gray-50 rounded-md" data-index="{{ $i }}">
                            <div class="md:col-span-3">
                                <label class="block text-xs text-gray-500 mb-1">اسم الدفعة</label>
                                <input type="text" name="discounts[{{ $i }}][name]" value="{{ $discount['name'] ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs text-gray-500 mb-1">الطريقة</label>
                                <select name="discounts[{{ $i }}][method]" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm discount-method">
                                    @foreach($methods as $method)
                                        <option value="{{ $method->value }}" {{ ($discount['method'] ?? 'percentage') === $method->value ? 'selected' : '' }}>{{ $method->label() }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs text-gray-500 mb-1">القيمة</label>
                                <input type="number" step="0.01" name="discounts[{{ $i }}][value]" value="{{ $discount['value'] ?? 0 }}" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm discount-value">
                            </div>
                            <div class="md:col-span-4">
                                <label class="block text-xs text-gray-500 mb-1">شرط الاستحقاق</label>
                                <input type="text" name="discounts[{{ $i }}][condition]" value="{{ $discount['condition'] ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                            </div>
                            <div class="md:col-span-1 flex items-end">
                                <button type="button" onclick="removeDiscount(this)" class="w-full py-2 bg-rose-100 hover:bg-rose-200 text-rose-700 text-xs rounded-md">×</button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Totals --}}
            <div class="bg-white rounded-md border border-gray-200 p-5 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
                    <div>
                        <p class="text-sm text-gray-500">المجموع الفرعي</p>
                        <p class="text-xl font-bold text-gray-800" id="subtotal">0.00 ر.س</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">الخصومات</p>
                        <p class="text-xl font-bold text-rose-600" id="discountsTotal">0.00 ر.س</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">الإجمالي</p>
                        <p class="text-2xl font-bold text-teal-700" id="grandTotal">0.00 ر.س</p>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('facility.quotes.index') }}" class="py-2.5 px-5 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-md">إلغاء</a>
                <button type="submit" class="py-2.5 px-5 bg-teal-600 hover:bg-teal-700 text-white rounded-md">إنشاء عرض السعر</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
let itemIndex = {{ $maxItem + 1 }};
let termIndex = {{ $maxTerm + 1 }};
let discountIndex = {{ $maxDiscount + 1 }};

function addItem() {
    const i = itemIndex++;
    const html = `
        <div class="quote-item grid grid-cols-1 md:grid-cols-12 gap-4 p-4 bg-gray-50 rounded-md" data-index="${i}">
            <div class="md:col-span-3"><label class="block text-xs text-gray-500 mb-1">الاسم</label><input type="text" name="items[${i}][name]" required class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm"></div>
            <div class="md:col-span-2"><label class="block text-xs text-gray-500 mb-1">الكمية</label><input type="number" step="0.01" name="items[${i}][quantity]" value="1" required class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm item-qty"></div>
            <div class="md:col-span-2"><label class="block text-xs text-gray-500 mb-1">الوحدة</label><input type="text" name="items[${i}][unit]" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm item-unit"></div>
            <div class="md:col-span-2"><label class="block text-xs text-gray-500 mb-1">سعر الوحدة</label><input type="number" step="0.01" name="items[${i}][unit_price]" value="0" required class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm item-price"></div>
            <div class="md:col-span-2"><label class="block text-xs text-gray-500 mb-1">الإجمالي</label><input type="text" readonly class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm bg-gray-100 item-total" value="0.00"></div>
            <div class="md:col-span-1 flex items-end gap-2"><button type="button" onclick="suggestPrice(${i})" class="w-full py-2 bg-teal-600 hover:bg-teal-700 text-white text-xs rounded-md">AI</button></div>
            <div class="md:col-span-12"><label class="block text-xs text-gray-500 mb-1">الوصف والمواصفات</label><textarea name="items[${i}][description]" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm"></textarea></div>
        </div>
    `;
    document.getElementById('itemsList').insertAdjacentHTML('beforeend', html);
    recalc();
    attachListeners();
}

function addTerm() {
    const i = termIndex++;
    const html = `
        <div class="quote-term grid grid-cols-1 md:grid-cols-12 gap-4 p-4 bg-gray-50 rounded-md" data-index="${i}">
            <div class="md:col-span-3"><label class="block text-xs text-gray-500 mb-1">العنوان</label><input type="text" name="terms[${i}][title]" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm"></div>
            <div class="md:col-span-8"><label class="block text-xs text-gray-500 mb-1">الشرط</label><textarea name="terms[${i}][content]" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm"></textarea></div>
            <div class="md:col-span-1 flex items-end"><button type="button" onclick="this.closest('.quote-term').remove()" class="w-full py-2 bg-rose-100 hover:bg-rose-200 text-rose-700 text-xs rounded-md">×</button></div>
        </div>
    `;
    document.getElementById('termsList').insertAdjacentHTML('beforeend', html);
}

function addDiscount() {
    const i = discountIndex++;
    const html = `
        <div class="quote-discount grid grid-cols-1 md:grid-cols-12 gap-4 p-4 bg-gray-50 rounded-md" data-index="${i}">
            <div class="md:col-span-3"><label class="block text-xs text-gray-500 mb-1">اسم الدفعة</label><input type="text" name="discounts[${i}][name]" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm"></div>
            <div class="md:col-span-2"><label class="block text-xs text-gray-500 mb-1">الطريقة</label><select name="discounts[${i}][method]" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm discount-method">@foreach($methods as $method)<option value="{{ $method->value }}">{{ $method->label() }}</option>@endforeach</select></div>
            <div class="md:col-span-2"><label class="block text-xs text-gray-500 mb-1">القيمة</label><input type="number" step="0.01" name="discounts[${i}][value]" value="0" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm discount-value"></div>
            <div class="md:col-span-4"><label class="block text-xs text-gray-500 mb-1">شرط الاستحقاق</label><input type="text" name="discounts[${i}][condition]" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm"></div>
            <div class="md:col-span-1 flex items-end"><button type="button" onclick="this.closest('.quote-discount').remove(); recalc();" class="w-full py-2 bg-rose-100 hover:bg-rose-200 text-rose-700 text-xs rounded-md">×</button></div>
        </div>
    `;
    document.getElementById('discountsList').insertAdjacentHTML('beforeend', html);
    attachListeners();
    recalc();
}

function suggestPrice(index) {
    const item = document.querySelector(`.quote-item[data-index="${index}"]`);
    const name = item.querySelector('input[name$="[name]"]').value;
    const unit = item.querySelector('input[name$="[unit]"]').value;
    const btn = item.querySelector('button');
    btn.textContent = '...';

    fetch('{{ route('facility.quotes.suggest-price') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ name, unit })
    })
    .then(r => r.json())
    .then(data => {
        if (data.price) {
            item.querySelector('input[name$="[unit_price]"]').value = data.price;
        }
        btn.textContent = 'AI';
        recalc();
    })
    .catch(() => { btn.textContent = 'AI'; alert('لم يتم الحصول على تسعير'); });
}

function attachListeners() {
    document.querySelectorAll('.item-qty, .item-price, .discount-value, .discount-method').forEach(el => {
        el.removeEventListener('input', recalc);
        el.removeEventListener('change', recalc);
        el.addEventListener('input', recalc);
        el.addEventListener('change', recalc);
    });
}

function recalc() {
    let subtotal = 0;
    document.querySelectorAll('.quote-item').forEach(item => {
        const qty = parseFloat(item.querySelector('.item-qty').value) || 0;
        const price = parseFloat(item.querySelector('.item-price').value) || 0;
        const total = qty * price;
        item.querySelector('.item-total').value = total.toFixed(2);
        subtotal += total;
    });

    let discounts = 0;
    document.querySelectorAll('.quote-discount').forEach(d => {
        const method = d.querySelector('.discount-method').value;
        const value = parseFloat(d.querySelector('.discount-value').value) || 0;
        if (method === 'percentage') {
            discounts += subtotal * (value / 100);
        } else {
            discounts += value;
        }
    });

    const grand = Math.max(0, subtotal - discounts);

    document.getElementById('subtotal').textContent = subtotal.toFixed(2) + ' ر.س';
    document.getElementById('discountsTotal').textContent = discounts.toFixed(2) + ' ر.س';
    document.getElementById('grandTotal').textContent = grand.toFixed(2) + ' ر.س';
}

function removeTerm(btn) { btn.closest('.quote-term').remove(); }
function removeDiscount(btn) { btn.closest('.quote-discount').remove(); recalc(); }

document.addEventListener('DOMContentLoaded', () => {
    attachListeners();
    recalc();
});
</script>
@endpush
