@extends('layouts.app')

@section('title', __('Advertising Policy'))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h1 class="h3 mb-0">{{ __('Advertising Policy') }}</h1>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="content-section">
                                <h2 class="h4 text-primary mb-3">{{ __('Our Advertising Standards') }}</h2>
                                <p class="text-muted">
                                    {{ __('We are committed to maintaining high standards in all our advertising and promotional activities. Our advertising policy ensures transparency, accuracy, and compliance with applicable laws and regulations.') }}
                                </p>
                            </div>

                            <div class="content-section mt-4">
                                <h2 class="h4 text-primary mb-3">{{ __('Advertising Guidelines') }}</h2>
                                <p class="text-muted">
                                    {{ __('All advertisements on our platform must adhere to the following guidelines:') }}
                                </p>
                                <ul class="text-muted">
                                    <li>{{ __('Accurate and truthful representation of products and services') }}</li>
                                    <li>{{ __('Compliance with local advertising laws and regulations') }}</li>
                                    <li>{{ __('No misleading or deceptive claims') }}</li>
                                    <li>{{ __('Respect for intellectual property rights') }}</li>
                                    <li>{{ __('Appropriate content suitable for all audiences') }}</li>
                                </ul>
                            </div>

                            <div class="content-section mt-4">
                                <h2 class="h4 text-primary mb-3">{{ __('Sponsored Content') }}</h2>
                                <p class="text-muted">
                                    {{ __('We clearly label sponsored content and advertisements to ensure transparency. Users can easily distinguish between organic content and promotional materials.') }}
                                </p>
                            </div>

                            <div class="content-section mt-4">
                                <h2 class="h4 text-primary mb-3">{{ __('Third-Party Advertisements') }}</h2>
                                <p class="text-muted">
                                    {{ __('We may display third-party advertisements on our platform. While we strive to ensure these ads meet our standards, we are not responsible for the content of third-party advertisements.') }}
                                </p>
                            </div>

                            <div class="content-section mt-4">
                                <h2 class="h4 text-primary mb-3">{{ __('User Privacy and Advertising') }}</h2>
                                <p class="text-muted">
                                    {{ __('We respect user privacy in our advertising practices. Personal information is never sold to advertisers, and targeted advertising is based on aggregated, anonymized data.') }}
                                </p>
                            </div>

                            <div class="content-section mt-4">
                                <h2 class="h4 text-primary mb-3">{{ __('Reporting Violations') }}</h2>
                                <p class="text-muted">
                                    {{ __('If you encounter advertising that violates our policy, please report it to us. We will investigate and take appropriate action against violators.') }}
                                </p>
                            </div>

                            <div class="content-section mt-4">
                                <h2 class="h4 text-primary mb-3">{{ __('Contact Us') }}</h2>
                                <p class="text-muted">
                                    {{ __('For questions about our advertising policy or to report violations, please contact our advertising team.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.content-section {
    padding: 1rem 0;
    border-bottom: 1px solid #e9ecef;
}

.content-section:last-child {
    border-bottom: none;
}

.card {
    border: none;
    border-radius: 15px;
}

.card-header {
    border-radius: 15px 15px 0 0 !important;
}

.text-primary {
    color: #007bff !important;
}
</style>
@endsection
