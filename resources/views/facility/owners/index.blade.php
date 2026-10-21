@extends('facility.layouts.app')

@section('title', __('facility.owners.title'))

@section('content')
<div class="container mx-auto px-4 my-10">
    <div class="flex justify-center">
        <div class="w-full max-w-7xl">
            <div class="bg-white rounded-lg shadow-lg">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 rounded-t-lg">
                    <div class="flex justify-between items-center">
                        <h4 class="text-lg font-semibold text-gray-800 mb-0">{{ __('facility.owners.title') }}</h4>
                        <a href="{{ route('facility.owners.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-colors">
                            <i class="fas fa-plus"></i>
                            <span>{{ __('facility.owners.add_new') }}</span>
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
                        <div class="md:col-span-3">
                            <input type="text" name="search" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="{{ __('facility.owners.search_placeholder') }}" value="{{ request('search') }}">
                        </div>
                        <div>
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">{{ __('facility.owners.search') }}</button>
                        </div>
                    </form>

                    @if($owners->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('facility.owners.name') }}</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('facility.owners.phone') }}</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('facility.owners.type') }}</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('facility.owners.commission_rate') }}</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('facility.owners.properties_count') }}</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('facility.owners.status') }}</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('facility.owners.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($owners as $owner)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $owner->name }}</div>
                                                @if($owner->email)
                                                    <div class="text-sm text-gray-500">{{ $owner->email }}</div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-700">{{ $owner->phone ?? '—' }}</td>
                                            <td class="px-6 py-4">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $owner->is_company ? 'bg-blue-50 text-blue-700' : 'bg-gray-100 text-gray-700' }}">
                                                    {{ $owner->is_company ? __('facility.owners.company') : __('facility.owners.individual') }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-700">{{ $owner->commission_rate !== null ? $owner->commission_rate . '%' : '—' }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-700">{{ $owner->products_count }}</td>
                                            <td class="px-6 py-4">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $owner->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' }}">
                                                    {{ $owner->is_active ? __('facility.owners.active') : __('facility.owners.inactive') }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center space-x-2 rtl:space-x-reverse">
                                                    <a href="{{ route('facility.owners.show', $owner) }}" class="inline-flex items-center justify-center w-8 h-8 text-emerald-600 bg-emerald-50 hover:bg-emerald-100 rounded-lg" title="كشف حساب">
                                                        <i class="fas fa-file-invoice-dollar"></i>
                                                    </a>
                                                    <a href="{{ route('facility.owners.edit', $owner) }}" class="inline-flex items-center justify-center w-8 h-8 text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg" title="{{ __('facility.owners.edit') }}">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('facility.owners.destroy', $owner) }}" method="POST" onsubmit="return confirm('{{ __('facility.owners.delete_confirm') }}')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="inline-flex items-center justify-center w-8 h-8 text-red-600 bg-red-50 hover:bg-red-100 rounded-lg" title="{{ __('facility.owners.delete') }}">
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
                        <div class="mt-4">{{ $owners->links() }}</div>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-user-tie text-gray-300 text-5xl mb-4"></i>
                            <p class="text-gray-500">{{ __('facility.owners.empty') }}</p>
                            <a href="{{ route('facility.owners.create') }}" class="inline-flex items-center mt-4 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                                <i class="fas fa-plus ml-2"></i>{{ __('facility.owners.add_new') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
