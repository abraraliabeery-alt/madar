@extends('layouts.app')

@section('title', __('News'))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="text-center mb-5">
                <h1 class="display-4 text-primary">{{ __('Real Estate News') }}</h1>
                <p class="lead text-muted">{{ __('Latest updates and breaking news from the real estate industry') }}</p>
            </div>

            <!-- Featured News -->
            <div class="row mb-5">
                <div class="col-lg-8 mx-auto">
                    <div class="card shadow-lg">
                        <div class="card-body p-0">
                            <div class="position-relative">
                                <div class="bg-gradient-to-br from-success-500 to-success-700 h-64 flex items-center justify-center">
                                    <i class="fas fa-newspaper text-6xl text-white opacity-80"></i>
                                </div>
                                <div class="position-absolute top-4 right-4">
                                    <span class="badge bg-danger text-white px-3 py-2">{{ __('Breaking') }}</span>
                                </div>
                            </div>
                            <div class="p-5">
                                <div class="d-flex align-items-center mb-3">
                                    <span class="text-muted me-3">
                                        <i class="fas fa-calendar me-1"></i>
                                        {{ __('January 20, 2025') }}
                                    </span>
                                    <span class="text-muted me-3">
                                        <i class="fas fa-clock me-1"></i>
                                        {{ __('2 hours ago') }}
                                    </span>
                                    <span class="text-muted">
                                        <i class="fas fa-folder me-1"></i>
                                        {{ __('Market News') }}
                                    </span>
                                </div>
                                <h2 class="h3 mb-3">{{ __('New Real Estate Regulations Announced for 2025') }}</h2>
                                <p class="text-muted mb-4">
                                    {{ __('The government has announced comprehensive new regulations that will impact property buyers, sellers, and investors starting from March 2025. These changes aim to improve market transparency and protect consumer rights.') }}
                                </p>
                                <a href="#" class="btn btn-success">{{ __('Read Full Article') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- News Categories -->
            <div class="row mb-5">
                <div class="col-12">
                    <div class="d-flex flex-wrap justify-content-center gap-2">
                        <a href="#" class="btn btn-outline-primary active">{{ __('All News') }}</a>
                        <a href="#" class="btn btn-outline-primary">{{ __('Market Updates') }}</a>
                        <a href="#" class="btn btn-outline-primary">{{ __('Policy Changes') }}</a>
                        <a href="#" class="btn btn-outline-primary">{{ __('Investment') }}</a>
                        <a href="#" class="btn btn-outline-primary">{{ __('Development') }}</a>
                        <a href="#" class="btn btn-outline-primary">{{ __('Technology') }}</a>
                    </div>
                </div>
            </div>

            <!-- News Grid -->
            <div class="row">
                <div class="col-lg-6 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-start mb-3">
                                <div class="bg-primary text-white rounded p-2 me-3">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="badge bg-primary text-white me-2">{{ __('Market Update') }}</span>
                                    <span class="text-muted small">{{ __('January 18, 2025') }}</span>
                                </div>
                            </div>
                            <h5 class="card-title mb-3">{{ __('Property Prices Show Strong Growth in Q4 2024') }}</h5>
                            <p class="card-text text-muted">
                                {{ __('The latest market report reveals a 12% increase in property prices across major cities, with residential properties leading the growth. Experts predict continued momentum in 2025.') }}
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="#" class="btn btn-outline-primary btn-sm">{{ __('Read More') }}</a>
                                <small class="text-muted">{{ __('5 min read') }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-start mb-3">
                                <div class="bg-success text-white rounded p-2 me-3">
                                    <i class="fas fa-building"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="badge bg-success text-white me-2">{{ __('Development') }}</span>
                                    <span class="text-muted small">{{ __('January 17, 2025') }}</span>
                                </div>
                            </div>
                            <h5 class="card-title mb-3">{{ __('New Smart City Project Launched in Riyadh') }}</h5>
                            <p class="card-text text-muted">
                                {{ __('A groundbreaking smart city development has been announced, featuring sustainable infrastructure, smart homes, and integrated technology systems. The project is expected to create thousands of jobs.') }}
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="#" class="btn btn-outline-primary btn-sm">{{ __('Read More') }}</a>
                                <small class="text-muted">{{ __('7 min read') }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-start mb-3">
                                <div class="bg-warning text-dark rounded p-2 me-3">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="badge bg-warning text-dark me-2">{{ __('Policy') }}</span>
                                    <span class="text-muted small">{{ __('January 16, 2025') }}</span>
                                </div>
                            </div>
                            <h5 class="card-title mb-3">{{ __('Enhanced Consumer Protection Laws for Real Estate') }}</h5>
                            <p class="card-text text-muted">
                                {{ __('New consumer protection measures have been introduced to safeguard property buyers and tenants. These laws include stricter regulations on property disclosures and improved dispute resolution processes.') }}
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="#" class="btn btn-outline-primary btn-sm">{{ __('Read More') }}</a>
                                <small class="text-muted">{{ __('6 min read') }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-start mb-3">
                                <div class="bg-info text-white rounded p-2 me-3">
                                    <i class="fas fa-robot"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="badge bg-info text-white me-2">{{ __('Technology') }}</span>
                                    <span class="text-muted small">{{ __('January 15, 2025') }}</span>
                                </div>
                            </div>
                            <h5 class="card-title mb-3">{{ __('AI-Powered Property Valuation System Introduced') }}</h5>
                            <p class="card-text text-muted">
                                {{ __('Leading real estate companies are adopting artificial intelligence for property valuations. This technology provides more accurate pricing models and faster assessment processes.') }}
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="#" class="btn btn-outline-primary btn-sm">{{ __('Read More') }}</a>
                                <small class="text-muted">{{ __('4 min read') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- More News Articles -->
            <div class="row mt-4">
                <div class="col-lg-4 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <span class="badge bg-secondary text-white me-2">{{ __('Investment') }}</span>
                                <span class="text-muted small">{{ __('January 14, 2025') }}</span>
                            </div>
                            <h6 class="card-title mb-2">{{ __('Foreign Investment in Real Estate Reaches Record High') }}</h6>
                            <p class="card-text text-muted small">
                                {{ __('International investors are showing unprecedented interest in the local real estate market, with foreign direct investment reaching $2.5 billion in 2024.') }}
                            </p>
                            <a href="#" class="btn btn-outline-primary btn-sm">{{ __('Read More') }}</a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <span class="badge bg-danger text-white me-2">{{ __('Market Alert') }}</span>
                                <span class="text-muted small">{{ __('January 13, 2025') }}</span>
                            </div>
                            <h6 class="card-title mb-2">{{ __('Interest Rate Changes Impact Mortgage Market') }}</h6>
                            <p class="card-text text-muted small">
                                {{ __('Recent interest rate adjustments are affecting mortgage affordability and refinancing options. Experts advise borrowers to review their current mortgage terms.') }}
                            </p>
                            <a href="#" class="btn btn-outline-primary btn-sm">{{ __('Read More') }}</a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <span class="badge bg-success text-white me-2">{{ __('Sustainability') }}</span>
                                <span class="text-muted small">{{ __('January 12, 2025') }}</span>
                            </div>
                            <h6 class="card-title mb-2">{{ __('Green Building Standards Updated for 2025') }}</h6>
                            <p class="card-text text-muted small">
                                {{ __('New environmental standards for construction have been implemented, promoting energy efficiency and sustainable building practices across the industry.') }}
                            </p>
                            <a href="#" class="btn btn-outline-primary btn-sm">{{ __('Read More') }}</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Newsletter Signup -->
            <div class="row mt-5">
                <div class="col-lg-8 mx-auto">
                    <div class="card bg-success text-white text-center p-5">
                        <h3 class="mb-3">{{ __('Stay Informed') }}</h3>
                        <p class="mb-4">{{ __('Get the latest real estate news and market updates delivered to your inbox.') }}</p>
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
                <nav aria-label="News pagination">
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

.bg-success {
    background-color: #28a745 !important;
}

.h-64 {
    height: 16rem;
}

.btn-outline-primary:hover {
    background-color: #007bff;
    border-color: #007bff;
}
</style>
@endsection
