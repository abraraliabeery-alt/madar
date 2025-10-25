@extends('layouts.app')

@section('title', 'تصدير البيانات')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
                        <li class="breadcrumb-item active">تصدير البيانات</li>
                    </ol>
                </div>
                <h4 class="page-title">تصدير البيانات</h4>
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
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="format" id="format_json" value="json" checked>
                                        <label class="form-check-label" for="format_json">
                                            <i class="mdi mdi-code-json"></i> JSON
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="format" id="format_excel" value="excel">
                                        <label class="form-check-label" for="format_excel">
                                            <i class="mdi mdi-file-excel"></i> Excel
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="format" id="format_csv" value="csv">
                                        <label class="form-check-label" for="format_csv">
                                            <i class="mdi mdi-file-delimited"></i> CSV
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="format" id="format_pdf" value="pdf">
                                        <label class="form-check-label" for="format_pdf">
                                            <i class="mdi mdi-file-pdf"></i> PDF
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Date Range -->
                        <div class="mb-4">
                            <h6>نطاق التاريخ (اختياري)</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="date_from" class="form-label">من تاريخ</label>
                                    <input type="date" class="form-control" id="date_from" name="date_from">
                                </div>
                                <div class="col-md-6">
                                    <label for="date_to" class="form-label">إلى تاريخ</label>
                                    <input type="date" class="form-control" id="date_to" name="date_to">
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
                                        <input class="form-check-input" type="checkbox" id="include_notifications" name="include_notifications" checked>
                                        <label class="form-check-label" for="include_notifications">الإشعارات</label>
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
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" id="include_favorites" name="include_favorites" checked>
                                        <label class="form-check-label" for="include_favorites">المفضلة</label>
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
                    <h5 class="card-title mb-0">إحصائيات البيانات</h5>
                </div>
                <div class="card-body" id="exportStats">
                    <div class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">جاري التحميل...</span>
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
                            <li>سيتم تصدير جميع البيانات المرتبطة بحسابك</li>
                            <li>يمكنك اختيار نطاق زمني محدد للتصدير</li>
                            <li>البيانات المصدرة آمنة ومشفرة</li>
                            <li>يمكنك طلب حذف بياناتك في أي وقت</li>
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
        // Load export stats
        loadExportStats();
        
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
    
    function loadExportStats() {
        fetch('{{ route("user.export.stats") }}')
            .then(response => response.json())
            .then(data => {
                document.getElementById('exportStats').innerHTML = `
                    <div class="row">
                        <div class="col-6">
                            <div class="mb-3">
                                <h6>الأنشطة</h6>
                                <h4 class="text-primary">${data.total_activities}</h4>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <h6>الإشعارات</h6>
                                <h4 class="text-info">${data.total_notifications}</h4>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <h6>الحجوزات</h6>
                                <h4 class="text-success">${data.total_bookings}</h4>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <h6>العقود</h6>
                                <h4 class="text-warning">${data.total_contracts}</h4>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <h6>الفواتير</h6>
                                <h4 class="text-danger">${data.total_invoices}</h4>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <h6>المدفوعات</h6>
                                <h4 class="text-secondary">${data.total_payments}</h4>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <h6>التعليقات</h6>
                                <h4 class="text-dark">${data.total_comments}</h4>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <h6>المفضلة</h6>
                                <h4 class="text-muted">${data.favorite_products_count + data.favorite_facilities_count}</h4>
                            </div>
                        </div>
                    </div>
                `;
            })
            .catch(error => {
                console.error('Error loading stats:', error);
                document.getElementById('exportStats').innerHTML = '<div class="alert alert-danger">خطأ في تحميل الإحصائيات</div>';
            });
    }
    
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
        
        fetch('{{ route("user.export.json") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('previewContent').innerHTML = `
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
        const format = formData.get('format');
        
        let url = '';
        switch(format) {
            case 'json':
                url = '{{ route("user.export.json") }}';
                break;
            case 'excel':
                url = '{{ route("user.export.excel") }}';
                break;
            case 'csv':
                url = '{{ route("user.export.csv") }}';
                break;
            case 'pdf':
                url = '{{ route("user.export.pdf") }}';
                break;
        }
        
        // Create a form and submit it
        const exportForm = document.createElement('form');
        exportForm.method = 'POST';
        exportForm.action = url;
        exportForm.style.display = 'none';
        
        // Add CSRF token
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        exportForm.appendChild(csrfInput);
        
        // Add form data
        for (let [key, value] of formData.entries()) {
            if (key !== 'format') {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = value;
                exportForm.appendChild(input);
            }
        }
        
        document.body.appendChild(exportForm);
        exportForm.submit();
        document.body.removeChild(exportForm);
    }
</script>
@endpush
