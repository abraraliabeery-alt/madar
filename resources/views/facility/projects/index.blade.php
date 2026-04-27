@extends('facility.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">
            {{ __('facility.projects') }}
        </h1>
    </div>

    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('facility.project_name') }}</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('facility.lifecycle') }}</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('facility.actions') }}</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($projects as $project)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $project->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ optional($project->translations->firstWhere('locale', app()->getLocale()))->name ?? ('#'.$project->id) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @php
                                $completed = $project->stages->where('status', 'completed')->count();
                                $total = max($project->stages->count(), 1);
                                $percent = round(($completed / $total) * 100);
                            @endphp
                            <div class="w-full bg-gray-100 rounded-full h-2.5">
                                <div class="bg-emerald-500 h-2.5 rounded-full" style="width: {{ $percent }}%"></div>
                            </div>
                            <div class="mt-1 text-xs text-gray-500">{{ $percent }}%</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <a href="{{ route('facility.projects.show', $project) }}" class="text-indigo-600 hover:text-indigo-900 ml-3">
                                {{ __('facility.view') }}
                            </a>
                            <a href="{{ route('facility.projects.lifecycle', $project) }}" class="text-emerald-600 hover:text-emerald-900 ml-3">
                                {{ __('facility.view_lifecycle') }}
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                            {{ __('facility.no_projects_found') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="px-6 py-4 border-t border-gray-100">
            {{ $projects->links() }}
        </div>
    </div>
</div>
@endsection
