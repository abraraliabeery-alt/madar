<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تصدير بيانات المستخدم - {{ $user->name }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            direction: rtl;
            text-align: right;
            margin: 0;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #007bff;
        }
        .header h1 {
            color: #007bff;
            margin: 0;
            font-size: 28px;
        }
        .header p {
            color: #6c757d;
            margin: 5px 0 0 0;
        }
        .section {
            margin-bottom: 25px;
        }
        .section h2 {
            color: #343a40;
            font-size: 20px;
            margin-bottom: 15px;
            padding: 10px;
            background-color: #f8f9fa;
            border-right: 4px solid #007bff;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }
        .info-item {
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        .info-label {
            font-weight: bold;
            color: #495057;
            margin-bottom: 5px;
        }
        .info-value {
            color: #212529;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        .table th,
        .table td {
            padding: 12px;
            text-align: right;
            border: 1px solid #dee2e6;
        }
        .table th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }
        .table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin: 20px 0;
        }
        .stat-card {
            text-align: center;
            padding: 15px;
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            border-radius: 8px;
        }
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .stat-label {
            font-size: 14px;
            opacity: 0.9;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            text-align: center;
            color: #6c757d;
            font-size: 12px;
        }
        .no-data {
            text-align: center;
            color: #6c757d;
            font-style: italic;
            padding: 20px;
        }
        .json-data {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            white-space: pre-wrap;
            max-height: 300px;
            overflow-y: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>تصدير بيانات المستخدم</h1>
            <p>تم إنشاء هذا التقرير في {{ now()->format('Y-m-d H:i:s') }}</p>
        </div>

        <!-- User Information -->
        <div class="section">
            <h2>معلومات المستخدم</h2>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">الاسم</div>
                    <div class="info-value">{{ $user->name }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">البريد الإلكتروني</div>
                    <div class="info-value">{{ $user->email }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">رقم الهاتف</div>
                    <div class="info-value">{{ $user->phone ?? 'غير محدد' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">الموقع</div>
                    <div class="info-value">{{ $user->location ?? 'غير محدد' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">تاريخ الميلاد</div>
                    <div class="info-value">{{ $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : 'غير محدد' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">الجنس</div>
                    <div class="info-value">
                        @if($user->gender)
                            @switch($user->gender)
                                @case('male') ذكر @break
                                @case('female') أنثى @break
                                @case('other') آخر @break
                            @endswitch
                        @else
                            غير محدد
                        @endif
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">تاريخ الإنشاء</div>
                    <div class="info-value">{{ $user->created_at->format('Y-m-d H:i:s') }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">آخر تحديث</div>
                    <div class="info-value">{{ $user->updated_at->format('Y-m-d H:i:s') }}</div>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        @if(isset($data['statistics']))
        <div class="section">
            <h2>الإحصائيات</h2>
            <div class="stats-grid">
                @if(isset($data['statistics']['basic']))
                <div class="stat-card">
                    <div class="stat-number">{{ $data['statistics']['basic']['account_age'] ?? 0 }}</div>
                    <div class="stat-label">عمر الحساب (يوم)</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $data['statistics']['basic']['total_logins'] ?? 0 }}</div>
                    <div class="stat-label">إجمالي تسجيلات الدخول</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $data['statistics']['basic']['profile_completion'] ?? 0 }}%</div>
                    <div class="stat-label">اكتمال الملف الشخصي</div>
                </div>
                @endif
                
                @if(isset($data['statistics']['activity']))
                <div class="stat-card">
                    <div class="stat-number">{{ $data['statistics']['activity']['total_activities'] ?? 0 }}</div>
                    <div class="stat-label">إجمالي الأنشطة</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $data['statistics']['activity']['activities_last_30_days'] ?? 0 }}</div>
                    <div class="stat-label">الأنشطة آخر 30 يوم</div>
                </div>
                @endif
                
                @if(isset($data['statistics']['financial']))
                <div class="stat-card">
                    <div class="stat-number">{{ $data['statistics']['financial']['total_contracts'] ?? 0 }}</div>
                    <div class="stat-label">إجمالي العقود</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $data['statistics']['financial']['total_payments'] ?? 0 }}</div>
                    <div class="stat-label">إجمالي المدفوعات</div>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Activities -->
        @if(isset($data['activities']) && count($data['activities']) > 0)
        <div class="section">
            <h2>سجل النشاط</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>الإجراء</th>
                        <th>الوصف</th>
                        <th>عنوان IP</th>
                        <th>التاريخ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['activities'] as $activity)
                    <tr>
                        <td>{{ $activity['action'] }}</td>
                        <td>{{ $activity['description'] }}</td>
                        <td>{{ $activity['ip_address'] }}</td>
                        <td>{{ \Carbon\Carbon::parse($activity['created_at'])->format('Y-m-d H:i:s') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <!-- Notifications -->
        @if(isset($data['notifications']) && count($data['notifications']) > 0)
        <div class="section">
            <h2>الإشعارات</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>النوع</th>
                        <th>العنوان</th>
                        <th>الرسالة</th>
                        <th>تاريخ القراءة</th>
                        <th>تاريخ الإنشاء</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['notifications'] as $notification)
                    <tr>
                        <td>{{ $notification['type'] }}</td>
                        <td>{{ $notification['title'] }}</td>
                        <td>{{ Str::limit($notification['message'], 50) }}</td>
                        <td>{{ $notification['read_at'] ? \Carbon\Carbon::parse($notification['read_at'])->format('Y-m-d H:i:s') : 'غير مقروء' }}</td>
                        <td>{{ \Carbon\Carbon::parse($notification['created_at'])->format('Y-m-d H:i:s') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <!-- Bookings -->
        @if(isset($data['bookings']) && count($data['bookings']) > 0)
        <div class="section">
            <h2>الحجوزات</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>العقار</th>
                        <th>المنشأة</th>
                        <th>الحالة</th>
                        <th>تاريخ البداية</th>
                        <th>تاريخ النهاية</th>
                        <th>المبلغ الإجمالي</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['bookings'] as $booking)
                    <tr>
                        <td>{{ $booking['product_id'] }}</td>
                        <td>{{ $booking['facility_id'] }}</td>
                        <td>{{ $booking['status'] }}</td>
                        <td>{{ $booking['start_date'] }}</td>
                        <td>{{ $booking['end_date'] }}</td>
                        <td>{{ number_format($booking['total_amount'], 2) }} ريال</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <!-- Contracts -->
        @if(isset($data['contracts']) && count($data['contracts']) > 0)
        <div class="section">
            <h2>العقود</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>العقار</th>
                        <th>المنشأة</th>
                        <th>الحالة</th>
                        <th>تاريخ البداية</th>
                        <th>تاريخ النهاية</th>
                        <th>المبلغ الإجمالي</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['contracts'] as $contract)
                    <tr>
                        <td>{{ $contract['product_id'] }}</td>
                        <td>{{ $contract['facility_id'] }}</td>
                        <td>{{ $contract['status'] }}</td>
                        <td>{{ $contract['start_date'] }}</td>
                        <td>{{ $contract['end_date'] }}</td>
                        <td>{{ number_format($contract['total_amount'], 2) }} ريال</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <!-- Invoices -->
        @if(isset($data['invoices']) && count($data['invoices']) > 0)
        <div class="section">
            <h2>الفواتير</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>العقد</th>
                        <th>الحالة</th>
                        <th>المبلغ</th>
                        <th>تاريخ الاستحقاق</th>
                        <th>تاريخ الدفع</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['invoices'] as $invoice)
                    <tr>
                        <td>{{ $invoice['contract_id'] }}</td>
                        <td>{{ $invoice['status'] }}</td>
                        <td>{{ number_format($invoice['amount'], 2) }} ريال</td>
                        <td>{{ $invoice['due_date'] }}</td>
                        <td>{{ $invoice['paid_at'] ? \Carbon\Carbon::parse($invoice['paid_at'])->format('Y-m-d H:i:s') : 'غير مدفوع' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <!-- Payments -->
        @if(isset($data['payments']) && count($data['payments']) > 0)
        <div class="section">
            <h2>المدفوعات</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>الفاتورة</th>
                        <th>المبلغ</th>
                        <th>طريقة الدفع</th>
                        <th>الحالة</th>
                        <th>تاريخ الإنشاء</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['payments'] as $payment)
                    <tr>
                        <td>{{ $payment['invoice_id'] }}</td>
                        <td>{{ number_format($payment['amount'], 2) }} ريال</td>
                        <td>{{ $payment['method'] }}</td>
                        <td>{{ $payment['status'] }}</td>
                        <td>{{ \Carbon\Carbon::parse($payment['created_at'])->format('Y-m-d H:i:s') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <!-- Comments -->
        @if(isset($data['comments']) && count($data['comments']) > 0)
        <div class="section">
            <h2>التعليقات</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>العقار</th>
                        <th>المحتوى</th>
                        <th>التقييم</th>
                        <th>تاريخ الإنشاء</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['comments'] as $comment)
                    <tr>
                        <td>{{ $comment['product_id'] }}</td>
                        <td>{{ Str::limit($comment['content'], 100) }}</td>
                        <td>{{ $comment['rating'] }}/5</td>
                        <td>{{ \Carbon\Carbon::parse($comment['created_at'])->format('Y-m-d H:i:s') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <!-- Favorites -->
        @if(isset($data['favorite_products']) && count($data['favorite_products']) > 0)
        <div class="section">
            <h2>العقارات المفضلة</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>الاسم</th>
                        <th>النوع</th>
                        <th>السعر</th>
                        <th>الموقع</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['favorite_products'] as $product)
                    <tr>
                        <td>{{ $product['name'] }}</td>
                        <td>{{ $product['type'] }}</td>
                        <td>{{ number_format($product['price'], 2) }} ريال</td>
                        <td>{{ $product['location'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        @if(isset($data['favorite_facilities']) && count($data['favorite_facilities']) > 0)
        <div class="section">
            <h2>المرافق المفضلة</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>الاسم</th>
                        <th>النوع</th>
                        <th>الموقع</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['favorite_facilities'] as $facility)
                    <tr>
                        <td>{{ $facility['name'] }}</td>
                        <td>{{ $facility['type'] }}</td>
                        <td>{{ $facility['location'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <!-- Raw Data (if requested) -->
        @if(isset($data['export_info']['options']['include_raw_data']) && $data['export_info']['options']['include_raw_data'])
        <div class="section">
            <h2>البيانات الخام</h2>
            <div class="json-data">{{ json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</div>
        </div>
        @endif

        <div class="footer">
            <p>تم إنشاء هذا التقرير تلقائياً من نظام إدارة العقارات</p>
            <p>تاريخ الإنشاء: {{ now()->format('Y-m-d H:i:s') }}</p>
            <p>جميع الحقوق محفوظة © {{ date('Y') }}</p>
        </div>
    </div>
</body>
</html>
