@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h4 class="mb-1">إدارة قطع المخطط</h4>
                <div class="text-muted">المخطط: <span class="fw-semibold">{{ $slug }}</span></div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('public.plans.ajlan') }}" target="_blank" class="btn btn-outline-secondary">فتح صفحة عجلان</a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <div class="fw-semibold mb-1">تحقق من المدخلات</div>
                <ul class="mb-0">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card mb-4">
            <div class="card-header fw-semibold">استيراد GeoJSON</div>
            <div class="card-body">
                <div class="d-flex flex-wrap gap-2 mb-3">
                    <form method="POST" action="{{ route('admin.plans.lots.import_from_extraction', ['slug' => $slug]) }}">
                        @csrf
                        <button class="btn btn-outline-primary" type="submit">استيراد من ملف الاستخراج</button>
                    </form>
                </div>
                <form method="POST" action="{{ route('admin.plans.lots.import', ['slug' => $slug]) }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">GeoJSON FeatureCollection (WGS84)</label>
                        <textarea name="geojson" rows="6" class="form-control" placeholder='{"type":"FeatureCollection","features":[...]}'></textarea>
                        <div class="form-text">يدعم property: <code>lot_number</code> أو <code>parcel_no</code> لتعيين رقم القطعة.</div>
                    </div>
                    <button class="btn btn-primary" type="submit">استيراد</button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span class="fw-semibold">القطع ({{ $lots->count() }})</span>
            </div>
            <div class="card-body">
                @if ($lots->count() === 0)
                    <div class="text-muted">لا توجد قطع بعد. استورد GeoJSON أولاً.</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead>
                            <tr>
                                <th style="width: 110px;">رقم القطعة</th>
                                <th style="width: 160px;">الحالة</th>
                                <th style="width: 160px;">السعر</th>
                                <th style="width: 160px;">الاستخدام</th>
                                <th style="width: 160px;">المساحة (م²)</th>
                                <th style="width: 140px;"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($lots as $lot)
                                <tr>
                                    <td class="fw-semibold">{{ $lot->lot_number }}</td>
                                    <td>
                                        <form method="POST" action="{{ route('admin.plans.lots.update', ['slug' => $slug, 'lot' => $lot->id]) }}" class="d-flex gap-2">
                                            @csrf
                                            <select name="status" class="form-select form-select-sm">
                                                <option value="available" @selected($lot->status === 'available')>متاح</option>
                                                <option value="reserved" @selected($lot->status === 'reserved')>محجوز</option>
                                                <option value="sold" @selected($lot->status === 'sold')>مباع</option>
                                            </select>
                                    </td>
                                    <td>
                                            <input name="price" class="form-control form-control-sm" value="{{ $lot->price }}" placeholder="مثال: 650000">
                                    </td>
                                    <td>
                                            <input name="usage" class="form-control form-control-sm" value="{{ $lot->usage }}" placeholder="سكني/تجاري">
                                    </td>
                                    <td>
                                            <input name="area_m2" class="form-control form-control-sm" value="{{ $lot->area_m2 }}" placeholder="540">
                                    </td>
                                    <td>
                                            <button class="btn btn-sm btn-primary" type="submit">حفظ</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
