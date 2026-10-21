@php
    $existingTranslations = $tenant->exists
        ? $tenant->translations->map(fn($t) => ['locale' => $t->locale, 'name' => $t->name, 'notes' => $t->notes])->values()->all()
        : [];
@endphp

<div class="bg-white rounded-lg border border-gray-200 p-5 mb-6">
    <h5 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">{{ __('facility.tenants.basic_info') }}</h5>

    @include('components.translations-repeater', [
        'locales' => config('locales.available', []),
        'namePrefix' => 'translations',
        'fields' => [
            ['type' => 'input', 'key' => 'name', 'label' => __('facility.tenants.name'), 'requiredFirst' => true, 'placeholder' => __('facility.tenants.name_placeholder')],
            ['type' => 'textarea', 'key' => 'notes', 'label' => __('facility.tenants.notes'), 'rows' => 2, 'placeholder' => __('facility.tenants.notes_placeholder')],
        ],
        'items' => $existingTranslations,
        'addLabel' => __('facility.tenants.add_translation'),
        'removeLabel' => __('facility.tenants.remove_translation'),
        'minItems' => 1,
    ])
</div>

<div class="bg-white rounded-lg border border-gray-200 p-5 mb-6">
    <h5 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">{{ __('facility.tenants.contact_info') }}</h5>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.tenants.type') }}</label>
            <select name="is_company" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="0" {{ old('is_company', $tenant->is_company ? '1' : '0') === '0' ? 'selected' : '' }}>{{ __('facility.tenants.individual') }}</option>
                <option value="1" {{ old('is_company', $tenant->is_company ? '1' : '0') === '1' ? 'selected' : '' }}>{{ __('facility.tenants.company') }}</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.tenants.contact_person') }}</label>
            <input type="text" name="contact_person" value="{{ old('contact_person', $tenant->contact_person) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="{{ __('facility.tenants.contact_person_placeholder') }}">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.tenants.phone') }}</label>
            <input type="text" name="phone" value="{{ old('phone', $tenant->phone) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="05xxxxxxxx">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.tenants.email') }}</label>
            <input type="email" name="email" value="{{ old('email', $tenant->email) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="email@example.com">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.tenants.national_id') }}</label>
            <input type="text" name="national_id" value="{{ old('national_id', $tenant->national_id) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.tenants.linked_user') }}</label>
            <select name="user_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">{{ __('facility.tenants.no_user') }}</option>
                @foreach($users ?? [] as $user)
                    <option value="{{ $user->id }}" {{ (string) old('user_id', $tenant->user_id) === (string) $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                @endforeach
            </select>
            <p class="text-xs text-gray-500 mt-1">{{ __('facility.tenants.linked_user_help') }}</p>
        </div>
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.tenants.address') }}</label>
            <input type="text" name="address" value="{{ old('address', $tenant->address) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div class="flex items-center gap-2">
            <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $tenant->exists ? $tenant->is_active : true) ? 'checked' : '' }} class="h-4 w-4 text-blue-600 border-gray-300 rounded">
            <label for="is_active" class="text-sm text-gray-700">{{ __('facility.tenants.is_active') }}</label>
        </div>
    </div>
</div>
