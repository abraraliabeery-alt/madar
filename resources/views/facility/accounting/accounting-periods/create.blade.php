@extends('facility.layouts.app')

@section('title', 'إضافة فترة محاسبية جديدة')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">إضافة فترة محاسبية جديدة</h3>
                    <a href="{{ route('facility.accounting.accounting-periods.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-right"></i> العودة للقائمة
                    </a>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('facility.accounting.accounting-periods.store') }}">
                        @csrf

                        <div class="row">
                            <!-- معلومات الفترة -->
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">معلومات الفترة</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">اسم الفترة <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                                   id="name" name="name" value="{{ old('name') }}" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">مثال: السنة المالية 2024، الربع الأول 2024</div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="start_date" class="form-label">تاريخ البداية <span class="text-danger">*</span></label>
                                                    <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                                                           id="start_date" name="start_date" value="{{ old('start_date') }}" required>
                                                    @error('start_date')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="end_date" class="form-label">تاريخ النهاية <span class="text-danger">*</span></label>
                                                    <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                                                           id="end_date" name="end_date" value="{{ old('end_date') }}" required>
                                                    @error('end_date')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="description" class="form-label">وصف الفترة</label>
                                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                                      id="description" name="description" rows="3">{{ old('description') }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- إعدادات إضافية -->
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">الإعدادات</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="is_current" name="is_current" value="1" 
                                                       {{ old('is_current') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_current">
                                                    فترة حالية
                                                </label>
                                            </div>
                                            <div class="form-text">تحديد هذه الفترة كفترة محاسبية حالية</div>
                                        </div>

                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="auto_close" name="auto_close" value="1" 
                                                       {{ old('auto_close') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="auto_close">
                                                    إغلاق تلقائي
                                                </label>
                                            </div>
                                            <div class="form-text">إغلاق الفترة تلقائياً عند انتهاء تاريخ النهاية</div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="status" class="form-label">حالة الفترة</label>
                                            <select class="form-select @error('status') is-invalid @enderror" 
                                                    id="status" name="status">
                                                <option value="open" {{ old('status', 'open') == 'open' ? 'selected' : '' }}>مفتوحة</option>
                                                <option value="closed" {{ old('status') == 'closed' ? 'selected' : '' }}>مغلقة</option>
                                                <option value="locked" {{ old('status') == 'locked' ? 'selected' : '' }}>مقفلة</option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- ملخص الفترة -->
                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">ملخص الفترة</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-2">
                                            <small class="text-muted">المدة:</small>
                                            <div id="duration-display" class="fw-bold">-</div>
                                        </div>
                                        <div class="mb-2">
                                            <small class="text-muted">الأيام المتبقية:</small>
                                            <div id="remaining-days" class="fw-bold">-</div>
                                        </div>
                                        <div class="mb-2">
                                            <small class="text-muted">نسبة الإنجاز:</small>
                                            <div class="progress" style="height: 8px;">
                                                <div id="progress-bar" class="progress-bar" role="progressbar" style="width: 0%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- أزرار الإجراءات -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('facility.accounting.accounting-periods.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times"></i> إلغاء
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> حفظ الفترة
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Calculate duration and remaining days
    function calculateDuration() {
        const startDate = document.getElementById('start_date').value;
        const endDate = document.getElementById('end_date').value;
        
        if (startDate && endDate) {
            const start = new Date(startDate);
            const end = new Date(endDate);
            const today = new Date();
            
            // Calculate duration in days
            const timeDiff = end.getTime() - start.getTime();
            const durationDays = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1;
            
            // Calculate remaining days
            const remainingTime = end.getTime() - today.getTime();
            const remainingDays = Math.ceil(remainingTime / (1000 * 3600 * 24));
            
            // Update display
            document.getElementById('duration-display').textContent = durationDays + ' يوم';
            document.getElementById('remaining-days').textContent = remainingDays + ' يوم';
            
            // Calculate progress
            const totalDays = durationDays;
            const passedDays = Math.max(0, Math.ceil((today.getTime() - start.getTime()) / (1000 * 3600 * 24)));
            const progress = Math.min(100, Math.max(0, (passedDays / totalDays) * 100));
            
            const progressBar = document.getElementById('progress-bar');
            progressBar.style.width = progress + '%';
            progressBar.textContent = Math.round(progress) + '%';
            
            // Color coding
            if (progress < 25) {
                progressBar.className = 'progress-bar bg-success';
            } else if (progress < 75) {
                progressBar.className = 'progress-bar bg-warning';
            } else {
                progressBar.className = 'progress-bar bg-danger';
            }
        } else {
            document.getElementById('duration-display').textContent = '-';
            document.getElementById('remaining-days').textContent = '-';
            document.getElementById('progress-bar').style.width = '0%';
        }
    }

    // Add event listeners
    document.getElementById('start_date').addEventListener('change', calculateDuration);
    document.getElementById('end_date').addEventListener('change', calculateDuration);

    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
        const startDate = document.getElementById('start_date').value;
        const endDate = document.getElementById('end_date').value;
        const name = document.getElementById('name').value;
        
        if (!name || !startDate || !endDate) {
            e.preventDefault();
            alert('يرجى ملء جميع الحقول المطلوبة');
            return false;
        }
        
        if (new Date(startDate) >= new Date(endDate)) {
            e.preventDefault();
            alert('تاريخ البداية يجب أن يكون قبل تاريخ النهاية');
            return false;
        }
    });

    // Auto-fill end date based on start date
    document.getElementById('start_date').addEventListener('change', function() {
        const startDate = new Date(this.value);
        if (startDate) {
            // Suggest end date (e.g., one year later)
            const endDate = new Date(startDate);
            endDate.setFullYear(endDate.getFullYear() + 1);
            endDate.setDate(endDate.getDate() - 1);
            
            document.getElementById('end_date').value = endDate.toISOString().split('T')[0];
            calculateDuration();
        }
    });
</script>
@endpush

@push('styles')
<style>
.card {
    border: none;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    border-radius: 10px;
}

.card-header {
    background: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
    border-radius: 10px 10px 0 0 !important;
}

.form-label {
    font-weight: 600;
    color: #495057;
}

.form-control, .form-select {
    border-radius: 0.375rem;
    border: 1px solid #ced4da;
}

.form-control:focus, .form-select:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.btn {
    border-radius: 0.375rem;
    font-weight: 500;
}

.progress {
    background-color: #e9ecef;
    border-radius: 0.375rem;
}

.progress-bar {
    transition: width 0.3s ease;
}

@media (max-width: 768px) {
    .card-body {
        padding: 1rem;
    }
    
    .d-flex.gap-2 {
        flex-direction: column;
    }
    
    .d-flex.gap-2 .btn {
        margin-bottom: 0.5rem;
    }
}
</style>
@endpush
