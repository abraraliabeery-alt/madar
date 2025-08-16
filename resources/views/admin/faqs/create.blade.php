@extends('admin.layouts.app')

@section('title', 'إضافة سؤال جديد')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">إضافة سؤال جديد</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.faqs.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-right ml-1"></i>العودة للقائمة
                        </a>
                    </div>
                </div>
                <form action="{{ route('admin.faqs.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="question">السؤال <span class="text-danger">*</span></label>
                                    <textarea name="question" id="question" rows="3" 
                                              class="form-control @error('question') is-invalid @enderror" 
                                              placeholder="اكتب السؤال هنا..." required>{{ old('question') }}</textarea>
                                    @error('question')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="answer">الإجابة <span class="text-danger">*</span></label>
                                    <textarea name="answer" id="answer" rows="6" 
                                              class="form-control @error('answer') is-invalid @enderror" 
                                              placeholder="اكتب الإجابة هنا..." required>{{ old('answer') }}</textarea>
                                    @error('answer')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="category">الفئة</label>
                                    <input type="text" name="category" id="category" 
                                           class="form-control @error('category') is-invalid @enderror" 
                                           placeholder="مثال: عام، تقني، مالي" value="{{ old('category') }}">
                                    @error('category')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="locale">اللغة <span class="text-danger">*</span></label>
                                    <select name="locale" id="locale" 
                                            class="form-control @error('locale') is-invalid @enderror" required>
                                        <option value="">اختر اللغة</option>
                                        <option value="ar" {{ old('locale') == 'ar' ? 'selected' : '' }}>العربية</option>
                                        <option value="en" {{ old('locale') == 'en' ? 'selected' : '' }}>English</option>
                                    </select>
                                    @error('locale')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="order">الترتيب</label>
                                    <input type="number" name="order" id="order" 
                                           class="form-control @error('order') is-invalid @enderror" 
                                           placeholder="0" value="{{ old('order', 0) }}" min="0">
                                    @error('order')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <small class="form-text text-muted">الأرقام الأقل تظهر أولاً</small>
                                </div>

                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" 
                                               id="is_active" name="is_active" value="1" 
                                               {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_active">نشط</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save ml-1"></i>حفظ السؤال
                        </button>
                        <a href="{{ route('admin.faqs.index') }}" class="btn btn-secondary">
                            إلغاء
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
