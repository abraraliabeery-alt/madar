@php
    $brandColor = $facility->primary_color ?? '#2563eb';
    $brandName = $facility->name;
    $brandLogo = $facility->logo_url;
@endphp
<div class="page-header" style="display:flex;align-items:center;justify-content:space-between;border-bottom:2px solid {{ $brandColor }};padding:6mm 15mm;height:20mm">
  <div class="text-sm" style="color:#4b5563">{{ $brandName }}</div>
  @if($brandLogo)
    <img src="{{ $brandLogo }}" onerror="this.style.display='none'" alt="logo" style="height:24px">
  @endif
</div>
