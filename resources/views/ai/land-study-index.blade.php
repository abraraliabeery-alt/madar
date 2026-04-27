@extends('layouts.app')

@section('content')
<div class="container" dir="rtl">
  <h1 class="mb-3">الدراسات</h1>
  <a href="{{ route('client.ai.land-studies.form') }}" class="btn btn-primary mb-3">دراسة جديدة</a>
  <div class="list-group">
    @foreach($studies as $s)
      <a class="list-group-item list-group-item-action" href="{{ route('client.ai.land-studies.show', $s->id) }}">
        #{{ $s->id }} - {{ $s->inputs['location'] ?? 'بدون عنوان' }} - {{ $s->status }}
      </a>
    @endforeach
  </div>
  <div class="mt-3">{{ $studies->links() }}</div>
</div>
@endsection
