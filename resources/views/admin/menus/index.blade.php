@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">إدارة القوائم</h5>
                <div class="text-muted small">تحكم بالتفعيل والترتيب والظهور حسب وضع المنصة</div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.menus.index', ['panel' => 'public']) }}" class="btn btn-light {{ $panel === 'public' ? 'active' : '' }}">الموقع</a>
                <a href="{{ route('admin.menus.index', ['panel' => 'admin']) }}" class="btn btn-light {{ $panel === 'admin' ? 'active' : '' }}">الأدمن</a>
                <a href="{{ route('admin.menus.index', ['panel' => 'facility']) }}" class="btn btn-light {{ $panel === 'facility' ? 'active' : '' }}">المنشأة</a>
                <a href="{{ route('admin.menus.index', ['panel' => 'client']) }}" class="btn btn-light {{ $panel === 'client' ? 'active' : '' }}">العميل</a>
            </div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.menus.update') }}">
                @csrf
                <input type="hidden" name="panel" value="{{ $panel }}">

                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead>
                            <tr>
                                <th style="width:70px">ترتيب</th>
                                <th style="width:220px">العنصر</th>
                                <th style="width:120px">مفعل</th>
                                <th>الظهور حسب المنصة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $i => $item)
                                @php($modes = data_get($item->visibility, 'modes'))
                                @php($translated = __($item->label_key))
                                <tr>
                                    <td>
                                        <input type="hidden" name="items[{{ $i }}][id]" value="{{ $item->id }}">
                                        <input type="number" class="form-control" name="items[{{ $i }}][sort_order]" value="{{ old("items.$i.sort_order", $item->sort_order) }}" min="0">
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <div class="fw-semibold">{{ $translated === $item->label_key ? 'غير مترجم' : $translated }}</div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-switch d-inline-flex">
                                            <input class="form-check-input" type="checkbox" name="items[{{ $i }}][enabled]" value="1" {{ old("items.$i.enabled", $item->enabled) ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="m_{{ $item->id }}_re" name="items[{{ $i }}][visibility_modes][]" value="real_estate" {{ is_array($modes) && in_array('real_estate', $modes, true) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="m_{{ $item->id }}_re">عقارات</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="m_{{ $item->id }}_co" name="items[{{ $i }}][visibility_modes][]" value="contracting" {{ is_array($modes) && in_array('contracting', $modes, true) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="m_{{ $item->id }}_co">مقاولات</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="m_{{ $item->id }}_li" name="items[{{ $i }}][visibility_modes][]" value="lifecycle" {{ is_array($modes) && in_array('lifecycle', $modes, true) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="m_{{ $item->id }}_li">هجين</label>
                                            </div>
                                        </div>
                                        <div class="text-muted small mt-2">إذا ما اخترت أي وضع: يظهر في كل الأوضاع.</div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="text-end">
                    <button class="btn btn-primary" type="submit">حفظ</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
