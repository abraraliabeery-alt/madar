@php
    $isEdit = isset($building) && $building->id;
@endphp

<form action="{{ $isEdit ? route('facility.buildings.update', $building) : route('facility.buildings.store') }}"
      method="POST"
      enctype="multipart/form-data"
      class="grid grid-cols-1 gap-6">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <div class="bg-white rounded-md border border-gray-200 p-5">
        <h5 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">الترجمات</h5>
        <div class="grid grid-cols-1 gap-4">
            @foreach($locales as $locale)
                @php
                    $translation = $translations[$locale] ?? null;
                @endphp
                <div class="border border-gray-200 rounded-md p-4">
                    <h6 class="text-sm font-semibold text-gray-700 mb-3 uppercase">{{ $locale }}</h6>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">الاسم</label>
                            <input type="text" name="translations[{{ $locale }}][name]" value="{{ old('translations.'.$locale.'.name', $translation->name ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">ملاحظات</label>
                            <textarea name="translations[{{ $locale }}][notes]" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('translations.'.$locale.'.notes', $translation->notes ?? '') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">القواعد</label>
                            <textarea name="translations[{{ $locale }}][rules]" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('translations.'.$locale.'.rules', $translation->rules ?? '') }}</textarea>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="bg-white rounded-md border border-gray-200 p-5">
        <h5 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">معلومات العمارة</h5>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">عدد الأدوار</label>
                <input type="number" name="Number_of_floors" value="{{ old('Number_of_floors', $building->Number_of_floors ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">عدد الشقق</label>
                <input type="number" name="Number_of_Apartments" value="{{ old('Number_of_Apartments', $building->Number_of_Apartments ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">نسبة المكاتب (%)</label>
                <input type="number" step="0.01" name="Office_ratio" value="{{ old('Office_ratio', $building->Office_ratio ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">خط العرض</label>
                <input type="text" name="latitude" value="{{ old('latitude', $building->latitude ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">خط الطول</label>
                <input type="text" name="longitude" value="{{ old('longitude', $building->longitude ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">رابط Google Maps</label>
                <input type="text" name="google_maps_url" value="{{ old('google_maps_url', $building->google_maps_url ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </div>

        <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">الصورة الرئيسية</label>
            <input type="file" name="image" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            @if($isEdit && $building->image)
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $building->image) }}" alt="Building image" class="h-24 w-24 object-cover rounded border border-gray-200">
                </div>
            @endif
        </div>

        <div class="mt-4">
            <label class="inline-flex items-center">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $building->is_active ?? true) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                <span class="ml-2 text-sm text-gray-700">نشط</span>
            </label>
        </div>
    </div>

    <div class="flex items-center gap-3">
        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md">
            {{ $isEdit ? 'تحديث' : 'حفظ' }}
        </button>
        <a href="{{ route('facility.buildings.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 text-sm font-medium rounded-md">
            إلغاء
        </a>
    </div>
</form>
