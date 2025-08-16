@extends('admin.layouts.app')

@section('title', 'إدارة الأسئلة الشائعة')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">الأسئلة الشائعة</h3>
                    <a href="{{ route('admin.faqs.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus ml-1"></i>إضافة سؤال جديد
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th>السؤال</th>
                                    <th>الإجابة</th>
                                    <th>الفئة</th>
                                    <th>اللغة</th>
                                    <th>الترتيب</th>
                                    <th>الحالة</th>
                                    <th style="width: 200px;">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody id="faqs-table">
                                @forelse($faqs as $faq)
                                    <tr data-id="{{ $faq->id }}">
                                        <td>{{ $faq->id }}</td>
                                        <td>
                                            <div class="text-truncate" style="max-width: 200px;" title="{{ $faq->question }}">
                                                {{ $faq->question }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-truncate" style="max-width: 300px;" title="{{ $faq->answer }}">
                                                {{ $faq->answer }}
                                            </div>
                                        </td>
                                        <td>{{ $faq->category ?? '-' }}</td>
                                        <td>
                                            <span class="badge badge-{{ $faq->locale == 'ar' ? 'primary' : 'info' }}">
                                                {{ $faq->locale == 'ar' ? 'العربية' : 'English' }}
                                            </span>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm order-input" 
                                                   value="{{ $faq->order }}" min="0" style="width: 80px;">
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $faq->is_active ? 'success' : 'danger' }}">
                                                {{ $faq->is_active ? 'نشط' : 'غير نشط' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.faqs.edit', $faq) }}" 
                                                   class="btn btn-sm btn-info" title="تعديل">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.faqs.toggle-status', $faq) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-{{ $faq->is_active ? 'warning' : 'success' }}" 
                                                            title="{{ $faq->is_active ? 'إلغاء التفعيل' : 'تفعيل' }}">
                                                        <i class="fas fa-{{ $faq->is_active ? 'eye-slash' : 'eye' }}"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.faqs.destroy', $faq) }}" 
                                                      method="POST" class="d-inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" 
                                                            title="حذف" onclick="return confirm('هل أنت متأكد من الحذف؟')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">لا توجد أسئلة شائعة</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Handle order changes
    $('.order-input').on('change', function() {
        const row = $(this).closest('tr');
        const faqId = row.data('id');
        const newOrder = $(this).val();
        
        // Update order via AJAX
        $.ajax({
            url: '{{ route("admin.faqs.update-order") }}',
            method: 'POST',
            data: {
                faqs: [{
                    id: faqId,
                    order: newOrder
                }],
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    // Optionally refresh the page or show success message
                    location.reload();
                }
            },
            error: function() {
                alert('حدث خطأ أثناء تحديث الترتيب');
            }
        });
    });
});
</script>
@endpush
@endsection
