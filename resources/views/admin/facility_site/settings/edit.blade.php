@extends('admin.layouts.app')

@section('title', 'إعدادات موقع المنشأة')

@section('content')
<div class="container-fluid">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h1 class="h4 m-0">إعدادات موقع المنشأة: {{ $facility->name }}</h1>
    <a href="{{ route('facility.site.home', $facility->slug ?? $facility->id) }}" class="btn btn-outline-secondary btn-sm">رجوع</a>
  </div>

  @if(session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
  @endif
  @if($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('facilities.site.settings.update', $facility) }}" method="POST" enctype="multipart/form-data" class="card shadow-sm">
    @csrf
    @method('PUT')
    <div class="card-header">
      <ul class="nav nav-tabs card-header-tabs" role="tablist">
        <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#tab-brand" role="tab">الهوية</a></li>
        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-social" role="tab">السوشال</a></li>
        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-seo" role="tab">SEO</a></li>
        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-landing" role="tab">الصفحة الرئيسية</a></li>
      </ul>
    </div>
    <div class="card-body">
      <div class="tab-content">
        <div class="tab-pane fade show active" id="tab-brand" role="tabpanel">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">اسم المنشأة</label>
              <input type="text" name="name" class="form-control" value="{{ old('name', $facility->name) }}">
            </div>
            <div class="col-md-3">
              <label class="form-label">الهاتف</label>
              <input type="text" name="phone" class="form-control" value="{{ old('phone', $facility->phone) }}">
            </div>
            <div class="col-md-3">
              <label class="form-label">البريد</label>
              <input type="email" name="email" class="form-control" value="{{ old('email', $facility->email) }}">
            </div>
            <div class="col-md-6">
              <label class="form-label">الموقع الإلكتروني</label>
              <input type="text" name="website" class="form-control" value="{{ old('website', $facility->website) }}">
            </div>
            <div class="col-md-3">
              <label class="form-label">اللون الأساسي</label>
              <input type="text" name="primary_color" class="form-control" value="{{ old('primary_color', $setting->primary_color ?? $facility->primary_color) }}" placeholder="#2563eb">
            </div>
            <div class="col-md-3">
              <label class="form-label">اللون الثانوي</label>
              <input type="text" name="secondary_color" class="form-control" value="{{ old('secondary_color', $setting->secondary_color ?? $facility->secondary_color) }}" placeholder="#1e40af">
            </div>
            <div class="col-md-6">
              <label class="form-label">الشعار</label>
              <input type="file" name="logo" class="form-control">
              @if($facility->logo_url)
                <div class="mt-2"><img src="{{ $facility->logo_url }}" height="40" alt="logo"></div>
              @endif
            </div>
            <div class="col-md-6">
              <label class="form-label">Favicon</label>
              <input type="file" name="favicon" class="form-control">
              @if(!empty($setting->favicon_path))
                <div class="mt-2"><img src="{{ asset('storage/'.$setting->favicon_path) }}" height="24" alt="favicon"></div>
              @endif
            </div>
          </div>
        </div>

        <div class="tab-pane fade" id="tab-social" role="tabpanel">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Facebook</label>
              <input type="text" name="facebook" class="form-control" value="{{ old('facebook', data_get($setting->social_links,'facebook')) }}">
            </div>
            <div class="col-md-6">
              <label class="form-label">Twitter (X)</label>
              <input type="text" name="twitter" class="form-control" value="{{ old('twitter', data_get($setting->social_links,'twitter')) }}">
            </div>
            <div class="col-md-6">
              <label class="form-label">Instagram</label>
              <input type="text" name="instagram" class="form-control" value="{{ old('instagram', data_get($setting->social_links,'instagram')) }}">
            </div>
            <div class="col-md-6">
              <label class="form-label">LinkedIn</label>
              <input type="text" name="linkedin" class="form-control" value="{{ old('linkedin', data_get($setting->social_links,'linkedin')) }}">
            </div>
            <div class="col-md-6">
              <label class="form-label">WhatsApp</label>
              <input type="text" name="whatsapp" class="form-control" value="{{ old('whatsapp', data_get($setting->social_links,'whatsapp')) }}" placeholder="https://wa.me/966...">
            </div>
          </div>
        </div>

        <div class="tab-pane fade" id="tab-seo" role="tabpanel">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">SEO Title</label>
              <input type="text" name="seo_title" class="form-control" value="{{ old('seo_title', $setting->seo_title) }}">
            </div>
            <div class="col-md-12">
              <label class="form-label">SEO Description</label>
              <textarea name="seo_description" class="form-control" rows="3">{{ old('seo_description', $setting->seo_description) }}</textarea>
            </div>
          </div>
        </div>

        <div class="tab-pane fade" id="tab-landing" role="tabpanel">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">صورة الهيرو</label>
              <input type="file" name="hero_image" class="form-control">
              @php($hero = data_get($setting->options,'hero_image'))
              @if($hero)
                <div class="mt-2"><img src="{{ asset('storage/'.$hero) }}" style="max-height:120px" alt="hero"></div>
              @endif
            </div>
            <div class="col-md-6">
              <label class="form-label">CTA Title</label>
              <input type="text" name="cta_title" class="form-control" value="{{ old('cta_title', data_get($setting->options,'cta_title')) }}">
            </div>
            <div class="col-md-12">
              <label class="form-label">CTA Subtitle</label>
              <input type="text" name="cta_subtitle" class="form-control" value="{{ old('cta_subtitle', data_get($setting->options,'cta_subtitle')) }}">
            </div>
            <div class="col-md-3 form-check mt-2">
              <input class="form-check-input" type="checkbox" name="show_kpis" value="1" {{ data_get($setting->options,'show_kpis', true) ? 'checked' : '' }}>
              <label class="form-check-label">إظهار KPIs</label>
            </div>
            <div class="col-md-3 form-check mt-2">
              <input class="form-check-input" type="checkbox" name="show_clients" value="1" {{ data_get($setting->options,'show_clients', true) ? 'checked' : '' }}>
              <label class="form-check-label">إظهار شريط العملاء</label>
            </div>
            <div class="col-md-3 form-check mt-2">
              <input class="form-check-input" type="checkbox" name="show_services" value="1" {{ data_get($setting->options,'show_services', true) ? 'checked' : '' }}>
              <label class="form-check-label">إظهار الخدمات</label>
            </div>
            <div class="col-md-3 form-check mt-2">
              <input class="form-check-input" type="checkbox" name="show_contact" value="1" {{ data_get($setting->options,'show_contact', true) ? 'checked' : '' }}>
              <label class="form-check-label">إظهار التواصل</label>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="card-footer text-end">
      <button class="btn btn-primary" type="submit">حفظ</button>
    </div>
  </form>
</div>
@endsection
