@extends('layouts.app')

@section('title', __('Pricing Plans'))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="text-center mb-5">
                <h1 class="display-4 text-primary">{{ __('Pricing Plans') }}</h1>
                <p class="lead text-muted">{{ __('Choose the perfect plan for your real estate needs') }}</p>
            </div>

            <div class="row">
                <div class="col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-header bg-light text-center py-4">
                            <h3 class="h4 text-primary">{{ __('Basic') }}</h3>
                            <div class="pricing-price">
                                <span class="display-6 text-primary">0</span>
                                <span class="text-muted"> ريال/{{ __('month') }}</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>{{ __('5 property listings') }}</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>{{ __('Basic search filters') }}</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>{{ __('Email support') }}</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>{{ __('Standard templates') }}</li>
                                <li class="mb-2 text-muted"><i class="fas fa-times text-muted me-2"></i>{{ __('Advanced analytics') }}</li>
                                <li class="mb-2 text-muted"><i class="fas fa-times text-muted me-2"></i>{{ __('Priority support') }}</li>
                            </ul>
                        </div>
                        <div class="card-footer text-center">
                            <a href="{{ route('register') }}" class="btn btn-outline-primary btn-lg w-100">{{ __('Get Started') }}</a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 mb-4">
                    <div class="card h-100 shadow-lg border-primary">
                        <div class="card-header bg-primary text-white text-center py-4">
                            <h3 class="h4">{{ __('Professional') }}</h3>
                            <div class="pricing-price">
                                <span class="display-6">29</span>
                                <span> ريال/{{ __('month') }}</span>
                            </div>
                            <span class="badge bg-warning text-dark">{{ __('Most Popular') }}</span>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>{{ __('Unlimited property listings') }}</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>{{ __('Advanced search filters') }}</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>{{ __('Priority email support') }}</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>{{ __('Premium templates') }}</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>{{ __('Basic analytics') }}</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>{{ __('Booking management') }}</li>
                            </ul>
                        </div>
                        <div class="card-footer text-center">
                            <a href="{{ route('register') }}" class="btn btn-primary btn-lg w-100">{{ __('Choose Plan') }}</a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-header bg-dark text-white text-center py-4">
                            <h3 class="h4">{{ __('Enterprise') }}</h3>
                            <div class="pricing-price">
                                <span class="display-6">99</span>
                                <span> ريال/{{ __('month') }}</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>{{ __('Everything in Professional') }}</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>{{ __('Advanced analytics dashboard') }}</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>{{ __('24/7 phone support') }}</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>{{ __('Custom branding') }}</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>{{ __('API access') }}</li>
                                <li class="mb-2"><i class="mb-2"><i class="fas fa-check text-success me-2"></i>{{ __('Dedicated account manager') }}</li>
                            </ul>
                        </div>
                        <div class="card-footer text-center">
                            <a href="{{ route('public.contact') }}" class="btn btn-outline-dark btn-lg w-100">{{ __('Contact Sales') }}</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-5">
                <div class="col-lg-8 mx-auto">
                    <div class="card shadow-sm">
                        <div class="card-header bg-info text-white">
                            <h2 class="h4 mb-0">{{ __('All Plans Include') }}</h2>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <ul class="list-unstyled">
                                        <li class="mb-2"><i class="fas fa-shield-alt text-info me-2"></i>{{ __('Secure hosting') }}</li>
                                        <li class="mb-2"><i class="fas fa-mobile-alt text-info me-2"></i>{{ __('Mobile responsive') }}</li>
                                        <li class="mb-2"><i class="fas fa-globe text-info me-2"></i>{{ __('Multi-language support') }}</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <ul class="list-unstyled">
                                        <li class="mb-2"><i class="fas fa-sync text-info me-2"></i>{{ __('Regular updates') }}</li>
                                        <li class="mb-2"><i class="fas fa-database text-info me-2"></i>{{ __('Data backup') }}</li>
                                        <li class="mb-2"><i class="fas fa-lock text-info me-2"></i>{{ __('SSL security') }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-5">
                <p class="text-muted">{{ __('Have questions about our pricing?') }}</p>
                <a href="{{ route('public.contact') }}" class="btn btn-outline-primary">{{ __('Contact Us') }}</a>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border: none;
    border-radius: 15px;
    transition: transform 0.2s;
}

.card:hover {
    transform: translateY(-5px);
}

.card-header {
    border-radius: 15px 15px 0 0 !important;
}

.pricing-price {
    margin: 1rem 0;
}

.badge {
    font-size: 0.8rem;
    padding: 0.5rem 1rem;
}

.text-primary {
    color: #007bff !important;
}

.bg-primary {
    background-color: #007bff !important;
}

.bg-info {
    background-color: #17a2b8 !important;
}

.bg-dark {
    background-color: #343a40 !important;
}
</style>
@endsection
