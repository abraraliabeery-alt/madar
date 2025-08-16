@extends('layouts.app')

@section('title', __('Blog'))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="text-center mb-5">
                <h1 class="display-4 text-primary">{{ __('Real Estate Blog') }}</h1>
                <p class="lead text-muted">{{ __('Stay updated with the latest insights, tips, and trends in real estate') }}</p>
            </div>

            <!-- Featured Post -->
            <div class="row mb-5">
                <div class="col-lg-8 mx-auto">
                    <div class="card shadow-lg">
                        <div class="card-body p-0">
                            <div class="position-relative">
                                <div class="bg-gradient-to-br from-primary-500 to-primary-700 h-64 flex items-center justify-center">
                                    <i class="fas fa-newspaper text-6xl text-white opacity-80"></i>
                                </div>
                                <div class="position-absolute top-4 right-4">
                                    <span class="badge bg-warning text-dark px-3 py-2">{{ __('Featured') }}</span>
                                </div>
                            </div>
                            <div class="p-5">
                                <div class="d-flex align-items-center mb-3">
                                    <span class="text-muted me-3">
                                        <i class="fas fa-calendar me-1"></i>
                                        {{ __('January 15, 2025') }}
                                    </span>
                                    <span class="text-muted me-3">
                                        <i class="fas fa-user me-1"></i>
                                        {{ __('Real Estate Team') }}
                                    </span>
                                    <span class="text-muted">
                                        <i class="fas fa-folder me-1"></i>
                                        {{ __('Market Trends') }}
                                    </span>
                                </div>
                                <h2 class="h3 mb-3">{{ __('2025 Real Estate Market Outlook: What to Expect') }}</h2>
                                <p class="text-muted mb-4">
                                    {{ __('Discover the key trends and predictions that will shape the real estate market in 2025. From emerging neighborhoods to investment opportunities, we\'ll guide you through what to expect in the coming year.') }}
                                </p>
                                <a href="#" class="btn btn-primary">{{ __('Read More') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Blog Posts Grid -->
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <div class="bg-light rounded p-3 text-center">
                                    <i class="fas fa-home text-4xl text-primary"></i>
                                </div>
                            </div>
                            <div class="mb-3">
                                <span class="badge bg-info text-white me-2">{{ __('Buying Guide') }}</span>
                                <span class="text-muted small">{{ __('January 10, 2025') }}</span>
                            </div>
                            <h5 class="card-title mb-3">{{ __('First-Time Homebuyer Checklist') }}</h5>
                            <p class="card-text text-muted">
                                {{ __('Essential steps and considerations for first-time homebuyers. From saving for a down payment to closing the deal, we\'ll walk you through the entire process.') }}
                            </p>
                            <a href="#" class="btn btn-outline-primary">{{ __('Read More') }}</a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <div class="bg-light rounded p-3 text-center">
                                    <i class="fas fa-chart-line text-4xl text-success"></i>
                                </div>
                            </div>
                            <div class="mb-3">
                                <span class="badge bg-success text-white me-2">{{ __('Investment') }}</span>
                                <span class="text-muted small">{{ __('January 8, 2025') }}</span>
                            </div>
                            <h5 class="card-title mb-3">{{ __('Top Investment Properties in 2025') }}</h5>
                            <p class="card-text text-muted">
                                {{ __('Discover the most promising investment opportunities in the current market. We\'ll analyze different property types and locations to help you make informed decisions.') }}
                            </p>
                            <a href="#" class="btn btn-outline-primary">{{ __('Read More') }}</a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <div class="bg-light rounded p-3 text-center">
                                    <i class="fas fa-tools text-4xl text-warning"></i>
                                </div>
                            </div>
                            <div class="mb-3">
                                <span class="badge bg-warning text-dark me-2">{{ __('Maintenance') }}</span>
                                <span class="text-muted small">{{ __('January 5, 2025') }}</span>
                            </div>
                            <h5 class="card-title mb-3">{{ __('Home Maintenance Tips for Winter') }}</h5>
                            <p class="card-text text-muted">
                                {{ __('Protect your home during the winter months with these essential maintenance tips. From heating systems to insulation, ensure your property stays in top condition.') }}
                            </p>
                            <a href="#" class="btn btn-outline-primary">{{ __('Read More') }}</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- More Blog Posts -->
            <div class="row mt-4">
                <div class="col-lg-6 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-start">
                                <div class="bg-light rounded p-3 me-3">
                                    <i class="fas fa-map-marker-alt text-2xl text-danger"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="mb-2">
                                        <span class="badge bg-secondary text-white me-2">{{ __('Location') }}</span>
                                        <span class="text-muted small">{{ __('January 3, 2025') }}</span>
                                    </div>
                                    <h5 class="card-title mb-2">{{ __('Emerging Neighborhoods to Watch') }}</h5>
                                    <p class="card-text text-muted">
                                        {{ __('Explore up-and-coming neighborhoods that offer great value and potential for growth. These areas might be your next investment opportunity.') }}
                                    </p>
                                    <a href="#" class="btn btn-outline-primary btn-sm">{{ __('Read More') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-start">
                                <div class="bg-light rounded p-3 me-3">
                                    <i class="fas fa-calculator text-2xl text-info"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="mb-2">
                                        <span class="badge bg-info text-white me-2">{{ __('Finance') }}</span>
                                        <span class="text-muted small">{{ __('January 1, 2025') }}</span>
                                    </div>
                                    <h5 class="card-title mb-2">{{ __('Mortgage Options for 2025') }}</h5>
                                    <p class="card-text text-muted">
                                        {{ __('Understanding the different mortgage options available in 2025. From conventional loans to government programs, find the best financing solution for your needs.') }}
                                    </p>
                                    <a href="#" class="btn btn-outline-primary btn-sm">{{ __('Read More') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Newsletter Signup -->
            <div class="row mt-5">
                <div class="col-lg-8 mx-auto">
                    <div class="card bg-primary text-white text-center p-5">
                        <h3 class="mb-3">{{ __('Stay Updated') }}</h3>
                        <p class="mb-4">{{ __('Subscribe to our newsletter for the latest real estate insights and exclusive content.') }}</p>
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <input type="email" class="form-control" placeholder="{{ __('Enter your email address') }}" aria-label="{{ __('Email address') }}">
                                    <button class="btn btn-light" type="button">{{ __('Subscribe') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div class="text-center mt-5">
                <nav aria-label="Blog pagination">
                    <ul class="pagination justify-content-center">
                        <li class="page-item disabled">
                            <span class="page-link">{{ __('Previous') }}</span>
                        </li>
                        <li class="page-item active"><span class="page-link">1</span></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">{{ __('Next') }}</a>
                        </li>
                    </ul>
                </nav>
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

.text-primary {
    color: #007bff !important;
}

.bg-primary {
    background-color: #007bff !important;
}

.bg-light {
    background-color: #f8f9fa !important;
}

.h-64 {
    height: 16rem;
}

.text-4xl {
    font-size: 2.25rem;
}

.text-2xl {
    font-size: 1.5rem;
}
</style>
@endsection
