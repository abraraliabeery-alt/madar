@extends('admin.layouts.app')

@section('title', 'تصدير بيانات المستخدمين')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">لوحة التحكم</a></li>
                        <li class="breadcrumb-item active">تصدير بيانات المستخدمين</li>
                    </ol>
                </div>
                <h4 class="page-title">تصدير بيانات المستخدمين</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">خيارات التصدير</h5>
                </div>
                <div class="card-body">
                    <form id="exportForm">
                        @csrf
                        
                        <!-- Export Format -->
                        <div class="mb-4">
                            <h6>تنسيق التصدير</h6>
                            <div class="row">
                                @foreach($exportFormats as $key => $label)
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="format" id="format_{{ $key }}" value="{{ $key }}" {{ $key === 'excel' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="format_{{ $key }}">
                                            <i class="mdi mdi-file-{{ $key === 'excel' ? 'excel' : ($key === 'pdf' ? 'pdf' : 'document') }}"></i> {{ $label }}
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Filters -->
                        <div class="mb-4">
                            <h6>الفلاتر</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="date_from" class="form-label">من تاريخ</label>
                                        <input type="date" class="form-control" id="date_from" name="date_from">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="date_to" class="form-label">إلى تاريخ</label>
                                        <input type="date" class="form-control" id="date_to" name="date_to">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="role_filter" class="form-label">الدور</label>
                                        <select class="form-select" id="role_filter" name="role_filter">
                                            <option value="">جميع الأدوار</option>
                                            @foreach(\App\Models\Role::all() as $role)
                                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="status_filter" class="form-label">الحالة</label>
                                        <select class="form-select" id="status_filter" name="status_filter">
                                            <option value="">جميع الحالات</option>
                                            <option value="active">نشط</option>
                                            <option value="inactive">غير نشط</option>
                                            <option value="verified">مصدق</option>
                                            <option value="unverified">غير مصدق</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Data to Include -->
                        <div class="mb-4">
                            <h6>البيانات المراد تصديرها</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" id="include_activities" name="include_activities" checked>
                                        <label class="form-check-label" for="include_activities">سجل النشاط</label>
                                    </div>
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" id="include_bookings" name="include_bookings" checked>
                                        <label class="form-check-label" for="include_bookings">الحجوزات</label>
                                    </div>
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" id="include_contracts" name="include_contracts" checked>
                                        <label class="form-check-label" for="include_contracts">العقود</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" id="include_invoices" name="include_invoices" checked>
                                        <label class="form-check-label" for="include_invoices">الفواتير</label>
                                    </div>
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" id="include_payments" name="include_payments" checked>
                                        <label class="form-check-label" for="include_payments">المدفوعات</label>
                                    </div>
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" id="include_comments" name="include_comments" checked>
                                        <label class="form-check-label" for="include_comments">التعليقات</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-info" id="previewBtn">
                                <i class="mdi mdi-eye"></i> معاينة البيانات
                            </button>
                            <button type="submit" class="btn btn-primary" id="exportBtn">
                                <i class="mdi mdi-download"></i> تصدير البيانات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">إحصائيات التصدير</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <h4 class="text-primary">{{ number_format($totalUsers) }}</h4>
                        <p class="text-muted">إجمالي المستخدمين</p>
                    </div>
                    
                    <div class="row text-center">
                        <div class="col-6">
                            <h6 class="text-success">{{ \App\Models\User::whereNotNull('last_login_at')->count() }}</h6>
                            <small class="text-muted">نشط</small>
                        </div>
                        <div class="col-6">
                            <h6 class="text-info">{{ \App\Models\User::whereNotNull('email_verified_at')->count() }}</h6>
                            <small class="text-muted">مصدق</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">معلومات التصدير</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6>ملاحظات مهمة:</h6>
                        <ul class="mb-0">
                            <li>سيتم تصدير جميع بيانات المستخدمين المحددة</li>
                            <li>يمكنك اختيار نطاق زمني محدد للتصدير</li>
                            <li>البيانات المصدرة آمنة ومشفرة</li>
                            <li>يمكن تصدير البيانات بصيغ متعددة</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Preview Modal -->
    <div class="modal fade" id="previewModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">معاينة البيانات</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="previewContent">
                        <div class="text-center">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">جاري التحميل...</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                    <button type="button" class="btn btn-primary" id="exportFromPreview">تصدير</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Export form submission
        document.getElementById('exportForm').addEventListener('submit', function(e) {
            e.preventDefault();
            exportData();
        });
        
        // Preview button
        document.getElementById('previewBtn').addEventListener('click', function() {
            previewData();
        });
        
        // Export from preview
        document.getElementById('exportFromPreview').addEventListener('click', function() {
            document.getElementById('previewModal').querySelector('.btn-close').click();
            exportData();
        });
    });
    
    function previewData() {
        const formData = new FormData(document.getElementById('exportForm'));
        formData.append('format', 'json'); // Always use JSON for preview
        
        document.getElementById('previewContent').innerHTML = `
            <div class="text-center">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">جاري التحميل...</span>
                </div>
            </div>
        `;
        
        fetch('{{ route("admin.users.export-data") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('previewContent').innerHTML = `
                <div class="alert alert-info">
                    <h6>معلومات التصدير:</h6>
                    <p><strong>إجمالي المستخدمين:</strong> ${data.export_info.total_users}</p>
                    <p><strong>تاريخ التصدير:</strong> ${new Date(data.export_info.exported_at).toLocaleString('ar-SA')}</p>
                </div>
                <pre class="bg-light p-3 rounded" style="max-height: 400px; overflow-y: auto;"><code>${JSON.stringify(data, null, 2)}</code></pre>
            `;
            new bootstrap.Modal(document.getElementById('previewModal')).show();
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('previewContent').innerHTML = '<div class="alert alert-danger">خطأ في تحميل البيانات</div>';
        });
    }
    
    function exportData() {
        const form = document.getElementById('exportForm');
        const formData = new FormData(form);
        
        // Create a form and submit it
        const exportForm = document.createElement('form');
        exportForm.method = 'POST';
        exportForm.action = '{{ route("admin.users.export-data") }}';
        exportForm.style.display = 'none';
        
        // Add CSRF token
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        exportForm.appendChild(csrfInput);
        
        // Add form data
        for (let [key, value] of formData.entries()) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = key;
            input.value = value;
            exportForm.appendChild(input);
        }
        
        document.body.appendChild(exportForm);
        exportForm.submit();
        document.body.removeChild(exportForm);
    }
</script>
@endpush
