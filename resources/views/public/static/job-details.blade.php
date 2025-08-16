@extends('layouts.app')

@section('title', __('Job Details'))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('careers') }}">{{ __('Careers') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('Job Details') }}</li>
                </ol>
            </nav>

            <!-- Job Header -->
            <div class="card shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h1 class="h2 text-primary mb-2">{{ __('Senior Real Estate Agent') }}</h1>
                            <p class="text-muted mb-2">{{ __('Sales & Marketing Department') }}</p>
                            <div class="d-flex align-items-center gap-3">
                                <span class="badge bg-success">{{ __('Full-time') }}</span>
                                <span class="text-muted"><i class="fas fa-map-marker-alt me-1"></i>{{ __('Riyadh, Saudi Arabia') }}</span>
                                <span class="text-muted"><i class="fas fa-clock me-1"></i>{{ __('Posted 2 days ago') }}</span>
                            </div>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-warning text-dark">{{ __('Urgent') }}</span>
                        </div>
                    </div>
                    
                    <!-- Quick Apply Button -->
                    <div class="text-center">
                        <a href="#apply-form" class="btn btn-primary btn-lg">{{ __('Apply Now') }}</a>
                    </div>
                </div>
            </div>

            <!-- Job Details -->
            <div class="row">
                <div class="col-lg-8">
                    <!-- Job Description -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h3 class="h5 mb-0">{{ __('Job Description') }}</h3>
                        </div>
                        <div class="card-body">
                            <p class="lead mb-4">
                                {{ __('We are seeking an experienced and motivated Senior Real Estate Agent to join our growing team. This is an exciting opportunity for a professional who is passionate about real estate and committed to providing exceptional service to our clients.') }}
                            </p>

                            <h4 class="h5 text-primary mb-3">{{ __('About the Role') }}</h4>
                            <p class="mb-4">
                                {{ __('As a Senior Real Estate Agent, you will be responsible for managing a portfolio of residential properties, building strong relationships with clients, and achieving sales targets. You will work closely with our marketing team to develop effective strategies and represent our company with professionalism and integrity.') }}
                            </p>

                            <h4 class="h5 text-primary mb-3">{{ __('Key Responsibilities') }}</h4>
                            <ul class="mb-4">
                                <li class="mb-2">{{ __('Manage a portfolio of residential properties for sale and rent') }}</li>
                                <li class="mb-2">{{ __('Conduct property viewings and provide detailed information to potential buyers/tenants') }}</li>
                                <li class="mb-2">{{ __('Negotiate offers and contracts on behalf of clients') }}</li>
                                <li class="mb-2">{{ __('Build and maintain relationships with property owners, buyers, and other industry professionals') }}</li>
                                <li class="mb-2">{{ __('Stay updated on market trends and property values in assigned areas') }}</li>
                                <li class="mb-2">{{ __('Collaborate with marketing team to develop effective property marketing strategies') }}</li>
                                <li class="mb-2">{{ __('Provide excellent customer service and maintain high client satisfaction') }}</li>
                                <li class="mb-2">{{ __('Meet and exceed monthly and quarterly sales targets') }}</li>
                            </ul>

                            <h4 class="h5 text-primary mb-3">{{ __('Requirements') }}</h4>
                            <ul class="mb-4">
                                <li class="mb-2">{{ __('Minimum 3 years of experience in real estate sales') }}</li>
                                <li class="mb-2">{{ __('Proven track record of achieving sales targets') }}</li>
                                <li class="mb-2">{{ __('Excellent communication and negotiation skills') }}</li>
                                <li class="mb-2">{{ __('Strong knowledge of local real estate market') }}</li>
                                <li class="mb-2">{{ __('Valid real estate license (preferred)') }}</li>
                                <li class="mb-2">{{ __('Proficiency in CRM systems and real estate software') }}</li>
                                <li class="mb-2">{{ __('Ability to work independently and as part of a team') }}</li>
                                <li class="mb-2">{{ __('Flexible schedule to accommodate client needs') }}</li>
                            </ul>

                            <h4 class="h5 text-primary mb-3">{{ __('What We Offer') }}</h4>
                            <ul class="mb-4">
                                <li class="mb-2">{{ __('Competitive base salary with attractive commission structure') }}</li>
                                <li class="mb-2">{{ __('Comprehensive health insurance coverage') }}</li>
                                <li class="mb-2">{{ __('Professional development and training opportunities') }}</li>
                                <li class="mb-2">{{ __('Modern office environment with latest technology') }}</li>
                                <li class="mb-2">{{ __('Supportive team culture and mentorship') }}</li>
                                <li class="mb-2">{{ __('Flexible working hours and work-life balance') }}</li>
                                <li class="mb-2">{{ __('Performance-based bonuses and incentives') }}</li>
                                <li class="mb-2">{{ __('Career advancement opportunities') }}</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Company Information -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h3 class="h5 mb-0">{{ __('About Our Company') }}</h3>
                        </div>
                        <div class="card-body">
                            <p class="mb-3">
                                {{ __('We are one of the leading real estate companies in Saudi Arabia, specializing in residential and commercial properties. With over 15 years of experience, we have built a reputation for excellence, integrity, and innovation in the real estate industry.') }}
                            </p>
                            <p class="mb-3">
                                {{ __('Our mission is to provide exceptional real estate services while building lasting relationships with our clients. We believe in continuous innovation and staying ahead of market trends to deliver the best possible outcomes for our clients.') }}
                            </p>
                            <p class="mb-0">
                                {{ __('Join our team and be part of a company that values professionalism, growth, and success. We offer a supportive environment where you can develop your skills and advance your career in real estate.') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Quick Info -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-primary text-white">
                            <h4 class="h6 mb-0">{{ __('Job Summary') }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <strong>{{ __('Department:') }}</strong>
                                <p class="mb-0">{{ __('Sales & Marketing') }}</p>
                            </div>
                            <div class="mb-3">
                                <strong>{{ __('Location:') }}</strong>
                                <p class="mb-0">{{ __('Riyadh, Saudi Arabia') }}</p>
                            </div>
                            <div class="mb-3">
                                <strong>{{ __('Employment Type:') }}</strong>
                                <p class="mb-0">{{ __('Full-time') }}</p>
                            </div>
                            <div class="mb-3">
                                <strong>{{ __('Experience Level:') }}</strong>
                                <p class="mb-0">{{ __('3+ years') }}</p>
                            </div>
                            <div class="mb-3">
                                <strong>{{ __('Salary:') }}</strong>
                                <p class="mb-0">{{ __('Competitive + Commission') }}</p>
                            </div>
                            <div class="mb-3">
                                <strong>{{ __('Posted:') }}</strong>
                                <p class="mb-0">{{ __('2 days ago') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Similar Jobs -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h4 class="h6 mb-0">{{ __('Similar Positions') }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <h6 class="mb-1">{{ __('Real Estate Agent') }}</h6>
                                <p class="text-muted small mb-2">{{ __('Entry-level position') }}</p>
                                <a href="#" class="btn btn-sm btn-outline-primary">{{ __('View Details') }}</a>
                            </div>
                            <div class="mb-3">
                                <h6 class="mb-1">{{ __('Sales Manager') }}</h6>
                                <p class="text-muted small mb-2">{{ __('Team leadership role') }}</p>
                                <a href="#" class="btn btn-sm btn-outline-primary">{{ __('View Details') }}</a>
                            </div>
                            <div>
                                <h6 class="mb-1">{{ __('Marketing Specialist') }}</h6>
                                <p class="text-muted small mb-2">{{ __('Digital marketing focus') }}</p>
                                <a href="#" class="btn btn-sm btn-outline-primary">{{ __('View Details') }}</a>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="card shadow-sm">
                        <div class="card-header bg-info text-white">
                            <h4 class="h6 mb-0">{{ __('Contact HR') }}</h4>
                        </div>
                        <div class="card-body">
                            <p class="small text-muted mb-3">{{ __('Have questions about this position?') }}</p>
                            <div class="mb-2">
                                <i class="fas fa-envelope text-info me-2"></i>
                                <a href="mailto:hr@company.com" class="text-decoration-none">hr@company.com</a>
                            </div>
                            <div class="mb-2">
                                <i class="fas fa-phone text-info me-2"></i>
                                <a href="tel:+966112345678" class="text-decoration-none">+966 11 234 5678</a>
                            </div>
                            <div>
                                <i class="fas fa-clock text-info me-2"></i>
                                <span class="small">{{ __('Mon-Fri, 9AM-6PM') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Application Form -->
            <div id="apply-form" class="card shadow-sm mt-5">
                <div class="card-header bg-success text-white">
                    <h3 class="h5 mb-0">{{ __('Apply for This Position') }}</h3>
                </div>
                <div class="card-body p-4">
                    <form>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="firstName" class="form-label">{{ __('First Name') }} *</label>
                                <input type="text" class="form-control" id="firstName" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="lastName" class="form-label">{{ __('Last Name') }} *</label>
                                <input type="text" class="form-control" id="lastName" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">{{ __('Email Address') }} *</label>
                                <input type="email" class="form-control" id="email" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">{{ __('Phone Number') }} *</label>
                                <input type="tel" class="form-control" id="phone" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="experience" class="form-label">{{ __('Years of Experience') }} *</label>
                            <select class="form-select" id="experience" required>
                                <option value="">{{ __('Select experience level') }}</option>
                                <option value="0-1">{{ __('0-1 years') }}</option>
                                <option value="1-3">{{ __('1-3 years') }}</option>
                                <option value="3-5">{{ __('3-5 years') }}</option>
                                <option value="5-10">{{ __('5-10 years') }}</option>
                                <option value="10+">{{ __('10+ years') }}</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="coverLetter" class="form-label">{{ __('Cover Letter') }}</label>
                            <textarea class="form-control" id="coverLetter" rows="4" placeholder="{{ __('Tell us why you\'re interested in this position and what makes you a great candidate...') }}"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="resume" class="form-label">{{ __('Resume/CV') }} *</label>
                            <input type="file" class="form-control" id="resume" accept=".pdf,.doc,.docx" required>
                            <div class="form-text">{{ __('Accepted formats: PDF, DOC, DOCX (Max size: 5MB)') }}</div>
                        </div>

                        <div class="mb-3">
                            <label for="portfolio" class="form-label">{{ __('Portfolio (Optional)') }}</label>
                            <input type="file" class="form-control" id="portfolio" accept=".pdf,.zip,.rar">
                            <div class="form-text">{{ __('Accepted formats: PDF, ZIP, RAR (Max size: 10MB)') }}</div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="agree" required>
                                <label class="form-check-label" for="agree">
                                    {{ __('I agree to the') }} <a href="#" class="text-decoration-none">{{ __('Terms of Service') }}</a> {{ __('and') }} <a href="#" class="text-decoration-none">{{ __('Privacy Policy') }}</a>
                                </label>
                            </div>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-success btn-lg">{{ __('Submit Application') }}</button>
                        </div>
                    </form>
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

.bg-success {
    background-color: #28a745 !important;
}

.bg-info {
    background-color: #17a2b8 !important;
}

.bg-warning {
    background-color: #ffc107 !important;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.btn-success:hover {
    background-color: #218838;
    border-color: #1e7e34;
}
</style>
@endsection
