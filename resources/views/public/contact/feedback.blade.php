@extends('layouts.app')
@section('title', __('public.feedback'))
@section('content')
<div class="container py-5">
    <h1 class="mb-4 text-center">{{ __('public.feedback') }}</h1>
    <div class="row justify-content-center"><div class="col-lg-6">
        <form method="POST" action="{{ route('public.contact.feedback.send') }}" class="card p-4">
            @csrf
            <div class="mb-3"><input type="text" name="name" class="form-control" placeholder="{{ __('public.name') }}" required></div>
            <div class="mb-3"><input type="email" name="email" class="form-control" placeholder="{{ __('public.email') }}" required></div>
            <div class="mb-3"><select name="type" class="form-select">
                @foreach($feedbackTypes as $key => $label)<option value="{{ $key }}">{{ $label }}</option>@endforeach
            </select></div>
            <div class="mb-3"><textarea name="message" class="form-control" rows="4" placeholder="{{ __('public.message') }}" required></textarea></div>
            <button type="submit" class="btn btn-primary w-100">{{ __('public.send') }}</button>
        </form>
    </div></div>
</div>
@endsection
