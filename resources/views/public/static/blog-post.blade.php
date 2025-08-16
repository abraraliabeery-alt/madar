@extends('layouts.app')

@section('title', __('Blog Post'))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('blog') }}">{{ __('Blog') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('Blog Post') }}</li>
                </ol>
            </nav>

            <!-- Blog Post Header -->
            <div class="mb-5">
                <div class="bg-light rounded p-4 mb-4">
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
                    <h1 class="display-5 text-primary mb-3">{{ __('2025 Real Estate Market Outlook: What to Expect') }}</h1>
                    <p class="lead text-muted">{{ __('Discover the key trends and predictions that will shape the real estate market in 2025.') }}</p>
                </div>
            </div>

            <!-- Blog Post Content -->
            <div class="card shadow-sm mb-5">
                <div class="card-body p-5">
                    <!-- Featured Image Placeholder -->
                    <div class="bg-light rounded mb-4 text-center py-5">
                        <i class="fas fa-image text-4xl text-muted"></i>
                        <p class="text-muted mt-2">{{ __('Featured Image') }}</p>
                    </div>

                    <!-- Article Content -->
                    <div class="article-content">
                        <p class="lead mb-4">
                            {{ __('The real estate market is constantly evolving, and 2025 promises to bring significant changes that will impact buyers, sellers, and investors alike. Understanding these trends is crucial for making informed decisions in the coming year.') }}
                        </p>

                        <h2 class="h3 text-primary mb-3">{{ __('1. Market Trends and Predictions') }}</h2>
                        <p class="mb-4">
                            {{ __('Based on current market analysis and economic indicators, experts predict continued growth in residential properties, particularly in emerging neighborhoods. The demand for sustainable and smart homes is expected to increase significantly.') }}
                        </p>

                        <h2 class="h3 text-primary mb-3">{{ __('2. Technology Integration') }}</h2>
                        <p class="mb-4">
                            {{ __('Artificial intelligence and virtual reality are revolutionizing how properties are marketed and viewed. Virtual tours, AI-powered pricing models, and blockchain-based transactions are becoming standard in the industry.') }}
                        </p>

                        <h2 class="h3 text-primary mb-3">{{ __('3. Sustainability Focus') }}</h2>
                        <p class="mb-4">
                            {{ __('Green building standards and energy-efficient features are no longer optional. Buyers are increasingly prioritizing environmentally friendly properties, and this trend is expected to accelerate in 2025.') }}
                        </p>

                        <h2 class="h3 text-primary mb-3">{{ __('4. Investment Opportunities') }}</h2>
                        <p class="mb-4">
                            {{ __('Emerging markets and new development projects offer promising investment opportunities. Areas with planned infrastructure improvements and commercial developments are particularly attractive to investors.') }}
                        </p>

                        <h2 class="h3 text-primary mb-3">{{ __('5. Regulatory Changes') }}</h2>
                        <p class="mb-4">
                            {{ __('New regulations aimed at protecting consumers and ensuring market transparency are expected to be implemented. These changes will affect both buyers and sellers, requiring adaptation to new processes and requirements.') }}
                        </p>

                        <div class="alert alert-info">
                            <h4 class="h5">{{ __('Key Takeaways') }}</h4>
                            <ul class="mb-0">
                                <li>{{ __('Technology will continue to transform the industry') }}</li>
                                <li>{{ __('Sustainability will be a major factor in property values') }}</li>
                                <li>{{ __('Emerging neighborhoods offer growth potential') }}</li>
                                <li>{{ __('Regulatory changes will impact market dynamics') }}</li>
                            </ul>
                        </div>

                        <p class="mb-4">
                            {{ __('As we approach 2025, staying informed about these trends will be essential for anyone involved in real estate. Whether you\'re buying, selling, or investing, understanding the market direction will help you make better decisions.') }}
                        </p>

                        <p class="mb-0">
                            {{ __('For personalized advice and market insights, consider consulting with our real estate experts who can provide tailored guidance based on your specific needs and goals.') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Author Information -->
            <div class="card shadow-sm mb-5">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-center me-3" style="width: 60px; height: 60px;">
                            <i class="fas fa-user fa-2x"></i>
                        </div>
                        <div>
                            <h5 class="mb-1">{{ __('Real Estate Team') }}</h5>
                            <p class="text-muted mb-2">{{ __('Professional real estate experts with over 15 years of experience in the market.') }}</p>
                            <div class="d-flex gap-2">
                                <a href="#" class="text-primary"><i class="fab fa-linkedin"></i></a>
                                <a href="#" class="text-primary"><i class="fab fa-twitter"></i></a>
                                <a href="#" class="text-primary"><i class="fas fa-envelope"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Posts -->
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h3 class="h5 mb-0">{{ __('Related Articles') }}</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="d-flex">
                                <div class="bg-light rounded me-3" style="width: 80px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-home text-muted"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">{{ __('First-Time Homebuyer Guide') }}</h6>
                                    <small class="text-muted">{{ __('Essential tips for new buyers') }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex">
                                <div class="bg-light rounded me-3" style="width: 80px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-chart-line text-muted"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">{{ __('Investment Property Strategies') }}</h6>
                                    <small class="text-muted">{{ __('Maximizing your returns') }}</small>
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
                    <h5 class="card-title">{{ __('Search Articles') }}</h5>
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="{{ __('Search...') }}" aria-label="{{ __('Search articles') }}">
                        <button class="btn btn-primary" type="button"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </div>

            <!-- Categories Widget -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">{{ __('Categories') }}</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><a href="#" class="text-decoration-none text-muted">{{ __('Market Trends') }}</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-muted">{{ __('Investment') }}</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-muted">{{ __('Buying Guide') }}</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-muted">{{ __('Selling Tips') }}</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-muted">{{ __('Technology') }}</a></li>
                        <li><a href="#" class="text-decoration-none text-muted">{{ __('Legal Updates') }}</a></li>
                    </ul>
                </div>
            </div>

            <!-- Recent Posts Widget -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">{{ __('Recent Posts') }}</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="mb-1">{{ __('Smart Home Features for 2025') }}</h6>
                        <small class="text-muted">{{ __('January 10, 2025') }}</small>
                    </div>
                    <div class="mb-3">
                        <h6 class="mb-1">{{ __('Mortgage Rate Predictions') }}</h6>
                        <small class="text-muted">{{ __('January 8, 2025') }}</small>
                    </div>
                    <div>
                        <h6 class="mb-1">{{ __('Neighborhood Development Guide') }}</h6>
                        <small class="text-muted">{{ __('January 5, 2025') }}</small>
                    </div>
                </div>
            </div>

            <!-- Newsletter Signup -->
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">{{ __('Stay Updated') }}</h5>
                    <p class="card-text text-muted">{{ __('Get the latest real estate insights delivered to your inbox.') }}</p>
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" placeholder="{{ __('Email address') }}" aria-label="{{ __('Email address') }}">
                        <button class="btn btn-primary" type="button">{{ __('Subscribe') }}</button>
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

.text-primary {
    color: #007bff !important;
}

.bg-primary {
    background-color: #007bff !important;
}

.bg-light {
    background-color: #f8f9fa !important;
}

.article-content h2 {
    margin-top: 2rem;
}

.article-content p {
    line-height: 1.8;
}

.alert {
    border-radius: 10px;
}
</style>
@endsection
