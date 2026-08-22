<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductRequest;
use App\Models\User;
use App\Services\AI\ProductRequestAiService;
use Carbon\Carbon;

class ProductRequestController extends Controller
{
    public function __construct(private ProductRequestAiService $ai)
    {
    }
    public function index(Request $request)
    {
        $query = ProductRequest::with('assignedTo')->latest();

        if ($request->filled('status')) {
            $query->status($request->status);
        }

        if ($request->filled('priority')) {
            $query->priority($request->priority);
        }

        if ($request->filled('assigned_to')) {
            $query->assignedTo($request->assigned_to);
        }

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $requests = $query->paginate(20)->withQueryString();
        $statuses = ProductRequest::statuses();
        $priorities = ProductRequest::priorities();
        $admins = User::whereHas('roles', function ($q) {
            $q->where('name', 'admin');
        })->orWhere('primary_role', 'admin')->orderBy('name')->get(['id', 'name']);

        $stats = [
            'total' => ProductRequest::count(),
            'open' => ProductRequest::open()->count(),
            'new' => ProductRequest::where('status', ProductRequest::STATUS_NEW)->count(),
            'resolved' => ProductRequest::where('status', ProductRequest::STATUS_RESOLVED)->count(),
        ];

        return view('admin.product-requests.index', compact(
            'requests',
            'statuses',
            'priorities',
            'admins',
            'stats'
        ));
    }

    public function show(ProductRequest $productRequest)
    {
        $productRequest->load('assignedTo');
        $statuses = ProductRequest::statuses();
        $priorities = ProductRequest::priorities();
        $admins = User::whereHas('roles', function ($q) {
            $q->where('name', 'admin');
        })->orWhere('primary_role', 'admin')->orderBy('name')->get(['id', 'name']);

        return view('admin.product-requests.show', compact('productRequest', 'statuses', 'priorities', 'admins'));
    }

    public function update(Request $request, ProductRequest $productRequest)
    {
        $validated = $request->validate([
            'status' => 'required|in:' . implode(',', array_keys(ProductRequest::statuses())),
            'priority' => 'required|in:' . implode(',', array_keys(ProductRequest::priorities())),
            'assigned_to' => 'nullable|exists:users,id',
            'admin_notes' => 'nullable|string|max:5000',
        ]);

        if ($validated['status'] === ProductRequest::STATUS_CONTACTED && $productRequest->contacted_at === null) {
            $productRequest->contacted_at = Carbon::now();
        }

        if (in_array($validated['status'], [ProductRequest::STATUS_CLOSED, ProductRequest::STATUS_REJECTED, ProductRequest::STATUS_RESOLVED], true) && $productRequest->closed_at === null) {
            $productRequest->closed_at = Carbon::now();
        }

        $productRequest->fill($validated);
        $productRequest->save();

        return redirect()->route('admin.product-requests.index')
            ->with('success', 'تم تحديث طلب المنتج بنجاح.');
    }

    public function updateStatus(Request $request, ProductRequest $productRequest)
    {
        $validated = $request->validate([
            'status' => 'required|in:' . implode(',', array_keys(ProductRequest::statuses())),
        ]);

        if ($validated['status'] === ProductRequest::STATUS_CONTACTED && $productRequest->contacted_at === null) {
            $productRequest->contacted_at = Carbon::now();
        }

        if (in_array($validated['status'], [ProductRequest::STATUS_CLOSED, ProductRequest::STATUS_REJECTED, ProductRequest::STATUS_RESOLVED], true) && $productRequest->closed_at === null) {
            $productRequest->closed_at = Carbon::now();
        }

        $productRequest->status = $validated['status'];
        $productRequest->save();

        return redirect()->back()->with('success', 'تم تحديث الحالة إلى: ' . ProductRequest::statuses()[$validated['status']]);
    }

    public function aiMatches(ProductRequest $productRequest)
    {
        $result = $this->ai->match($productRequest);

        try {
            $result['reply'] = $result['matches'] === []
                ? ''
                : $this->ai->suggestReply($productRequest, $result['matches']);
        } catch (\Throwable $e) {
            report($e);
            $result['reply'] = '';
        }

        return response()->json([
            'success' => true,
            'data' => $result,
        ]);
    }

    public function create()
    {
        $statuses = ProductRequest::statuses();
        $priorities = ProductRequest::priorities();
        $admins = User::whereHas('roles', function ($q) {
            $q->where('name', 'admin');
        })->orWhere('primary_role', 'admin')->orderBy('name')->get(['id', 'name']);

        return view('admin.product-requests.create', compact('statuses', 'priorities', 'admins'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'description' => 'required|string|max:4000',
            'status' => 'required|in:' . implode(',', array_keys(ProductRequest::statuses())),
            'priority' => 'required|in:' . implode(',', array_keys(ProductRequest::priorities())),
            'assigned_to' => 'nullable|exists:users,id',
            'admin_notes' => 'nullable|string|max:5000',
            'extracted' => 'nullable|array',
        ]);

        $extracted = $this->ai->extractFromText($validated['description']);
        $extracted['phone'] = $validated['phone'];

        $productRequest = ProductRequest::create([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'description' => $validated['description'],
            'extracted' => $extracted,
            'status' => $validated['status'],
            'priority' => $validated['priority'],
            'assigned_to' => $validated['assigned_to'],
            'admin_notes' => $validated['admin_notes'] ?? null,
            'source' => 'admin',
        ]);

        return redirect()->route('admin.product-requests.show', $productRequest)
            ->with('success', 'تم إضافة طلب المنتج بنجاح.');
    }

    public function analyzeText(Request $request)
    {
        $data = $request->validate(['description' => 'required|string|max:4000']);

        return response()->json([
            'success' => true,
            'extracted' => $this->ai->extractFromText($data['description']),
        ]);
    }

    public function analyzeImage(Request $request)
    {
        $data = $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        return response()->json([
            'success' => true,
            'extracted' => $this->ai->extractFromImage($data['image']),
        ]);
    }

    public function importWhatsApp(Request $request)
    {
        $data = $request->validate([
            'chat_file' => 'required|file|mimetypes:text/plain|max:2048',
        ]);

        $file = $data['chat_file'];
        $content = (string) file_get_contents($file->getRealPath());

        $messages = $this->extractTodayMessages($content);
        $created = 0;
        $today = now()->toDateString();

        foreach ($messages as $message) {
            $extracted = $this->ai->extractFromText($message['body']);

            ProductRequest::create([
                'name' => $message['sender'],
                'phone' => $extracted['phone'] ?: ($message['phone'] ?: null),
                'description' => $message['body'],
                'extracted' => $extracted,
                'status' => ProductRequest::STATUS_NEW,
                'priority' => ProductRequest::PRIORITY_NORMAL,
                'source' => 'whatsapp_import',
            ]);

            $created++;
        }

        return response()->json([
            'success' => true,
            'created' => $created,
            'message' => "تم استيراد {$created} طلب من رسائل واتساب لليوم ({$today}).",
        ]);
    }

    private function extractTodayMessages(string $content): array
    {
        $lines = preg_split('/\r\n|\r|\n/', $content);
        $messages = [];
        $current = null;

        foreach ($lines as $line) {
            // Try to detect a new message line: [date, time] sender: body
            // WhatsApp formats vary; try a flexible regex.
            if (preg_match('/^\[(.*?)\]\s*(.*?)\s*:\s*(.*)$/u', $line, $m)) {
                if ($current) {
                    $messages[] = $current;
                }
                $dateString = $this->cleanDateString($m[1]);
                $current = [
                    'date' => $this->parseDate($dateString),
                    'sender' => trim($m[2]),
                    'body' => trim($m[3]),
                    'phone' => null,
                ];
                $current['phone'] = $this->extractPhone($current['sender']) ?: $this->extractPhone($current['body']);
            } elseif ($current !== null && trim($line) !== '') {
                $current['body'] .= "\n" . trim($line);
            }
        }

        if ($current) {
            $messages[] = $current;
        }

        return array_values(array_filter($messages, function ($m) {
            return $m['date'] !== null && $m['date']->isToday() && $this->looksLikePropertyRequest($m['body']);
        }));
    }

    private function cleanDateString(string $str): string
    {
        // Remove LRM/RLM and other invisible characters
        return preg_replace('/[\x{200E}\x{200F}\x{202A}-\x{202E}\x{2066}-\x{2069}]/u', '', str_replace(['‏', '‎'], '', $str));
    }

    private function parseDate(string $str): ?\Carbon\Carbon
    {
        $str = trim($str);
        $formats = ['d/m/Y', 'd/m/y', 'm/d/Y', 'm/d/y', 'Y-m-d', 'y-m-d', 'd-m-Y', 'd-m-y', 'Y/m/d', 'y/m/d'];

        foreach ($formats as $format) {
            $d = \Carbon\Carbon::createFromFormat($format, $str);
            if ($d !== false && $d->year > 2000) {
                return $d->startOfDay();
            }
        }

        try {
            return \Carbon\Carbon::parse($str)->startOfDay();
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function extractPhone(string $text): ?string
    {
        if (preg_match('/(?:\+|0)?(?:966|05|5)\s?\d{1,2}[\s\-]?\d{3}[\s\-]?\d{4}/', $text, $m)) {
            return preg_replace('/\s/', '', $m[0]);
        }
        if (preg_match('/\+?\d{9,15}/', $text, $m)) {
            return preg_replace('/\s/', '', $m[0]);
        }

        return null;
    }

    private function looksLikePropertyRequest(string $body): bool
    {
        $keywords = ['شقة', 'فيلا', 'أرض', 'استوديو', 'محل', 'عمارة', 'دوبلكس', 'تاونهاوس', 'بيت', 'شاليه', 'مستودع', 'مكتب', 'مطلوب', 'للبيع', 'للإيجار', 'للشراء', 'أبحث', 'أبغى', 'عرض', 'سعر', 'مساحة', 'غرف', 'حمام', 'موقع', 'الرياض', 'جدة', 'مكة', 'الدمام', 'الخبر', 'الطائف', 'أبها', 'تبوك', 'المدينة'];
        foreach ($keywords as $k) {
            if (mb_stripos($body, $k) !== false) {
                return true;
            }
        }

        return false;
    }

    public function destroy(ProductRequest $productRequest)
    {
        $productRequest->delete();

        return redirect()->route('admin.product-requests.index')
            ->with('success', 'تم حذف الطلب بنجاح.');
    }

    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|in:delete,close,resolve',
            'ids' => 'required|array',
            'ids.*' => 'exists:product_requests,id',
        ]);

        $ids = $validated['ids'];

        switch ($validated['action']) {
            case 'delete':
                ProductRequest::whereIn('id', $ids)->delete();
                $message = 'تم حذف الطلبات المحددة.';
                break;

            case 'close':
                ProductRequest::whereIn('id', $ids)->update([
                    'status' => ProductRequest::STATUS_CLOSED,
                    'closed_at' => Carbon::now(),
                ]);
                $message = 'تم إغلاق الطلبات المحددة.';
                break;

            case 'resolve':
                ProductRequest::whereIn('id', $ids)->update([
                    'status' => ProductRequest::STATUS_RESOLVED,
                    'closed_at' => Carbon::now(),
                ]);
                $message = 'تم حل الطلبات المحددة.';
                break;
        }

        return redirect()->back()->with('success', $message);
    }
}
