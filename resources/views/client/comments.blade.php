@extends('client.layouts.app')

@section('title', __('client.my_comments'))

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-4xl mx-auto px-4">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900">{{ __('client.my_comments') }}</h1>
            <div class="flex gap-2 text-sm">
                <a href="{{ route('client.comments') }}" class="px-3 py-1 rounded-md {{ request()->routeIs('client.comments') ? 'bg-blue-600 text-white' : 'bg-white text-gray-700' }}">{{ __('client.all') }}</a>
                <a href="{{ route('client.comments.products') }}" class="px-3 py-1 rounded-md {{ request()->routeIs('client.comments.products') ? 'bg-blue-600 text-white' : 'bg-white text-gray-700' }}">{{ __('client.products') }}</a>
                <a href="{{ route('client.comments.facilities') }}" class="px-3 py-1 rounded-md {{ request()->routeIs('client.comments.facilities') ? 'bg-blue-600 text-white' : 'bg-white text-gray-700' }}">{{ __('client.facilities') }}</a>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 text-green-800 rounded-lg p-4 mb-6">{{ session('success') }}</div>
        @endif

        <div class="bg-white rounded-lg shadow-sm divide-y">
            @forelse($comments as $comment)
                <div class="p-4">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="font-medium text-gray-900">
                                {{ optional($comment->product)->getTranslatedTitle() ?: optional($comment->facility)->name ?? '-' }}
                            </p>
                            <p class="text-gray-600 text-sm mt-1">{{ $comment->comment }}</p>
                            <p class="text-gray-400 text-xs mt-2">{{ $comment->created_at->diffForHumans() }}
                                @if($comment->rating) · {{ str_repeat('★', $comment->rating) }}@endif
                            </p>
                        </div>
                        <form method="POST" action="{{ route('client.comments.delete', $comment) }}" onsubmit="return confirm('{{ __('client.confirm_delete') }}')">
                            @csrf @method('DELETE')
                            <button class="text-red-600 text-sm hover:underline">{{ __('client.delete') }}</button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="p-8 text-center text-gray-500">{{ __('client.no_comments') }}</p>
            @endforelse
        </div>

        <div class="mt-6">{{ $comments->links() }}</div>
    </div>
</div>
@endsection
