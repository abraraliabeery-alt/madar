@extends('client.layouts.app')

@section('title', __('client.my_tickets'))

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-4xl mx-auto px-4">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900">{{ __('client.my_tickets') }}</h1>
            <a href="{{ route('client.help.tickets.create') }}" class="bg-blue-600 text-white rounded-md px-4 py-2 text-sm hover:bg-blue-700">{{ __('client.new_ticket') }}</a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 text-green-800 rounded-lg p-4 mb-6">{{ session('success') }}</div>
        @endif

        <div class="bg-white rounded-lg shadow-sm divide-y">
            @forelse($tickets as $ticket)
                <a href="{{ route('client.help.tickets.show', $ticket) }}" class="block p-4 hover:bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-gray-900">{{ $ticket->subject }}</p>
                            <p class="text-sm text-gray-500 mt-1">#{{ $ticket->id }} · {{ $ticket->created_at->diffForHumans() }} · {{ $ticket->replies_count }} {{ __('client.replies') }}</p>
                        </div>
                        <span class="text-xs px-2 py-1 rounded-full {{ $ticket->status === 'open' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                            {{ __('client.ticket_status_' . $ticket->status) }}
                        </span>
                    </div>
                </a>
            @empty
                <p class="p-8 text-center text-gray-500">{{ __('client.no_tickets') }}</p>
            @endforelse
        </div>

        <div class="mt-6">{{ $tickets->links() }}</div>
    </div>
</div>
@endsection
