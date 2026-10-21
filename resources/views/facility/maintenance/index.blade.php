@extends('facility.layouts.app')

@section('title', __('facility.maintenance.title'))

@section('content')
<div class="container mx-auto px-4 my-10">
    <div class="flex justify-center">
        <div class="w-full max-w-7xl">
            <div class="bg-white rounded-lg shadow-lg">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 rounded-t-lg">
                    <div class="flex justify-between items-center">
                        <h4 class="text-lg font-semibold text-gray-800 mb-0">{{ __('facility.maintenance.title') }}</h4>
                        <a href="{{ route('facility.maintenance.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-colors">
                            <i class="fas fa-plus"></i>
                            <span>{{ __('facility.maintenance.add_new') }}</span>
                        </a>
                    </div>
                </div>

                <div class="p-6">
                    @if(session('success'))
                        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
                        <div>
                            <input type="text" name="search" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="{{ __('facility.maintenance.search_placeholder') }}" value="{{ request('search') }}">
                        </div>
                        <div>
                            <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">{{ __('facility.maintenance.all_statuses') }}</option>
                                @foreach(\App\Models\MaintenanceRequest::STATUSES as $status)
                                    <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>{{ __('facility.maintenance.statuses.' . $status) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <select name="type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">{{ __('facility.maintenance.all_types') }}</option>
                                @foreach(\App\Models\MaintenanceRequest::TYPES as $type)
                                    <option value="{{ $type }}" {{ request('type') === $type ? 'selected' : '' }}>{{ __('facility.maintenance.types.' . $type) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <select name="priority" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">{{ __('facility.maintenance.all_priorities') }}</option>
                                @foreach(\App\Models\MaintenanceRequest::PRIORITIES as $priority)
                                    <option value="{{ $priority }}" {{ request('priority') === $priority ? 'selected' : '' }}>{{ __('facility.maintenance.priorities.' . $priority) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">{{ __('facility.maintenance.search') }}</button>
                        </div>
                    </form>

                    @if($requests->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('facility.maintenance.request') }}</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('facility.maintenance.product') }}</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('facility.maintenance.type') }}</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('facility.maintenance.priority') }}</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('facility.maintenance.status') }}</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('facility.maintenance.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($requests as $req)
                                        @php
                                            $statusColors = ['open' => 'bg-yellow-50 text-yellow-700', 'in_progress' => 'bg-blue-50 text-blue-700', 'completed' => 'bg-emerald-50 text-emerald-700', 'cancelled' => 'bg-gray-100 text-gray-600'];
                                            $priorityColors = ['low' => 'bg-gray-100 text-gray-600', 'medium' => 'bg-blue-50 text-blue-700', 'high' => 'bg-orange-50 text-orange-700', 'urgent' => 'bg-red-50 text-red-700'];
                                        @endphp
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $req->title }}</div>
                                                <div class="text-xs text-gray-500">
                                                    {{ $req->assignee?->name ?? __('facility.maintenance.unassigned') }}
                                                    @if($req->scheduled_at) · {{ $req->scheduled_at->format('Y-m-d') }} @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-700">
                                                @if($req->product)
                                                    <a href="{{ route('facility.products.manage', $req->product) }}" class="text-blue-600 hover:underline">{{ $req->product->getTranslatedTitle() ?? ('#'.$req->product_id) }}</a>
                                                @else
                                                    —
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-700">{{ __('facility.maintenance.types.' . $req->type) }}</td>
                                            <td class="px-6 py-4">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $priorityColors[$req->priority] ?? 'bg-gray-100 text-gray-600' }}">
                                                    {{ __('facility.maintenance.priorities.' . $req->priority) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$req->status] ?? 'bg-gray-100 text-gray-600' }}">
                                                    {{ __('facility.maintenance.statuses.' . $req->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center space-x-2 rtl:space-x-reverse">
                                                    @if($req->status !== 'completed')
                                                        <form action="{{ route('facility.maintenance.update-status', $req) }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="status" value="completed">
                                                            <button type="submit" class="inline-flex items-center justify-center w-8 h-8 text-emerald-600 bg-emerald-50 hover:bg-emerald-100 rounded-lg" title="{{ __('facility.maintenance.mark_completed') }}">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                    <a href="{{ route('facility.maintenance.edit', $req) }}" class="inline-flex items-center justify-center w-8 h-8 text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg" title="{{ __('facility.maintenance.edit') }}">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('facility.maintenance.destroy', $req) }}" method="POST" onsubmit="return confirm('{{ __('facility.maintenance.delete_confirm') }}')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="inline-flex items-center justify-center w-8 h-8 text-red-600 bg-red-50 hover:bg-red-100 rounded-lg" title="{{ __('facility.maintenance.delete') }}">
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
                        <div class="mt-4">{{ $requests->links() }}</div>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-screwdriver-wrench text-gray-300 text-5xl mb-4"></i>
                            <p class="text-gray-500">{{ __('facility.maintenance.empty') }}</p>
                            <a href="{{ route('facility.maintenance.create') }}" class="inline-flex items-center mt-4 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                                <i class="fas fa-plus ml-2"></i>{{ __('facility.maintenance.add_new') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
