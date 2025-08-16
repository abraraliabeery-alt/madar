@extends('layouts.app')

@section('title', __('How It Works'))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="text-center mb-5">
                <h1 class="display-4 text-primary">{{ __('How It Works') }}</h1>
                <p class="lead text-muted">{{ __('Discover how our platform connects you with the best real estate opportunities') }}</p>
            </div>

            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm text-center">
                        <div class="card-body">
                            <div class="mb-3">
                                <i class="fas fa-search fa-3x text-primary"></i>
                            </div>
                            <h3 class="h5 text-primary">{{ __('1. Search & Discover') }}</h3>
                            <p class="text-muted">{{ __('Browse through our extensive collection of properties, facilities, and real estate opportunities. Use our advanced filters to find exactly what you\'re looking for.') }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm text-center">
                        <div class="card-body">
                            <div class="mb-3">
                                <i class="fas fa-heart fa-3x text-primary"></i>
                            </div>
                            <h3 class="h5 text-primary">{{ __('2. Save & Compare') }}</h3>
                            <p class="text-muted">{{ __('Save your favorite properties to your wishlist and compare different options side by side. Make informed decisions with all the information at your fingertips.') }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm text-center">
                        <div class="card-body">
                            <div class="mb-3">
                                <i class="fas fa-handshake fa-3x text-primary"></i>
                            </div>
                            <h3 class="h5 text-primary">{{ __('3. Connect & Book') }}</h3>
                            <p class="text-muted">{{ __('Connect directly with property owners and facility managers. Book appointments, schedule viewings, and take the next step towards your real estate goals.') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-5">
                <div class="col-lg-8 mx-auto">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h2 class="h4 mb-0">{{ __('For Property Seekers') }}</h2>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>{{ __('Access to verified property listings') }}</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>{{ __('Advanced search and filtering options') }}</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>{{ __('Direct communication with sellers') }}</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>{{ __('Secure booking and appointment scheduling') }}</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>{{ __('Detailed property information and photos') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-lg-8 mx-auto">
                    <div class="card shadow-sm">
                        <div class="card-header bg-success text-white">
                            <h2 class="h4 mb-0">{{ __('For Property Owners') }}</h2>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>{{ __('Easy property listing management') }}</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>{{ __('Reach qualified buyers and renters') }}</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>{{ __('Professional presentation tools') }}</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>{{ __('Booking and appointment management') }}</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>{{ __('Analytics and performance insights') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-5">
                <a href="{{ route('public.products.index') }}" class="btn btn-primary btn-lg me-3">{{ __('Browse Properties') }}</a>
                <a href="{{ route('public.facilities.featured') }}" class="btn btn-outline-primary btn-lg">{{ __('View Facilities') }}</a>
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

.text-primary {
    color: #007bff !important;
}

.text-success {
    color: #28a745 !important;
}

.bg-success {
    background-color: #28a745 !important;
}
</style>
@endsection
