@extends('layouts.app')

@section('title', __('Testimonials'))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="text-center mb-5">
                <h1 class="display-4 text-primary">{{ __('What Our Clients Say') }}</h1>
                <p class="lead text-muted">{{ __('Discover why thousands of clients trust us with their real estate needs') }}</p>
            </div>

            <!-- Featured Testimonial -->
            <div class="row mb-5">
                <div class="col-lg-8 mx-auto">
                    <div class="card shadow-lg border-primary">
                        <div class="card-body text-center p-5">
                            <div class="mb-4">
                                <i class="fas fa-quote-left fa-3x text-primary opacity-25"></i>
                            </div>
                            <p class="lead text-muted mb-4">
                                "{{ __('The platform exceeded all my expectations. I found my dream home within weeks, and the entire process was smooth and professional. Highly recommended!') }}"
                            </p>
                            <div class="d-flex align-items-center justify-content-center">
                                <div class="avatar me-3">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                        <i class="fas fa-user fa-2x"></i>
                                    </div>
                                </div>
                                <div class="text-start">
                                    <h5 class="mb-1">{{ __('Ahmed Al-Rashid') }}</h5>
                                    <p class="text-muted mb-0">{{ __('Property Buyer') }}</p>
                                    <div class="text-warning">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Testimonials Grid -->
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <div class="text-warning">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                            <p class="text-muted mb-4">
                                "{{ __('Excellent service from start to finish. The team was professional, responsive, and helped me find the perfect investment property.') }}"
                            </p>
                            <div class="d-flex align-items-center">
                                <div class="avatar me-3">
                                    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                        <i class="fas fa-user"></i>
                                    </div>
                                </div>
                                <div>
                                    <h6 class="mb-1">{{ __('Sarah Johnson') }}</h6>
                                    <p class="text-muted mb-0">{{ __('Investor') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <div class="text-warning">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                            <p class="text-muted mb-4">
                                "{{ __('As a property owner, this platform has helped me reach more potential tenants and manage my properties efficiently. Great ROI!') }}"
                            </p>
                            <div class="d-flex align-items-center">
                                <div class="avatar me-3">
                                    <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                        <i class="fas fa-user"></i>
                                    </div>
                                </div>
                                <div>
                                    <h6 class="mb-1">{{ __('Mohammed Al-Zahrani') }}</h6>
                                    <p class="text-muted mb-0">{{ __('Property Owner') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <div class="text-warning">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                            <p class="text-muted mb-4">
                                "{{ __('The search filters and detailed property information made it easy to find exactly what I was looking for. Saved me hours of research!') }}"
                            </p>
                            <div class="d-flex align-items-center">
                                <div class="avatar me-3">
                                    <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                        <i class="fas fa-user"></i>
                                    </div>
                                </div>
                                <div>
                                    <h6 class="mb-1">{{ __('Fatima Al-Qahtani') }}</h6>
                                    <p class="text-muted mb-0">{{ __('First-time Buyer') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- More Testimonials -->
            <div class="row mt-4">
                <div class="col-lg-6 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <div class="text-warning">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="far fa-star"></i>
                                </div>
                            </div>
                            <p class="text-muted mb-3">
                                "{{ __('Professional team with deep market knowledge. They helped me navigate the complex real estate market and make informed decisions.') }}"
                            </p>
                            <div class="d-flex align-items-center">
                                <div class="avatar me-3">
                                    <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                                        <i class="fas fa-user"></i>
                                    </div>
                                </div>
                                <div>
                                    <h6 class="mb-1">{{ __('Omar Al-Shehri') }}</h6>
                                    <p class="text-muted mb-0">{{ __('Business Owner') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <div class="text-warning">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                            <p class="text-muted mb-3">
                                "{{ __('Outstanding customer support and transparent pricing. No hidden fees, and they always put the client\'s interests first.') }}"
                            </p>
                            <div class="d-flex align-items-center">
                                <div class="avatar me-3">
                                    <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                                        <i class="fas fa-user"></i>
                                    </div>
                                </div>
                                <div>
                                    <h6 class="mb-1">{{ __('Layla Al-Mansouri') }}</h6>
                                    <p class="text-muted mb-0">{{ __('Property Seller') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Call to Action -->
            <div class="text-center mt-5">
                <div class="card bg-primary text-white p-5">
                    <h3 class="mb-3">{{ __('Ready to Experience Our Service?') }}</h3>
                    <p class="mb-4">{{ __('Join thousands of satisfied clients who have found their perfect property through our platform.') }}</p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('public.products.index') }}" class="btn btn-light btn-lg">{{ __('Browse Properties') }}</a>
                        <a href="{{ route('public.contact') }}" class="btn btn-outline-light btn-lg">{{ __('Contact Us') }}</a>
                    </div>
                </div>
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

.border-primary {
    border-color: #007bff !important;
}

.text-primary {
    color: #007bff !important;
}

.bg-primary {
    background-color: #007bff !important;
}

.avatar {
    flex-shrink: 0;
}

.opacity-25 {
    opacity: 0.25;
}
</style>
@endsection
