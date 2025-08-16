@extends('layouts.app')

@section('title', __('Cookies Policy'))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h1 class="h3 mb-0">{{ __('Cookies Policy') }}</h1>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="content-section">
                                <h2 class="h4 text-primary mb-3">{{ __('What are Cookies?') }}</h2>
                                <p class="text-muted">
                                    {{ __('Cookies are small text files that are placed on your device when you visit our website. They help us provide you with a better experience and allow certain features to work properly.') }}
                                </p>
                            </div>

                            <div class="content-section mt-4">
                                <h2 class="h4 text-primary mb-3">{{ __('How We Use Cookies') }}</h2>
                                <p class="text-muted">
                                    {{ __('We use cookies for several purposes:') }}
                                </p>
                                <ul class="text-muted">
                                    <li>{{ __('Essential cookies that are necessary for the website to function properly') }}</li>
                                    <li>{{ __('Analytics cookies to understand how visitors use our website') }}</li>
                                    <li>{{ __('Preference cookies to remember your settings and choices') }}</li>
                                    <li>{{ __('Marketing cookies to provide you with relevant advertisements') }}</li>
                                </ul>
                            </div>

                            <div class="content-section mt-4">
                                <h2 class="h4 text-primary mb-3">{{ __('Managing Cookies') }}</h2>
                                <p class="text-muted">
                                    {{ __('You can control and manage cookies through your browser settings. You can delete existing cookies and prevent new ones from being set. However, disabling certain cookies may affect the functionality of our website.') }}
                                </p>
                            </div>

                            <div class="content-section mt-4">
                                <h2 class="h4 text-primary mb-3">{{ __('Third-Party Cookies') }}</h2>
                                <p class="text-muted">
                                    {{ __('Some cookies on our website are set by third-party services, such as Google Analytics, social media platforms, and advertising networks. These third parties have their own privacy policies and cookie policies.') }}
                                </p>
                            </div>

                            <div class="content-section mt-4">
                                <h2 class="h4 text-primary mb-3">{{ __('Updates to This Policy') }}</h2>
                                <p class="text-muted">
                                    {{ __('We may update this cookies policy from time to time. Any changes will be posted on this page with an updated revision date.') }}
                                </p>
                            </div>

                            <div class="content-section mt-4">
                                <h2 class="h4 text-primary mb-3">{{ __('Contact Us') }}</h2>
                                <p class="text-muted">
                                    {{ __('If you have any questions about our use of cookies, please contact us.') }}
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
