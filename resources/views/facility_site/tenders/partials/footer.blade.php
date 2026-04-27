@php
    $brandColor = $facility->primary_color ?? '#2563eb';
@endphp
<div class="page-footer" style="display:flex;align-items:center;justify-content:space-between;border-top:2px solid {{ $brandColor }};padding:4mm 15mm;height:15mm;font-size:10px;color:#4b5563">
  <div>{{ $facility->website ?? '' }} | {{ $facility->email ?? '' }}</div>
  <div>الهاتف: {{ $facility->phone ?? '' }}</div>
</div>
