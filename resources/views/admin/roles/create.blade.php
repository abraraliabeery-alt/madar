@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">إضافة دور جديد</h5>
            <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-right me-2"></i>رجوع
            </a>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.roles.store') }}" method="POST">
                @csrf

                <div class="row">
                    <!-- Basic Information -->
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">المعلومات الأساسية</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="name" class="form-label">اسم الدور <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">الوصف</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Permissions -->
                    <div class="col-md-12 mt-4">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">الصلاحيات</h6>
                                <div>
                                    <button type="button" class="btn btn-sm btn-secondary" id="selectAll">تحديد الكل</button>
                                    <button type="button" class="btn btn-sm btn-secondary" id="deselectAll">إلغاء تحديد الكل</button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    @foreach($permissions->groupBy('pages') as $page => $pagePermissions)
                                        <div class="col-md-6">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h6 class="mb-0">{{ $page }}</h6>
                                                </div>
                                                <div class="card-body">
                                                    @foreach($pagePermissions as $permission)
                                                        <div class="form-check mb-2">
                                                            <input type="checkbox" class="form-check-input permission-checkbox" id="permission_{{ $permission->id }}" name="permissions[]" value="{{ $permission->id }}" {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="permission_{{ $permission->id }}">
                                                                {{ $permission->name }}
                                                            </label>
                                                            @if($permission->description)
                                                                <small class="text-muted d-block">{{ $permission->description }}</small>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>حفظ
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Select/Deselect all permissions
    $('#selectAll').click(function() {
        $('.permission-checkbox').prop('checked', true);
    });

    $('#deselectAll').click(function() {
        $('.permission-checkbox').prop('checked', false);
    });
});
</script>
@endpush
