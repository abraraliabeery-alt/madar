@extends('layouts.app')

@section('content')
<div class="container" dir="rtl">
  <h1 class="mb-3">نتيجة الدراسة</h1>
  <a href="{{ route('client.ai.land-studies.form') }}" class="btn btn-secondary mb-3">دراسة جديدة</a>

  <div class="card mb-3">
    <div class="card-header">المدخلات</div>
    <div class="card-body">
      <pre class="mb-0">{{ json_encode($study->inputs, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) }}</pre>
    </div>
  </div>

  <div class="card mb-3">
    <div class="card-header">التقرير</div>
    <div class="card-body">
      <pre class="mb-0">{{ $study->report }}</pre>
    </div>
  </div>

  <div class="card mb-3">
    <div class="card-header">السيناريوهات</div>
    <div class="card-body">
      <pre class="mb-0">{{ json_encode($study->scenarios, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) }}</pre>
    </div>
  </div>

  <div class="card mb-3">
    <div class="card-header">التصاميم المفاهيمية</div>
    <div class="card-body">
      @if(is_array($study->images))
        @foreach($study->images as $group)
          <h5>{{ $group['scenario'] ?? '' }}</h5>
          <div class="d-flex gap-2 flex-wrap">
            @foreach(($group['urls'] ?? []) as $url)
              <img src="{{ $url }}" style="max-width:320px" />
            @endforeach
          </div>
        @endforeach
      @endif
    </div>
  </div>
</div>
@endsection
