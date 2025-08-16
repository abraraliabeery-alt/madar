@extends('layouts.app')

@section('title', __('Careers'))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="text-center mb-5">
                <h1 class="display-4 text-primary">{{ __('Join Our Team') }}</h1>
                <p class="lead text-muted">{{ __('Build your career in real estate with one of the leading companies in the industry') }}</p>
            </div>

            <!-- Company Culture Section -->
            <div class="row mb-5">
                <div class="col-lg-8 mx-auto">
                    <div class="card shadow-lg bg-primary text-white">
                        <div class="card-body text-center p-5">
                            <h2 class="h3 mb-4">{{ __('Why Work With Us?') }}</h2>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <div class="text-center">
                                        <i class="fas fa-rocket fa-3x mb-3"></i>
                                        <h5>{{ __('Growth Opportunities') }}</h5>
                                        <p class="small">{{ __('Continuous learning and career advancement') }}</p>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="text-center">
                                        <i class="fas fa-users fa-3x mb-3"></i>
                                        <h5>{{ __('Great Team') }}</h5>
                                        <p class="small">{{ __('Collaborative and supportive environment') }}</p>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="text-center">
                                        <i class="fas fa-chart-line fa-3x mb-3"></i>
                                        <h5>{{ __('Market Leader') }}</h5>
                                        <p class="small">{{ __('Work with industry-leading technology') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Job Categories -->
            <div class="row mb-5">
                <div class="col-12">
                    <h3 class="text-center mb-4">{{ __('Explore Our Departments') }}</h3>
                    <div class="d-flex flex-wrap justify-content-center gap-3">
                        <a href="#sales" class="btn btn-outline-primary">{{ __('Sales & Marketing') }}</a>
                        <a href="#technology" class="btn btn-outline-primary">{{ __('Technology') }}</a>
                        <a href="#operations" class="btn btn-outline-primary">{{ __('Operations') }}</a>
                        <a href="#finance" class="btn btn-outline-primary">{{ __('Finance') }}</a>
                        <a href="#hr" class="btn btn-outline-primary">{{ __('Human Resources') }}</a>
                        <a href="#legal" class="btn btn-outline-primary">{{ __('Legal & Compliance') }}</a>
                    </div>
                </div>
            </div>

            <!-- Featured Jobs -->
            <div class="row mb-5">
                <div class="col-lg-8 mx-auto">
                    <h3 class="text-center mb-4">{{ __('Featured Opportunities') }}</h3>
                    
                    <!-- Featured Job 1 -->
                    <div class="card shadow-sm mb-4 border-primary">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h4 class="card-title text-primary">{{ __('Senior Real Estate Agent') }}</h4>
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
                            <p class="card-text mb-3">
                                {{ __('We are seeking an experienced real estate agent to join our growing team. The ideal candidate will have a proven track record in residential sales and excellent customer service skills.') }}
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong class="text-success">{{ __('Competitive salary + commission') }}</strong>
                                </div>
                                <a href="#" class="btn btn-primary">{{ __('Apply Now') }}</a>
                            </div>
                        </div>
                    </div>

                    <!-- Featured Job 2 -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h4 class="card-title">{{ __('Software Developer') }}</h4>
                                    <p class="text-muted mb-2">{{ __('Technology Department') }}</p>
                                    <div class="d-flex align-items-center gap-3">
                                        <span class="badge bg-info">{{ __('Full-time') }}</span>
                                        <span class="text-muted"><i class="fas fa-map-marker-alt me-1"></i>{{ __('Remote / Riyadh') }}</span>
                                        <span class="text-muted"><i class="fas fa-clock me-1"></i>{{ __('Posted 1 week ago') }}</span>
                                    </div>
                                </div>
                            </div>
                            <p class="card-text mb-3">
                                {{ __('Join our development team to build innovative real estate technology solutions. We\'re looking for developers with experience in Laravel, Vue.js, and modern web technologies.') }}
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong class="text-success">{{ __('Competitive salary + benefits') }}</strong>
                                </div>
                                <a href="#" class="btn btn-outline-primary">{{ __('Apply Now') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Job Listings by Department -->
            <div class="row">
                <!-- Sales & Marketing -->
                <div class="col-lg-6 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-primary text-white">
                            <h4 class="h5 mb-0">{{ __('Sales & Marketing') }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <h6 class="mb-1">{{ __('Real Estate Agent') }}</h6>
                                <p class="text-muted small mb-2">{{ __('Entry-level position for motivated individuals') }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-secondary">{{ __('Part-time') }}</span>
                                    <a href="#" class="btn btn-sm btn-outline-primary">{{ __('View Details') }}</a>
                                </div>
                            </div>
                            <div class="mb-3">
                                <h6 class="mb-1">{{ __('Marketing Specialist') }}</h6>
                                <p class="text-muted small mb-2">{{ __('Digital marketing and content creation') }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-success">{{ __('Full-time') }}</span>
                                    <a href="#" class="btn btn-sm btn-outline-primary">{{ __('View Details') }}</a>
                                </div>
                            </div>
                            <div>
                                <h6 class="mb-1">{{ __('Sales Manager') }}</h6>
                                <p class="text-muted small mb-2">{{ __('Lead and manage sales team') }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-success">{{ __('Full-time') }}</span>
                                    <a href="#" class="btn btn-sm btn-outline-primary">{{ __('View Details') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Technology -->
                <div class="col-lg-6 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-info text-white">
                            <h4 class="h5 mb-0">{{ __('Technology') }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <h6 class="mb-1">{{ __('Frontend Developer') }}</h6>
                                <p class="text-muted small mb-2">{{ __('Vue.js and modern CSS frameworks') }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-success">{{ __('Full-time') }}</span>
                                    <a href="#" class="btn btn-sm btn-outline-primary">{{ __('View Details') }}</a>
                                </div>
                            </div>
                            <div class="mb-3">
                                <h6 class="mb-1">{{ __('DevOps Engineer') }}</h6>
                                <p class="text-muted small mb-2">{{ __('Infrastructure and deployment automation') }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-success">{{ __('Full-time') }}</span>
                                    <a href="#" class="btn btn-sm btn-outline-primary">{{ __('View Details') }}</a>
                                </div>
                            </div>
                            <div>
                                <h6 class="mb-1">{{ __('QA Engineer') }}</h6>
                                <p class="text-muted small mb-2">{{ __('Quality assurance and testing') }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-success">{{ __('Full-time') }}</span>
                                    <a href="#" class="btn btn-sm btn-outline-primary">{{ __('View Details') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Operations -->
                <div class="col-lg-6 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-success text-white">
                            <h4 class="h5 mb-0">{{ __('Operations') }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <h6 class="mb-1">{{ __('Operations Manager') }}</h6>
                                <p class="text-muted small mb-2">{{ __('Oversee daily operations') }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-success">{{ __('Full-time') }}</span>
                                    <a href="#" class="btn btn-sm btn-outline-primary">{{ __('View Details') }}</a>
                                </div>
                            </div>
                            <div class="mb-3">
                                <h6 class="mb-1">{{ __('Customer Support') }}</h6>
                                <p class="text-muted small mb-2">{{ __('Help customers with inquiries') }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-secondary">{{ __('Part-time') }}</span>
                                    <a href="#" class="btn btn-sm btn-outline-primary">{{ __('View Details') }}</a>
                                </div>
                            </div>
                            <div>
                                <h6 class="mb-1">{{ __('Data Analyst') }}</h6>
                                <p class="text-muted small mb-2">{{ __('Market analysis and reporting') }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-success">{{ __('Full-time') }}</span>
                                    <a href="#" class="btn btn-sm btn-outline-primary">{{ __('View Details') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Finance -->
                <div class="col-lg-6 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-warning text-dark">
                            <h4 class="h5 mb-0">{{ __('Finance') }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <h6 class="mb-1">{{ __('Financial Analyst') }}</h6>
                                <p class="text-muted small mb-2">{{ __('Financial planning and analysis') }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-success">{{ __('Full-time') }}</span>
                                    <a href="#" class="btn btn-sm btn-outline-primary">{{ __('View Details') }}</a>
                                </div>
                            </div>
                            <div class="mb-3">
                                <h6 class="mb-1">{{ __('Accountant') }}</h6>
                                <p class="text-muted small mb-2">{{ __('General accounting duties') }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-success">{{ __('Full-time') }}</span>
                                    <a href="#" class="btn btn-sm btn-outline-primary">{{ __('View Details') }}</a>
                                </div>
                            </div>
                            <div>
                                <h6 class="mb-1">{{ __('Payroll Specialist') }}</h6>
                                <p class="text-muted small mb-2">{{ __('Manage employee payroll') }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-success">{{ __('Full-time') }}</span>
                                    <a href="#" class="btn btn-sm btn-outline-primary">{{ __('View Details') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Benefits Section -->
            <div class="row mt-5">
                <div class="col-lg-8 mx-auto">
                    <div class="card shadow-sm">
                        <div class="card-header bg-light">
                            <h3 class="h4 mb-0 text-center">{{ __('Employee Benefits') }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <ul class="list-unstyled">
                                        <li class="mb-3"><i class="fas fa-check text-success me-2"></i>{{ __('Competitive salary packages') }}</li>
                                        <li class="mb-3"><i class="fas fa-check text-success me-2"></i>{{ __('Health insurance coverage') }}</li>
                                        <li class="mb-3"><i class="fas fa-check text-success me-2"></i>{{ __('Professional development opportunities') }}</li>
                                        <li class="mb-3"><i class="fas fa-check text-success me-2"></i>{{ __('Flexible working hours') }}</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <ul class="list-unstyled">
                                        <li class="mb-3"><i class="fas fa-check text-success me-2"></i>{{ __('Annual performance bonuses') }}</li>
                                        <li class="mb-3"><i class="fas fa-check text-success me-2"></i>{{ __('Paid time off and holidays') }}</li>
                                        <li class="mb-3"><i class="fas fa-check text-success me-2"></i>{{ __('Modern office environment') }}</li>
                                        <li class="mb-3"><i class="fas fa-check text-success me-2"></i>{{ __('Team building activities') }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Application Process -->
            <div class="row mt-5">
                <div class="col-lg-8 mx-auto">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h3 class="h4 mb-0 text-center">{{ __('How to Apply') }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-md-3 mb-3">
                                    <div class="bg-light rounded p-3">
                                        <i class="fas fa-search fa-2x text-primary mb-2"></i>
                                        <h6>{{ __('1. Find a Position') }}</h6>
                                        <p class="small text-muted">{{ __('Browse our open positions') }}</p>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="bg-light rounded p-3">
                                        <i class="fas fa-file-alt fa-2x text-primary mb-2"></i>
                                        <h6>{{ __('2. Prepare Resume') }}</h6>
                                        <p class="small text-muted">{{ __('Update your CV and cover letter') }}</p>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="bg-light rounded p-3">
                                        <i class="fas fa-paper-plane fa-2x text-primary mb-2"></i>
                                        <h6>{{ __('3. Submit Application') }}</h6>
                                        <p class="small text-muted">{{ __('Apply through our portal') }}</p>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="bg-light rounded p-3">
                                        <i class="fas fa-handshake fa-2x text-primary mb-2"></i>
                                        <h6>{{ __('4. Interview Process') }}</h6>
                                        <p class="small text-muted">{{ __('Meet our team') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Call to Action -->
            <div class="text-center mt-5">
                <div class="card bg-primary text-white p-5">
                    <h3 class="mb-3">{{ __('Ready to Join Our Team?') }}</h3>
                    <p class="mb-4">{{ __('Don\'t see a position that matches your skills? Send us your resume and we\'ll keep you in mind for future opportunities.') }}</p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="#" class="btn btn-light btn-lg">{{ __('Browse All Jobs') }}</a>
                        <a href="#" class="btn btn-outline-light btn-lg">{{ __('Submit Resume') }}</a>
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

.text-primary {
    color: #007bff !important;
}

.bg-primary {
    background-color: #007bff !important;
}

.bg-info {
    background-color: #17a2b8 !important;
}

.bg-success {
    background-color: #28a745 !important;
}

.bg-warning {
    background-color: #ffc107 !important;
}

.border-primary {
    border-color: #007bff !important;
}
</style>
@endsection
