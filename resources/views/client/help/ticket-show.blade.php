@extends('client.layouts.app')

@section('title', $ticket->subject)

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-3xl mx-auto px-4">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900">#{{ $ticket->id }} — {{ $ticket->subject }}</h1>
            <a href="{{ route('client.help.tickets') }}" class="text-sm text-blue-600 hover:underline">{{ __('client.back') }}</a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 text-green-800 rounded-lg p-4 mb-6">{{ session('success') }}</div>
        @endif

        <div class="bg-white rounded-lg shadow-sm p-5 mb-4">
            <p class="text-sm text-gray-500 mb-2">{{ $ticket->created_at->format('Y-m-d H:i') }} · {{ __('client.ticket_status_' . $ticket->status) }}</p>
            <p class="text-gray-800">{{ $ticket->message }}</p>
        </div>

        @foreach($ticket->replies as $reply)
            <div class="bg-white rounded-lg shadow-sm p-5 mb-4 {{ $reply->user_id === auth()->id() ? 'mr-8' : 'ml-8 border-r-4 border-blue-500' }}">
                <p class="text-sm text-gray-500 mb-2">{{ optional($reply->user)->name ?? '-' }} · {{ $reply->created_at->diffForHumans() }}</p>
                <p class="text-gray-800">{{ $reply->message }}</p>
            </div>
        @endforeach

        <div class="bg-white rounded-lg shadow-sm p-5">
            <form method="POST" action="{{ route('client.help.tickets.reply', $ticket) }}">
                @csrf
                <textarea name="message" rows="3" required class="w-full rounded-md border-gray-300 mb-3" placeholder="{{ __('client.write_reply') }}"></textarea>
                @error('message')<p class="text-red-600 text-sm mb-2">{{ $message }}</p>@enderror
                <button type="submit" class="bg-blue-600 text-white rounded-md px-5 py-2 text-sm hover:bg-blue-700">{{ __('client.send') }}</button>
            </form>
        </div>
    </div>
</div>
@endsection
