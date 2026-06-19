<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>إدارة قطع المخطط</title>
    <style>
        :root {
            --bg: #0b1220;
            --card: #0f1b2d;
            --card2: #0c1626;
            --fg: #e5e7eb;
            --muted: rgba(229,231,235,.72);
            --border: rgba(148,163,184,.22);
            --primary: #2563eb;
            --primary2: rgba(37,99,235,.18);
            --success: rgba(34,197,94,.16);
            --danger: rgba(239,68,68,.14);
            --shadow: 0 16px 40px rgba(0,0,0,.35);
            --r-lg: 18px;
            --r-md: 14px;
            --r-sm: 12px;
        }

        * { box-sizing: border-box; }
        body { margin:0; font-family: ui-sans-serif, system-ui, -apple-system, "Segoe UI", Arial, sans-serif; background: radial-gradient(1200px 600px at 50% 0%, rgba(37,99,235,.10), transparent), var(--bg); color: var(--fg); }
        .wrap { max-width: 1180px; margin: 0 auto; padding: 22px; }

        .card { background: linear-gradient(180deg, rgba(255,255,255,.02), transparent), var(--card); border: 1px solid var(--border); border-radius: var(--r-lg); overflow: hidden; box-shadow: var(--shadow); }
        .card-h { padding: 16px 18px; border-bottom: 1px solid var(--border); display:flex; gap:12px; align-items:center; justify-content:space-between; }
        .card-b { padding: 18px; }

        .title { font-weight: 800; letter-spacing: .2px; }
        .small { font-size: 12px; color: var(--muted); }

        .btn { background: var(--primary); color: #fff; border: 0; padding: 10px 14px; border-radius: var(--r-md); cursor: pointer; font-weight: 700; }
        .btn:hover { filter: brightness(1.06); }
        .btn:active { transform: translateY(1px); }
        .btn-outline { background: transparent; color: var(--fg); border: 1px solid var(--border); }

        input, select, textarea {
            width:100%;
            background: rgba(255,255,255,.03);
            color: var(--fg);
            border:1px solid var(--border);
            border-radius: var(--r-md);
            padding: 10px 12px;
            outline: none;
            transition: border-color .15s ease;
        }
        input:focus, select:focus, textarea:focus { border-color: rgba(37,99,235,.55); }

        table { width:100%; border-collapse: separate; border-spacing: 0; }
        thead th {
            position: sticky;
            top: 0;
            background: linear-gradient(180deg, rgba(255,255,255,.03), transparent), var(--card);
            z-index: 1;
            color: var(--muted);
            font-weight: 800;
            font-size: 12px;
            text-transform: none;
        }
        th, td { padding: 12px 12px; border-bottom: 1px solid var(--border); text-align: right; vertical-align: middle; }
        tbody tr:nth-child(odd) { background: rgba(255,255,255,.015); }
        tbody tr:hover { background: rgba(37,99,235,.06); }

        .table-wrap { overflow: auto; border-radius: var(--r-lg); border: 1px solid var(--border); background: linear-gradient(180deg, rgba(255,255,255,.02), transparent), var(--card2); }

        td input, td select { padding: 9px 10px; border-radius: var(--r-md); }
        td .btn { padding: 8px 12px; border-radius: var(--r-md); }

        .msg { padding: 12px 14px; border-radius: var(--r-md); margin-bottom: 12px; border:1px solid var(--border); }
        .ok { background: var(--success); border-color: rgba(34,197,94,.35); }
        .err { background: var(--danger); border-color: rgba(239,68,68,.35); }

        .pager { display:flex; flex-wrap: wrap; gap: 6px; margin-top: 14px; }
        .pager a, .pager span { display:inline-block; padding: 8px 10px; border-radius: var(--r-md); border:1px solid var(--border); color: var(--fg); text-decoration:none; }
        .pager span[aria-current="page"] span { background: rgba(255,255,255,.06); }

        .coord { font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace; font-size: 12px; color: rgba(229,231,235,.92); }
        .btn-mini { background: var(--primary2); border: 1px solid rgba(37,99,235,.35); color: var(--fg); padding: 7px 10px; border-radius: var(--r-md); cursor: pointer; font-weight: 700; }

        .actions { display:flex; align-items:center; justify-content:space-between; gap: 12px; }
        .actions-right { display:flex; align-items:center; gap: 10px; }

        @media (max-width: 980px) {
            .wrap { padding: 14px; }
            .card-h { flex-direction: column; align-items: flex-start; }
            .actions { width: 100%; flex-direction: column; align-items: stretch; }
            .actions-right { width: 100%; }
        }

        @media (max-width: 900px) {
            table, thead, tbody, th, td, tr { display: block; }
            thead { display: none; }
            .table-wrap { border: 0; background: transparent; }
            tbody tr { border: 1px solid var(--border); border-radius: var(--r-lg); overflow: hidden; margin-bottom: 12px; background: linear-gradient(180deg, rgba(255,255,255,.02), transparent), var(--card2); }
            tbody tr:nth-child(odd) { background: linear-gradient(180deg, rgba(255,255,255,.02), transparent), var(--card2); }
            tbody tr:hover { background: linear-gradient(180deg, rgba(255,255,255,.03), transparent), var(--card2); }
            td { border-bottom: 1px solid var(--border); display:flex; align-items:center; justify-content:space-between; gap: 12px; }
            td::before { content: attr(data-label); color: var(--muted); font-size: 12px; font-weight: 800; flex: 0 0 110px; }
            td:last-child { border-bottom: 0; }
            td input, td select { max-width: 260px; }
        }
    </style>
</head>
<body>
<div class="wrap">
    <div class="card">
        <div class="card-h">
            <div>
                <div class="title">إدارة قطع المخطط</div>
                <div class="small">المخطط: <b>{{ $slug }}</b></div>
            </div>
            <div class="actions-right" style="justify-content:flex-end;">
                <a class="btn btn-outline" href="{{ route('public.plans.ajlan') }}" target="_blank" style="text-decoration:none;">فتح صفحة عجلان</a>
            </div>
        </div>
        <div class="card-b">
            @if (session('success'))
                <div class="msg ok">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="msg err">
                    @foreach ($errors->all() as $e)
                        <div>{{ $e }}</div>
                    @endforeach
                </div>
            @endif

            <div class="card">
                <div class="card-h">
                    <div class="actions">
                        <div class="title" style="font-size: 14px;">القطع ({{ $lots instanceof \Illuminate\Pagination\LengthAwarePaginator ? $lots->total() : (is_countable($lots) ? count($lots) : 0) }})</div>
                        <div class="actions-right" style="min-width:260px;">
                            <input id="lotSearch" placeholder="بحث برقم القطعة...">
                        </div>
                    </div>
                </div>
                <div class="card-b">
                    @if (!$plan)
                        <div class="small">لا يوجد مخطط بهذا المعرف.</div>
                    @elseif (($lots instanceof \Illuminate\Pagination\LengthAwarePaginator && $lots->total() === 0) || (is_countable($lots) && count($lots) === 0))
                        <div class="small">لا توجد قطع محفوظة في قاعدة البيانات لهذا المخطط.</div>
                    @else
                        <div class="table-wrap">
                            <table>
                                <thead>
                                <tr>
                                    <th style="min-width:90px;">رقم القطعة</th>
                                    <th style="min-width:170px;">رقم الإكسل</th>
                                    <th style="min-width:140px;">الحالة</th>
                                    <th style="min-width:140px;">السعر</th>
                                    <th style="min-width:140px;">الاستخدام</th>
                                    <th style="min-width:140px;">المساحة (م²)</th>
                                    <th style="min-width:240px;">الإحداثيات</th>
                                    <th style="min-width:110px;"></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($lots as $lot)
                                    <tr>
                                        @php($formId = 'lot-form-' . $lot->id)
                                        <form id="{{ $formId }}" method="POST" action="{{ url('/plans/'.$slug.'/lots-manage/'.$lot->id) }}">
                                            @csrf
                                        </form>
                                            <td data-label="رقم القطعة"><b>{{ $lot->lot_number }}</b></td>
                                            <td data-label="رقم الإكسل">
                                                <input name="excel_lot_number" form="{{ $formId }}" value="{{ $lot->excel_lot_number }}" placeholder="مثال: 1044">
                                            </td>
                                            <td data-label="الحالة">
                                                <select name="status" form="{{ $formId }}">
                                                    <option value="available" @selected($lot->status === 'available')>متاح</option>
                                                    <option value="reserved" @selected($lot->status === 'reserved')>محجوز</option>
                                                    <option value="sold" @selected($lot->status === 'sold')>مباع</option>
                                                </select>
                                            </td>
                                            <td data-label="السعر">
                                                <input name="price" form="{{ $formId }}" value="{{ $lot->price }}" placeholder="650000">
                                            </td>
                                            <td data-label="الاستخدام">
                                                <input name="usage" form="{{ $formId }}" value="{{ $lot->usage }}" placeholder="سكني/تجاري">
                                            </td>
                                            <td data-label="المساحة (م²)">
                                                <input name="area_m2" form="{{ $formId }}" value="{{ $lot->area_m2 }}" placeholder="540">
                                            </td>
                                            <td data-label="الإحداثيات">
                                                @php($ring = is_array($lot->geometry) ? ($lot->geometry['coordinates'][0] ?? null) : null)
                                                @php($centroidLat = null)
                                                @php($centroidLng = null)
                                                @if (is_array($ring) && count($ring) > 0)
                                                    @php($sumLng = 0.0)
                                                    @php($sumLat = 0.0)
                                                    @php($n = 0)
                                                    @foreach ($ring as $pt)
                                                        @if (is_array($pt) && count($pt) >= 2)
                                                            @php($sumLng += (float) $pt[0])
                                                            @php($sumLat += (float) $pt[1])
                                                            @php($n++)
                                                        @endif
                                                    @endforeach
                                                    @if ($n > 0)
                                                        @php($centroidLng = $sumLng / $n)
                                                        @php($centroidLat = $sumLat / $n)
                                                    @endif
                                                @endif

                                                @if (!is_null($centroidLat) && !is_null($centroidLng))
                                                    @php($coordText = number_format($centroidLat, 6, '.', '') . ', ' . number_format($centroidLng, 6, '.', ''))
                                                    <div class="row" style="gap:8px;">
                                                        <div class="coord" data-coord="{{ $coordText }}">{{ $coordText }}</div>
                                                        <button type="button" class="btn-mini" onclick="copyCoord(this)">نسخ</button>
                                                    </div>
                                                @else
                                                    <div class="small">غير متوفر</div>
                                                @endif
                                            </td>
                                            <td data-label="">
                                                <button class="btn" type="submit" form="{{ $formId }}">حفظ</button>
                                            </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if ($lots instanceof \Illuminate\Pagination\LengthAwarePaginator)
                            <div class="pager" style="margin-top: 14px;">
                                {{ $lots->links() }}
                            </div>
                        @endif
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>

<script>
  (function () {
    const input = document.getElementById('lotSearch');
    if (!input) return;
    input.addEventListener('input', function () {
      const q = String(input.value || '').trim();
      const rows = document.querySelectorAll('tbody tr');
      rows.forEach((tr) => {
        const firstCell = tr.querySelector('td');
        const text = (firstCell?.innerText || '').trim();
        tr.style.display = q === '' || text.includes(q) ? '' : 'none';
      });
    });
  })();

  function copyCoord(btn) {
    const row = btn.closest('td');
    const coord = row?.querySelector('[data-coord]')?.getAttribute('data-coord') || '';
    if (!coord) return;
    if (navigator.clipboard?.writeText) {
      navigator.clipboard.writeText(coord);
    }
  }
</script>
</body>
</html>
