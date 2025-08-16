@extends('layouts.app')

@section('title', __('News Article'))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('news') }}">{{ __('News') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('News Article') }}</li>
                </ol>
            </nav>

            <!-- News Article Header -->
            <div class="mb-5">
                <div class="bg-success text-white rounded p-4 mb-4">
                    <div class="d-flex align-items-center mb-3">
                        <span class="me-3">
                            <i class="fas fa-calendar me-1"></i>
                            {{ __('January 20, 2025') }}
                        </span>
                        <span class="me-3">
                            <i class="fas fa-clock me-1"></i>
                            {{ __('2 hours ago') }}
                        </span>
                        <span class="badge bg-warning text-dark">
                            {{ __('Breaking News') }}
                        </span>
                    </div>
                    <h1 class="display-5 mb-3">{{ __('New Real Estate Regulations Announced for 2025') }}</h1>
                    <p class="lead mb-0">{{ __('Comprehensive changes to impact buyers, sellers, and investors starting March 2025.') }}</p>
                </div>
            </div>

            <!-- News Article Content -->
            <div class="card shadow-sm mb-5">
                <div class="card-body p-5">
                    <!-- Featured Image Placeholder -->
                    <div class="bg-light rounded mb-4 text-center py-5">
                        <i class="fas fa-newspaper text-4xl text-muted"></i>
                        <p class="text-muted mt-2">{{ __('Featured Image') }}</p>
                    </div>

                    <!-- Article Content -->
                    <div class="article-content">
                        <p class="lead mb-4">
                            {{ __('The government has announced comprehensive new regulations that will significantly impact the real estate market starting from March 2025. These changes aim to improve market transparency, protect consumer rights, and ensure sustainable growth in the sector.') }}
                        </p>

                        <h2 class="h3 text-success mb-3">{{ __('Key Regulatory Changes') }}</h2>
                        <p class="mb-4">
                            {{ __('The new regulations introduce several important changes that will affect all stakeholders in the real estate market. These include enhanced disclosure requirements, stricter licensing standards, and improved consumer protection measures.') }}
                        </p>

                        <h3 class="h4 text-primary mb-3">{{ __('1. Enhanced Disclosure Requirements') }}</h3>
                        <p class="mb-4">
                            {{ __('Property sellers and agents will now be required to provide more comprehensive information about properties, including detailed condition reports, energy efficiency ratings, and any known issues or defects. This aims to give buyers more confidence in their purchasing decisions.') }}
                        </p>

                        <h3 class="h4 text-primary mb-3">{{ __('2. Stricter Licensing Standards') }}</h3>
                        <p class="mb-4">
                            {{ __('Real estate professionals will face more rigorous licensing requirements, including mandatory continuing education, background checks, and adherence to stricter ethical standards. This will help improve the overall quality of service in the industry.') }}
                        </p>

                        <h3 class="h4 text-primary mb-3">{{ __('3. Consumer Protection Measures') }}</h3>
                        <p class="mb-4">
                            {{ __('New measures include mandatory cooling-off periods for property purchases, improved dispute resolution processes, and enhanced protection against fraudulent practices. These changes will provide buyers with greater security and recourse options.') }}
                        </p>

                        <h3 class="h4 text-primary mb-3">{{ __('4. Market Transparency Improvements') }}</h3>
                        <p class="mb-4">
                            {{ __('The regulations introduce requirements for more detailed market reporting, including price trends, transaction volumes, and market analysis. This will help all participants make more informed decisions.') }}
                        </p>

                        <div class="alert alert-success">
                            <h4 class="h5">{{ __('Benefits of New Regulations') }}</h4>
                            <ul class="mb-0">
                                <li>{{ __('Increased market transparency') }}</li>
                                <li>{{ __('Better consumer protection') }}</li>
                                <li>{{ __('Improved professional standards') }}</li>
                                <li>{{ __('Enhanced market stability') }}</li>
                            </ul>
                        </div>

                        <h2 class="h3 text-success mb-3">{{ __('Implementation Timeline') }}</h2>
                        <p class="mb-4">
                            {{ __('The new regulations will be implemented in phases to allow the industry time to adapt. The first phase begins in March 2025, with full implementation expected by the end of the year.') }}
                        </p>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card border-success">
                                    <div class="card-body text-center">
                                        <h5 class="text-success">{{ __('Phase 1') }}</h5>
                                        <p class="mb-0">{{ __('March 2025 - Enhanced disclosures') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-success">
                                    <div class="card-body text-center">
                                        <h5 class="text-success">{{ __('Phase 2') }}</h5>
                                        <p class="mb-0">{{ __('June 2025 - Licensing standards') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h2 class="h3 text-success mb-3">{{ __('Industry Response') }}</h2>
                        <p class="mb-4">
                            {{ __('Industry leaders have generally welcomed these changes, recognizing that they will help improve market confidence and professional standards. However, some concerns have been raised about the implementation timeline and compliance costs.') }}
                        </p>

                        <blockquote class="blockquote bg-light p-4 rounded">
                            <p class="mb-0">{{ __('"These regulations represent a significant step forward for the real estate industry. While there will be adjustment challenges, the long-term benefits for consumers and market stability are substantial."') }}</p>
                            <footer class="blockquote-footer mt-2">{{ __('Real Estate Industry Association') }}</footer>
                        </blockquote>

                        <h2 class="h3 text-success mb-3">{{ __('What This Means for You') }}</h2>
                        <p class="mb-4">
                            {{ __('Whether you\'re buying, selling, or investing in real estate, these changes will affect your experience. Buyers will have access to more information and better protection, while sellers and agents will need to meet higher standards.') }}
                        </p>

                        <p class="mb-0">
                            {{ __('We recommend staying informed about these changes and consulting with qualified real estate professionals who are up-to-date with the new requirements. Our team is committed to helping you navigate these changes successfully.') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Author Information -->
            <div class="card shadow-sm mb-5">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                            <i class="fas fa-user fa-2x"></i>
                        </div>
                        <div>
                            <h5 class="mb-1">{{ __('Real Estate News Team') }}</h5>
                            <p class="text-muted mb-2">{{ __('Dedicated to bringing you the latest updates and insights from the real estate industry.') }}</p>
                            <div class="d-flex gap-2">
                                <a href="#" class="text-success"><i class="fab fa-linkedin"></i></a>
                                <a href="#" class="text-success"><i class="fab fa-twitter"></i></a>
                                <a href="#" class="text-success"><i class="fas fa-envelope"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related News -->
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h3 class="h5 mb-0">{{ __('Related News') }}</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="d-flex">
                                <div class="bg-light rounded me-3" style="width: 80px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-chart-line text-muted"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">{{ __('Market Growth Report') }}</h6>
                                    <small class="text-muted">{{ __('Q4 2024 analysis') }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex">
                                <div class="bg-light rounded me-3" style="width: 80px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-building text-muted"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">{{ __('New Development Projects') }}</h6>
                                    <small class="text-muted">{{ __('Smart city initiatives') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Search Widget -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title">{{ __('Search News') }}</h5>
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="{{ __('Search...') }}" aria-label="{{ __('Search news') }}">
                        <button class="btn btn-success" type="button"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </div>

            <!-- Categories Widget -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">{{ __('News Categories') }}</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><a href="#" class="text-decoration-none text-muted">{{ __('Market Updates') }}</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-muted">{{ __('Policy Changes') }}</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-muted">{{ __('Investment') }}</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-muted">{{ __('Development') }}</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-muted">{{ __('Technology') }}</a></li>
                        <li><a href="#" class="text-decoration-none text-muted">{{ __('Sustainability') }}</a></li>
                    </ul>
                </div>
            </div>

            <!-- Recent News Widget -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">{{ __('Recent News') }}</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="mb-1">{{ __('Property Price Growth') }}</h6>
                        <small class="text-muted">{{ __('January 18, 2025') }}</small>
                    </div>
                    <div class="mb-3">
                        <h6 class="mb-1">{{ __('Smart City Launch') }}</h6>
                        <small class="text-muted">{{ __('January 17, 2025') }}</small>
                    </div>
                    <div>
                        <h6 class="mb-1">{{ __('Consumer Protection Laws') }}</h6>
                        <small class="text-muted">{{ __('January 16, 2025') }}</small>
                    </div>
                </div>
            </div>

            <!-- Newsletter Signup -->
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">{{ __('Stay Informed') }}</h5>
                    <p class="card-text text-muted">{{ __('Get the latest real estate news delivered to your inbox.') }}</p>
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" placeholder="{{ __('Email address') }}" aria-label="{{ __('Email address') }}">
                        <button class="btn btn-success" type="button">{{ __('Subscribe') }}</button>
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
}

.text-success {
    color: #28a745 !important;
}

.bg-success {
    background-color: #28a745 !important;
}

.bg-light {
    background-color: #f8f9fa !important;
}

.article-content h2, .article-content h3 {
    margin-top: 2rem;
}

.article-content p {
    line-height: 1.8;
}

.alert {
    border-radius: 10px;
}

.blockquote {
    border-left: 4px solid #28a745;
}
</style>
@endsection
