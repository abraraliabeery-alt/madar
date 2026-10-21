@extends('layouts.app')
@section('title', __('public.faq'))
@section('content')
<div class="container py-5">
    <h1 class="mb-4 text-center">{{ __('public.faq') }}</h1>
    <div class="accordion" id="faqAccordion">
        @foreach($faqs as $i => $faq)
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button {{ $i ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#faq-{{ $i }}">
                    {{ $faq['question'] }}
                </button>
            </h2>
            <div id="faq-{{ $i }}" class="accordion-collapse collapse {{ $i ? '' : 'show' }}" data-bs-parent="#faqAccordion">
                <div class="accordion-body">{{ $faq['answer'] }}</div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
