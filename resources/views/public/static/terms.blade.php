@extends('layouts.app')

@section('title', __('Terms of Service'))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h1 class="h3 mb-0">{{ __('Terms of Service') }}</h1>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="content-section">
                                <h2 class="h4 text-primary mb-3">{{ __('Acceptance of Terms') }}</h2>
                                <p class="text-muted">
                                    {{ __('By accessing and using this website, you accept and agree to be bound by the terms and provision of this agreement. If you do not agree to abide by the above, please do not use this service.') }}
                                </p>
                            </div>

                            <div class="content-section mt-4">
                                <h2 class="h4 text-primary mb-3">{{ __('Use License') }}</h2>
                                <p class="text-muted">
                                    {{ __('Permission is granted to temporarily download one copy of the materials (information or software) on our website for personal, non-commercial transitory viewing only. This is the grant of a license, not a transfer of title, and under this license you may not:') }}
                                </p>
                                <ul class="text-muted">
                                    <li>{{ __('Modify or copy the materials') }}</li>
                                    <li>{{ __('Use the materials for any commercial purpose or for any public display') }}</li>
                                    <li>{{ __('Attempt to reverse engineer any software contained on the website') }}</li>
                                    <li>{{ __('Remove any copyright or other proprietary notations from the materials') }}</li>
                                </ul>
                            </div>

                            <div class="content-section mt-4">
                                <h2 class="h4 text-primary mb-3">{{ __('User Responsibilities') }}</h2>
                                <p class="text-muted">
                                    {{ __('As a user of our platform, you agree to:') }}
                                </p>
                                <ul class="text-muted">
                                    <li>{{ __('Provide accurate and truthful information') }}</li>
                                    <li>{{ __('Respect the rights of other users') }}</li>
                                    <li>{{ __('Comply with all applicable laws and regulations') }}</li>
                                    <li>{{ __('Not engage in any harmful or malicious activities') }}</li>
                                </ul>
                            </div>

                            <div class="content-section mt-4">
                                <h2 class="h4 text-primary mb-3">{{ __('Privacy and Data Protection') }}</h2>
                                <p class="text-muted">
                                    {{ __('Your privacy is important to us. Please review our Privacy Policy, which also governs your use of the website, to understand our practices.') }}
                                </p>
                            </div>

                            <div class="content-section mt-4">
                                <h2 class="h4 text-primary mb-3">{{ __('Limitation of Liability') }}</h2>
                                <p class="text-muted">
                                    {{ __('In no event shall we or our suppliers be liable for any damages (including, without limitation, damages for loss of data or profit, or due to business interruption) arising out of the use or inability to use the materials on our website.') }}
                                </p>
                            </div>

                            <div class="content-section mt-4">
                                <h2 class="h4 text-primary mb-3">{{ __('Governing Law') }}</h2>
                                <p class="text-muted">
                                    {{ __('These terms and conditions are governed by and construed in accordance with the laws of the jurisdiction in which we operate.') }}
                                </p>
                            </div>

                            <div class="content-section mt-4">
                                <h2 class="h4 text-primary mb-3">{{ __('Changes to Terms') }}</h2>
                                <p class="text-muted">
                                    {{ __('We reserve the right to modify these terms at any time. Changes will be effective immediately upon posting on the website. Your continued use of the website constitutes acceptance of the modified terms.') }}
                                </p>
                            </div>

                            <div class="content-section mt-4">
                                <h2 class="h4 text-primary mb-3">{{ __('Contact Information') }}</h2>
                                <p class="text-muted">
                                    {{ __('If you have any questions about these Terms of Service, please contact us.') }}
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
