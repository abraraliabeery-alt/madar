@extends('facility.layouts.app')

@section('title', __('facility.owners.add_new'))

@section('content')
<div class="container mx-auto px-4 my-10">
    <div class="flex justify-center">
        <div class="w-full max-w-4xl">
            <div class="bg-white rounded-lg shadow-lg">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 rounded-t-lg flex justify-between items-center">
                    <h4 class="text-lg font-semibold text-gray-800 mb-0">{{ __('facility.owners.add_new') }}</h4>
                    <a href="{{ route('facility.owners.index') }}" class="text-gray-500 hover:text-gray-700 text-sm">← {{ __('facility.owners.back') }}</a>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ route('facility.owners.store') }}">
                        @csrf
                        @include('facility.owners._form')
                        <div class="mt-6 flex justify-end gap-3">
                            <a href="{{ route('facility.owners.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">{{ __('facility.owners.cancel') }}</a>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">{{ __('facility.owners.save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
