<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.owners.name') }} <span class="text-red-500">*</span></label>
        <input type="text" name="name" value="{{ old('name', $owner->name ?? '') }}" required
               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.owners.phone') }}</label>
        <input type="text" name="phone" value="{{ old('phone', $owner->phone ?? '') }}"
               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        @error('phone') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.owners.email') }}</label>
        <input type="email" name="email" value="{{ old('email', $owner->email ?? '') }}"
               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        @error('email') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.owners.national_id') }}</label>
        <input type="text" name="national_id" value="{{ old('national_id', $owner->national_id ?? '') }}"
               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        @error('national_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.owners.commission_rate') }}</label>
        <input type="number" step="0.01" min="0" max="100" name="commission_rate" value="{{ old('commission_rate', $owner->commission_rate ?? '') }}"
               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        @error('commission_rate') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.owners.contact_person') }}</label>
        <input type="text" name="contact_person" value="{{ old('contact_person', $owner->contact_person ?? '') }}"
               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        @error('contact_person') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.owners.address') }}</label>
        <input type="text" name="address" value="{{ old('address', $owner->address ?? '') }}"
               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        @error('address') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.owners.notes') }}</label>
        <textarea name="notes" rows="2"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('notes', $owner->notes ?? '') }}</textarea>
        @error('notes') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="flex items-center gap-6">
        <label class="inline-flex items-center gap-2 text-sm text-gray-700">
            <input type="hidden" name="is_company" value="0">
            <input type="checkbox" name="is_company" value="1" {{ old('is_company', $owner->is_company ?? false) ? 'checked' : '' }} class="rounded border-gray-300">
            {{ __('facility.owners.is_company') }}
        </label>
        <label class="inline-flex items-center gap-2 text-sm text-gray-700">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $owner->is_active ?? true) ? 'checked' : '' }} class="rounded border-gray-300">
            {{ __('facility.owners.is_active') }}
        </label>
    </div>
</div>
