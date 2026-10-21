<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>فاحص امتثال المناقصات</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet" />
    <style>
        body { font-family: 'Tajawal', 'Cairo', 'Segoe UI', Tahoma, Arial, sans-serif; background: #0f172a; color: #e5e7eb; margin: 0; padding: 18px; }
        .wrap { max-width: 900px; margin: 0 auto; }
        h1 { margin: 0 0 6px; text-align: center; }
        .sub { opacity: .85; text-align: center; line-height: 1.7; margin: 0 0 20px; }
        .card { background: #020617; border: 1px solid #243043; border-radius: 14px; padding: 18px; box-shadow: 0 10px 24px rgba(0,0,0,.35); margin-bottom: 16px; }
        label { display: block; margin-bottom: 6px; font-weight: 700; }
        textarea { width: 100%; box-sizing: border-box; min-height: 220px; padding: 12px; border-radius: 10px; border: 1px solid #334155; background: #0b1220; color: #e5e7eb; font-family: inherit; font-size: 15px; resize: vertical; }
        button { padding: 12px 20px; border-radius: 10px; border: none; cursor: pointer; font-weight: 800; background: #2563eb; color: #fff; font-family: inherit; font-size: 15px; }
        button:hover { background: #1d4ed8; }
        .error { color: #f87171; background: rgba(239,68,68,.12); border: 1px solid rgba(239,68,68,.25); border-radius: 10px; padding: 12px; margin-top: 14px; }
        .summary { color: #cbd5e1; line-height: 1.8; margin-bottom: 18px; }
        table { width: 100%; border-collapse: collapse; margin-top: 14px; font-size: 14px; }
        th, td { border: 1px solid #334155; padding: 10px; text-align: right; }
        th { background: #0b1220; }
        tr.good { background: rgba(16,185,129,.15); }
        tr.warn { background: rgba(245,158,11,.15); }
        tr.danger { background: rgba(239,68,68,.15); }
        tr.unknown { background: rgba(148,163,184,.15); }
        .muted { opacity: .8; font-size: 13px; }
        .back { display: inline-block; margin-top: 16px; color: #93c5fd; text-decoration: none; }
        .back:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="wrap">
        <h1>فاحص امتثال المناقصات</h1>
        <p class="sub">الصق كراسة الشروط وسيستخرج الذكاء الاصطناعي المتطلبات ويحدد حالة الامتثال.</p>

        <form method="POST" action="{{ route('public.tender-compliance') }}" class="card">
            @csrf
            <label for="tender_text">نص كراسة الشروط</label>
            <textarea id="tender_text" name="tender_text" placeholder="الصق نص المناقصة هنا...">{{ old('tender_text', $tenderText) }}</textarea>

            <div style="margin-top: 14px;">
                <button type="submit">فحص الامتثال</button>
            </div>

            @if($error)
                <div class="error">{{ $error }}</div>
            @endif
        </form>

        @if($result)
            <div class="card">
                <h2 style="margin-top: 0;">نتيجة التحليل</h2>

                @if(!empty($result['summary']))
                    <div class="summary">{{ $result['summary'] }}</div>
                @endif

                @if(!empty($result['requirements']))
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>المتطلب</th>
                                <th>الأولوية</th>
                                <th>الحالة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($result['requirements'] as $index => $requirement)
                                @php($status = $requirement['compliance_status'] ?? 'unknown')
                                <tr class="{{ $status }}">
                                    <td>{{ $requirement['id'] ?? ($index + 1) }}</td>
                                    <td>
                                        <strong>{{ $requirement['title'] ?? '—' }}</strong><br>
                                        <span class="muted">{{ $requirement['description'] ?? '' }}</span>
                                    </td>
                                    <td>{{ $requirement['priority'] ?? '—' }}</td>
                                    <td>
                                        {{ match($status) {
                                            'compliant' => 'متوافق',
                                            'partial' => 'جزئي',
                                            'non_compliant' => 'غير متوافق',
                                            default => 'غير محدد',
                                        } }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="muted">لم يتم استخراج متطلبات واضحة.</p>
                @endif
            </div>
        @endif

        <a href="/" class="back">العودة للرئيسية</a>
    </div>
</body>
</html>
