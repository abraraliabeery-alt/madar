@extends('facility.layouts.app')

@section('title', __('facility.tenants.title'))

@section('content')
<div class="container mx-auto px-4 my-10">
    <div class="flex justify-center">
        <div class="w-full max-w-7xl">
            <div class="bg-white rounded-lg shadow-lg">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 rounded-t-lg">
                    <div class="flex justify-between items-center">
                        <h4 class="text-lg font-semibold text-gray-800 mb-0">{{ __('facility.tenants.title') }}</h4>
                        <a href="{{ route('facility.tenants.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-colors">
                            <i class="fas fa-plus"></i>
                            <span>{{ __('facility.tenants.add_new') }}</span>
                        </a>
                    </div>
                </div>

                <div class="p-6">
                    @if(session('success'))
                        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                        <div class="md:col-span-2">
                            <input type="text" name="search" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="{{ __('facility.tenants.search_placeholder') }}" value="{{ request('search') }}">
                        </div>
                        <div>
                            <select name="is_company" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">{{ __('facility.tenants.all_types') }}</option>
                                <option value="0" {{ request('is_company') === '0' ? 'selected' : '' }}>{{ __('facility.tenants.individual') }}</option>
                                <option value="1" {{ request('is_company') === '1' ? 'selected' : '' }}>{{ __('facility.tenants.company') }}</option>
                            </select>
                        </div>
                        <div>
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">{{ __('facility.tenants.search') }}</button>
                        </div>
                    </form>

                    @if($tenants->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('facility.tenants.name') }}</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('facility.tenants.phone') }}</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('facility.tenants.national_id') }}</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('facility.tenants.type') }}</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('facility.tenants.contracts_count') }}</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('facility.tenants.status') }}</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('facility.tenants.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($tenants as $tenant)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $tenant->getTranslatedName() }}</div>
                                                @if($tenant->email)
                                                    <div class="text-sm text-gray-500">{{ $tenant->email }}</div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-700">{{ $tenant->phone ?? '—' }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-700">{{ $tenant->national_id ?? '—' }}</td>
                                            <td class="px-6 py-4">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $tenant->is_company ? 'bg-blue-50 text-blue-700' : 'bg-gray-100 text-gray-700' }}">
                                                    {{ $tenant->is_company ? __('facility.tenants.company') : __('facility.tenants.individual') }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-700">{{ $tenant->contracts_count }}</td>
                                            <td class="px-6 py-4">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $tenant->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' }}">
                                                    {{ $tenant->is_active ? __('facility.tenants.active') : __('facility.tenants.inactive') }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center space-x-2 rtl:space-x-reverse">
                                                    <a href="{{ route('facility.tenants.edit', $tenant) }}" class="inline-flex items-center justify-center w-8 h-8 text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg" title="{{ __('facility.tenants.edit') }}">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('facility.tenants.destroy', $tenant) }}" method="POST" onsubmit="return confirm('{{ __('facility.tenants.delete_confirm') }}')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="inline-flex items-center justify-center w-8 h-8 text-red-600 bg-red-50 hover:bg-red-100 rounded-lg" title="{{ __('facility.tenants.delete') }}">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">{{ $tenants->links() }}</div>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-user-check text-gray-300 text-5xl mb-4"></i>
                            <p class="text-gray-500">{{ __('facility.tenants.empty') }}</p>
                            <a href="{{ route('facility.tenants.create') }}" class="inline-flex items-center mt-4 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                                <i class="fas fa-plus ml-2"></i>{{ __('facility.tenants.add_new') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
