@extends('layouts.app')

@section('meta')
<meta name="language" content="{{ app()->getLocale() }}">
<meta name="language-alternate" content="{{ app()->getLocale() === 'ar' ? 'en' : 'ar' }}">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ __('general.categories.title') }} - {{ config('app.name') }}</title>
<meta name="description" content="{{ __('general.categories.description') }}">
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Header -->
    <div class="text-center mb-12">
        <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ __('general.categories.title') }}</h1>
        <p class="text-lg text-gray-600">{{ __('general.categories.subtitle') }}</p>
    </div>

    <!-- Categories Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($categories as $category)
        <div class="bg-white border border-gray-200 rounded-lg p-6 text-center hover:shadow-md transition-shadow">
            @if($category->icon)
                <i class="{{ $category->icon }} text-4xl text-primary-600 mb-4"></i>
            @else
                <i class="fas fa-building text-4xl text-primary-600 mb-4"></i>
            @endif
            
            <h3 class="font-medium text-gray-900 mb-2">{{ $category->display_name ?? categoryName($category) }}</h3>
            <p class="text-sm text-gray-600 mb-3 line-clamp-2">@categoryDescription($category)</p>
            
            <div class="text-sm text-gray-500 mb-4">
                {{ $category->products_count ?? 0 }} {{ __('general.status.property') }}
            </div>
            
            <a href="{{ route('public.categories.show', $category->id) }}" 
               class="text-primary-600 hover:text-primary-700 font-medium">
                {{ __('general.actions.browse_category') }}
            </a>
        </div>
        @empty
        <div class="col-span-full text-center py-12">
            <i class="fas fa-folder-open text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-medium text-gray-900 mb-2">{{ __('general.categories.no_categories') }}</h3>
            <p class="text-gray-600">{{ __('general.categories.no_categories_description') }}</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($categories->hasPages())
    <div class="mt-8">
        {{ $categories->links() }}
    </div>
    @endif
</div>
@endsection

@push('styles')
<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endpush
