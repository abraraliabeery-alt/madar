@props(['field' => null, 'bag' => 'default'])

@if ($field)
    {{-- Single field validation error --}}
    @error($field, $bag)
        <div class="text-red-500 text-sm mt-1" x-data="{ show: true }" x-show="show">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle text-red-400 mr-1"></i>
                <span>{{ $message }}</span>
                <button @click="show = false" class="ml-2 text-red-400 hover:text-red-600">
                    <i class="fas fa-times text-xs"></i>
                </button>
            </div>
        </div>
    @enderror
@else
    {{-- All validation errors for the form --}}
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-md p-4 mb-4" x-data="{ show: true }" x-show="show">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-red-400"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">
                        {{ __('validation.errors.title') }}
                    </h3>
                    <div class="mt-2 text-sm text-red-700">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="mt-4">
                        <button @click="show = false" class="bg-red-50 text-red-700 hover:bg-red-100 px-3 py-2 rounded-md text-sm font-medium">
                            {{ __('validation.errors.close') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endif
