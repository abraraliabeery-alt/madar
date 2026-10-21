@extends('client.layouts.app')

@section('title', __('client.new_booking'))

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-xl mx-auto px-4">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900">{{ __('client.new_booking') }}</h1>
            <a href="{{ route('client.bookings.index') }}" class="text-sm text-blue-600 hover:underline">{{ __('client.back') }}</a>
        </div>

        @if(session('error'))
            <div class="bg-red-100 text-red-800 rounded-lg p-4 mb-6">{{ session('error') }}</div>
        @endif

        <div class="bg-white rounded-lg shadow-sm p-6">
            <form method="POST" action="{{ route('client.bookings.store') }}" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('client.product') }}</label>
                    <select name="product_id" required class="w-full rounded-md border-gray-300">
                        <option value="">{{ __('client.choose_product') }}</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->getTranslatedTitle() }} — {{ optional($product->facility)->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('product_id')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('client.amount') }}</label>
                    <input type="number" name="total_amount" value="{{ old('total_amount') }}" min="0" step="0.01" required class="w-full rounded-md border-gray-300">
                    @error('total_amount')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('client.payment_method') }}</label>
                    <select name="payment_method" class="w-full rounded-md border-gray-300">
                        <option value="">{{ __('client.choose') }}</option>
                        <option value="cash">{{ __('client.cash') }}</option>
                        <option value="bank_transfer">{{ __('client.bank_transfer') }}</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('client.notes') }}</label>
                    <textarea name="notes" rows="3" class="w-full rounded-md border-gray-300">{{ old('notes') }}</textarea>
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white rounded-md py-2 hover:bg-blue-700">{{ __('client.create_booking') }}</button>
            </form>
        </div>
    </div>
</div>
@endsection
