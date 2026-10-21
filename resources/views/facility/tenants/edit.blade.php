@extends('facility.layouts.app')

@section('title', __('facility.tenants.edit'))

@section('content')
<div class="container mx-auto px-4 my-10">
    <div class="flex justify-center">
        <div class="w-full max-w-4xl">
            <div class="mb-6">
                <h1 class="text-2xl font-semibold text-gray-800">{{ __('facility.tenants.edit') }}: {{ $tenant->getTranslatedName() }}</h1>
            </div>

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                    <ul class="list-disc pr-5 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('facility.tenants.update', $tenant) }}">
                @csrf
                @method('PUT')
                @include('facility.tenants._form')

                <div class="flex items-center gap-3">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-6 rounded-lg shadow transition-colors">
                        <i class="fas fa-save ml-2"></i>{{ __('facility.tenants.save') }}
                    </button>
                    <a href="{{ route('facility.tenants.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2.5 px-6 rounded-lg shadow transition-colors">
                        {{ __('facility.tenants.cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
