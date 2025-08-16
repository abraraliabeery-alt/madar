@extends('layouts.app')

@section('title', __('Privacy Policy'))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h1 class="h3 mb-0">{{ __('Privacy Policy') }}</h1>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="content-section">
                                <h2 class="h4 text-primary mb-3">{{ __('Information We Collect') }}</h2>
                                <p class="text-muted">
                                    {{ __('We collect information you provide directly to us, such as when you create an account, make a purchase, or contact us for support. This may include:') }}
                                </p>
                                <ul class="text-muted">
                                    <li>{{ __('Name, email address, and contact information') }}</li>
                                    <li>{{ __('Account credentials and profile information') }}</li>
                                    <li>{{ __('Payment and billing information') }}</li>
                                    <li>{{ __('Communications with our support team') }}</li>
                                </ul>
                            </div>

                            <div class="content-section mt-4">
                                <h2 class="h4 text-primary mb-3">{{ __('How We Use Your Information') }}</h2>
                                <p class="text-muted">
                                    {{ __('We use the information we collect to:') }}
                                </p>
                                <ul class="text-muted">
                                    <li>{{ __('Provide and maintain our services') }}</li>
                                    <li>{{ __('Process transactions and send related information') }}</li>
                                    <li>{{ __('Send technical notices and support messages') }}</li>
                                    <li>{{ __('Respond to your comments and questions') }}</li>
                                    <li>{{ __('Improve our services and develop new features') }}</li>
                                </ul>
                            </div>

                            <div class="content-section mt-4">
                                <h2 class="h4 text-primary mb-3">{{ __('Information Sharing') }}</h2>
                                <p class="text-muted">
                                    {{ __('We do not sell, trade, or otherwise transfer your personal information to third parties without your consent, except as described in this policy. We may share information with:') }}
                                </p>
                                <ul class="text-muted">
                                    <li>{{ __('Service providers who assist in our operations') }}</li>
                                    <li>{{ __('Legal authorities when required by law') }}</li>
                                    <li>{{ __('Business partners with your explicit consent') }}</li>
                                </ul>
                            </div>

                            <div class="content-section mt-4">
                                <h2 class="h4 text-primary mb-3">{{ __('Data Security') }}</h2>
                                <p class="text-muted">
                                    {{ __('We implement appropriate security measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction. However, no method of transmission over the internet is 100% secure.') }}
                                </p>
                            </div>

                            <div class="content-section mt-4">
                                <h2 class="h4 text-primary mb-3">{{ __('Your Rights') }}</h2>
                                <p class="text-muted">
                                    {{ __('You have the right to:') }}
                                </p>
                                <ul class="text-muted">
                                    <li>{{ __('Access and update your personal information') }}</li>
                                    <li>{{ __('Request deletion of your data') }}</li>
                                    <li>{{ __('Opt out of marketing communications') }}</li>
                                    <li>{{ __('Request a copy of your data') }}</li>
                                </ul>
                            </div>

                            <div class="content-section mt-4">
                                <h2 class="h4 text-primary mb-3">{{ __('Cookies and Tracking') }}</h2>
                                <p class="text-muted">
                                    {{ __('We use cookies and similar technologies to enhance your experience on our website. You can control cookie settings through your browser preferences. Please see our Cookies Policy for more details.') }}
                                </p>
                            </div>

                            <div class="content-section mt-4">
                                <h2 class="h4 text-primary mb-3">{{ __('Changes to This Policy') }}</h2>
                                <p class="text-muted">
                                    {{ __('We may update this privacy policy from time to time. We will notify you of any changes by posting the new policy on this page and updating the "Last Updated" date.') }}
                                </p>
                            </div>

                            <div class="content-section mt-4">
                                <h2 class="h4 text-primary mb-3">{{ __('Contact Us') }}</h2>
                                <p class="text-muted">
                                    {{ __('If you have any questions about this Privacy Policy, please contact us.') }}
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
