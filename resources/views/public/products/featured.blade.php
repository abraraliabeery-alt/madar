@extends('layouts.app')
@section('title', __('public.featured_products'))
@section('content')
<div class="container py-5">
    <h1 class="mb-4">{{ __('public.featured_products') }}</h1>
    <div class="row g-4">
        @foreach($products as $product)
        <div class="col-md-3"><a href="{{ route('public.products.show', $product->id) }}" class="text-decoration-none"><div class="card h-100"><div class="card-body">
            <h6 class="text-dark">{{ $product->getTranslatedTitle() ?? '#'.$product->id }}</h6>
            <p class="text-muted small">{{ $product->facility?->name ?? '' }}</p>
        </div></div></a></div>
        @endforeach
    </div>
    <div class="mt-4">{{ $products->links() }}</div>
</div>
@endsection
