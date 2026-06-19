<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\ExecutionRequest;
use App\Models\ExecutionRequestTranslation;
use App\Models\ExecutionBid;
use App\Models\FacilityDocument;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class ExecutionMarketplaceController extends Controller
{
    public function __construct(private readonly FileUploadService $fileUploadService)
    {
    }

    private function resolveBidRequirements(ExecutionRequest $executionRequest): array
    {
        $req = (array) (data_get($executionRequest->data ?? [], 'bid_requirements', []) ?: []);

        $requiredAttachments = [];
        $rawRequiredAttachments = data_get($req, 'required_attachments');
        if (is_array($rawRequiredAttachments)) {
            foreach (array_slice($rawRequiredAttachments, 0, 20) as $row) {
                if (!is_array($row)) {
                    continue;
                }
                $key = trim((string) ($row['key'] ?? ''));
                if ($key === '') {
                    continue;
                }
                $requiredAttachments[] = [
                    'key' => mb_substr($key, 0, 50),
                    'label' => mb_substr(trim((string) ($row['label'] ?? $key)), 0, 100),
                    'required' => (bool) ($row['required'] ?? false),
                    'category' => mb_substr(trim((string) ($row['category'] ?? '')), 0, 30),
                ];
            }
        }

        $requiredAttachmentsMin = (int) ($req['required_attachments_min'] ?? 1);
        if ($requiredAttachmentsMin < 0) {
            $requiredAttachmentsMin = 0;
        }
        if ($requiredAttachmentsMin > 10) {
            $requiredAttachmentsMin = 10;
        }

        $requiredTypesCount = count(array_filter($requiredAttachments, fn ($a) => !empty($a['required'])));
        if ($requiredTypesCount > 0) {
            $requiredAttachmentsMin = max($requiredAttachmentsMin, $requiredTypesCount);
        }

        $requiredFields = (array) ($req['required_fields'] ?? ['price_total', 'message', 'technical_plan', 'declaration']);
        $requiredFields = array_values(array_unique(array_filter($requiredFields, fn ($f) => is_string($f) && $f !== '')));

        return [
            'required_attachments_min' => $requiredAttachmentsMin,
            'required_attachments' => $requiredAttachments,
            'required_fields' => $requiredFields,
        ];
    }

    public function index(Request $request)
    {
        $locale = App::getLocale();

        $requestsQuery = ExecutionRequest::query()
            ->with(['translations'])
            ->withCount('bids')
            ->latest();

        $status = $request->get('status', 'open');
        if ($status === 'open') {
            $requestsQuery->where('status', 'open');
        } elseif ($status === 'closed') {
            $requestsQuery->whereIn('status', ['completed', 'closed', 'cancelled']);
        }

        if ($search = $request->get('q')) {
            $requestsQuery->whereHas('translations', function ($q) use ($search) {
                $q->where(function ($tq) use ($search) {
                    $tq->where('title', 'like', "%{$search}%")
                       ->orWhere('description', 'like', "%{$search}%");
                });
            });
        }

        if ($type = $request->get('type')) {
            $requestsQuery->where('type', 'like', "%{$type}%");
        }

        $minBudget = $request->get('min_budget');
        $maxBudget = $request->get('max_budget');

        // Budget range overlap (null-safe):
        // - if minBudget is set: include rows where budget_max is null OR budget_max >= minBudget
        // - if maxBudget is set: include rows where budget_min is null OR budget_min <= maxBudget
        if ($minBudget !== null && $minBudget !== '') {
            $requestsQuery->where(function ($q) use ($minBudget) {
                $q->whereNull('budget_max')
                  ->orWhere('budget_max', '>=', $minBudget);
            });
        }

        if ($maxBudget !== null && $maxBudget !== '') {
            $requestsQuery->where(function ($q) use ($maxBudget) {
                $q->whereNull('budget_min')
                  ->orWhere('budget_min', '<=', $maxBudget);
            });
        }

        $openRequests = $requestsQuery->paginate(9)->appends($request->query());

        $stats = [
            'total_open' => ExecutionRequest::where('status', 'open')->count(),
            'total_closed' => ExecutionRequest::whereIn('status', ['completed', 'closed', 'cancelled'])->count(),
            'total_bids' => ExecutionBid::count(),
        ];

        $endedRequests = ExecutionRequest::query()
            ->whereIn('status', ['completed', 'closed', 'cancelled'])
            ->with(['translations'])
            ->latest()
            ->take(6)
            ->get();

        $highlightRequest = ExecutionRequest::query()
            ->with(['translations'])
            ->withCount('bids')
            ->latest()
            ->first();

        return view('public.execution.marketplace', compact('locale', 'openRequests', 'endedRequests', 'stats', 'highlightRequest'));
    }

    public function show(ExecutionRequest $executionRequest)
    {
        $executionRequest->load(['translations', 'bids.executorUser']);

        return view('public.execution.show', compact('executionRequest'));
    }

    public function bidForm(ExecutionRequest $executionRequest)
    {
        $executionRequest->load(['translations', 'bids.executorUser']);

        return view('public.execution.bid-form', compact('executionRequest'));
    }

    public function storeBid(Request $request, ExecutionRequest $executionRequest)
    {
        $data = $request->validate([
            'action' => 'nullable|in:save_draft,submit',
            'price_total' => 'nullable|numeric|min:0',
            'duration_days' => 'nullable|integer|min:1|max:3650',
            'warranty_months' => 'nullable|integer|min:0|max:240',
            'message' => 'nullable|string|max:5000',
            'technical_plan' => 'nullable|string|max:10000',
            'technical_plan_file' => 'nullable|file|max:8192|mimes:pdf',
            'financial_breakdown' => 'nullable|string|max:10000',
            'financial_items_json' => 'nullable|string|max:50000',
            'declaration' => 'nullable|in:1',
            'attachments_files' => 'nullable|array|max:10',
            'attachments_files.*' => 'nullable',
            'attachments_files.*.*' => 'file|max:8192|mimes:pdf,jpg,jpeg,png',
            'profile_attachments' => 'nullable|array|max:10',
            'profile_attachments.*' => 'nullable|array|max:20',
            'profile_attachments.*.*' => 'string|max:500',
            'remove_attachments' => 'nullable|array|max:50',
            'remove_attachments.*' => 'string|max:500',
        ]);

        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $facility = $user->facilities()->first();

        if (!$facility || !$facility->isExecutionEligible()) {
            return back()->withErrors([
                'execution' => 'حسابك غير مرتبط بمنشأة مصنَّفة كمنفِّذ، لذلك لا يمكنك تقديم عروض تنفيذ حالياً.'
            ]);
        }

        if (!empty($executionRequest->due_date)) {
            $due = $executionRequest->due_date instanceof \Carbon\CarbonInterface
                ? $executionRequest->due_date
                : Carbon::parse($executionRequest->due_date);
            if (now()->startOfDay()->gt($due->endOfDay())) {
                return back()->withErrors([
                    'execution' => 'انتهى وقت التقديم على هذا الطلب.'
                ]);
            }
        }

        $action = $data['action'] ?? 'submit';
        $targetStatus = $action === 'save_draft' ? 'draft' : 'pending';

        $requirements = $this->resolveBidRequirements($executionRequest);

        if ($action === 'submit') {
            $requiredFields = (array) ($requirements['required_fields'] ?? []);
            $requiredFields = array_values(array_unique(array_filter($requiredFields, fn ($f) => is_string($f) && $f !== '')));
            if (empty($requiredFields)) {
                $requiredFields = ['price_total', 'message', 'technical_plan', 'declaration'];
            }

            $rules = [];
            if (in_array('price_total', $requiredFields, true)) {
                $rules['price_total'] = 'required|numeric|min:0';
            }
            if (in_array('message', $requiredFields, true)) {
                $rules['message'] = 'required|string|max:5000';
            }
            if (in_array('technical_plan', $requiredFields, true)) {
                // Backward compatible:
                // - prefer PDF upload (two-envelope Etimad-style)
                // - allow legacy textarea as fallback
                $rules['technical_plan'] = 'nullable|string|max:10000';
                $rules['technical_plan_file'] = 'nullable|file|max:8192|mimes:pdf';
            }
            if (in_array('declaration', $requiredFields, true)) {
                $rules['declaration'] = 'required|in:1';
            }

            if (!empty($rules)) {
                $submitData = $request->validate($rules);
                foreach ($submitData as $k => $v) {
                    $data[$k] = $v;
                }
            }
        }

        $existingBid = ExecutionBid::query()
            ->where('execution_request_id', $executionRequest->id)
            ->where('executor_facility_id', $facility->id)
            ->latest('id')
            ->first();

        if ($existingBid && in_array($existingBid->status, ['accepted', 'rejected'], true)) {
            return back()->withErrors([
                'execution' => 'لا يمكن تعديل العرض بعد اتخاذ قرار بشأنه.'
            ]);
        }

        $isFinalLocked = false;
        if ($existingBid && ($existingBid->status === 'pending') && is_array($existingBid->data ?? null)) {
            $isFinalLocked = !empty($existingBid->data['submitted_at']);
        }
        if ($isFinalLocked) {
            return back()->withErrors([
                'execution' => 'تم إرسال العرض بشكل نهائي ولا يمكن تعديله.'
            ]);
        }

        $newData = array_merge((array) ($existingBid->data ?? []), [
            'notes' => $data['message'] ?? (($existingBid->data['notes'] ?? null) ?: null),
            'source' => 'public-marketplace',
            'last_action' => $action,
            'technical' => [
                'plan' => $data['technical_plan'] ?? data_get($existingBid?->data, 'technical.plan'),
            ],
            'financial' => [
                'breakdown' => $data['financial_breakdown'] ?? data_get($existingBid?->data, 'financial.breakdown'),
            ],
        ]);

        $financialItems = null;
        if (!empty($data['financial_items_json'])) {
            try {
                $decoded = json_decode($data['financial_items_json'], true, 512, JSON_THROW_ON_ERROR);
                $financialItems = is_array($decoded) ? $decoded : null;
            } catch (\Throwable $e) {
                $financialItems = null;
            }
        }
        if (is_array($financialItems)) {
            $normalized = [];
            foreach (array_slice($financialItems, 0, 200) as $row) {
                if (!is_array($row)) {
                    continue;
                }
                $name = trim((string) ($row['name'] ?? ''));
                if ($name === '') {
                    continue;
                }
                $qty = (float) ($row['qty'] ?? 0);
                $unit = (float) ($row['unit_price'] ?? 0);
                if ($qty < 0) {
                    $qty = 0;
                }
                if ($unit < 0) {
                    $unit = 0;
                }
                $total = round($qty * $unit, 2);
                $normalized[] = [
                    'name' => mb_substr($name, 0, 255),
                    'qty' => $qty,
                    'unit_price' => round($unit, 2),
                    'total' => $total,
                ];
            }
            $newData['financial']['items'] = $normalized;
        }

        $attachments = (array) (data_get($existingBid?->data, 'attachments', []) ?: []);

        $allowedProfileDocs = [];

        try {
            $facilityDocs = FacilityDocument::query()
                ->where('facility_id', $facility->id)
                ->where('status', 'active')
                ->get(['type', 'disk', 'path', 'original_name']);
            foreach ($facilityDocs as $doc) {
                if (($doc->disk ?? 'public') !== 'public') {
                    continue;
                }
                $p = trim((string) ($doc->path ?? ''));
                if ($p === '') {
                    continue;
                }
                if (!Storage::disk('public')->exists($p)) {
                    continue;
                }
                $label = (string) ($doc->original_name ?: ($doc->type ?? 'مستند منشأة'));
                if (($doc->type ?? null) === 'commercial_register') {
                    $label = 'السجل التجاري';
                }
                $allowedProfileDocs[$p] = $label;
            }
        } catch (\Throwable $e) {
        }

        $possible = [
            ['path' => (string) ($facility->License ?? ''), 'label' => 'ترخيص المنشأة'],
            ['path' => (string) ($facility->license ?? ''), 'label' => 'ترخيص المنشأة'],
            ['path' => (string) ($facility->license_path ?? ''), 'label' => 'ترخيص المنشأة'],
            ['path' => (string) data_get($facility->customization_settings ?? [], 'qualification_docs.commercial_register.path', ''), 'label' => 'السجل التجاري'],
            ['path' => (string) ($facility->logo_path ?? ''), 'label' => 'شعار المنشأة'],
            ['path' => (string) ($facility->logo ?? ''), 'label' => 'شعار المنشأة'],
            ['path' => (string) ($facility->cover_image ?? ''), 'label' => 'غلاف المنشأة'],
            ['path' => (string) ($facility->header ?? ''), 'label' => 'غلاف المنشأة'],
        ];
        foreach ($possible as $row) {
            $p = trim((string) ($row['path'] ?? ''));
            if ($p === '') {
                continue;
            }
            if (!Storage::disk('public')->exists($p)) {
                continue;
            }
            $allowedProfileDocs[$p] = $allowedProfileDocs[$p] ?? (string) ($row['label'] ?? 'مستند منشأة');
        }

        if (!empty($data['profile_attachments']) && is_array($data['profile_attachments'])) {
            foreach ($data['profile_attachments'] as $groupKey => $paths) {
                $typeKey = is_string($groupKey) ? $groupKey : 'general';
                foreach ((array) $paths as $path) {
                    $path = trim((string) $path);
                    if ($path === '') {
                        continue;
                    }
                    if (!array_key_exists($path, $allowedProfileDocs)) {
                        continue;
                    }
                    $already = false;
                    foreach ($attachments as $a) {
                        if (!is_array($a)) {
                            continue;
                        }
                        if (($a['path'] ?? null) === $path && ($a['type'] ?? 'general') === $typeKey) {
                            $already = true;
                            break;
                        }
                    }
                    if ($already) {
                        continue;
                    }
                    $attachments[] = [
                        'disk' => 'public',
                        'path' => $path,
                        'type' => $typeKey,
                        'original_name' => $allowedProfileDocs[$path],
                        'mime_type' => null,
                        'size_bytes' => null,
                        'uploaded_at' => now()->toISOString(),
                        'source' => 'facility-profile',
                    ];
                }
            }
        }

        if (!empty($data['remove_attachments']) && is_array($data['remove_attachments'])) {
            $toRemove = array_values(array_filter($data['remove_attachments'], fn ($p) => is_string($p) && $p !== ''));
            if (!empty($toRemove)) {
                foreach ($toRemove as $path) {
                    $isProfileDoc = array_key_exists($path, $allowedProfileDocs);
                    if (!$isProfileDoc && Storage::disk('public')->exists($path)) {
                        Storage::disk('public')->delete($path);
                    }
                }
                $attachments = array_values(array_filter($attachments, fn ($a) => !in_array(($a['path'] ?? null), $toRemove, true)));
            }
        }

        if ($request->hasFile('attachments_files')) {
            $files = (array) $request->file('attachments_files');
            foreach ($files as $groupKey => $groupFiles) {
                if ($groupFiles instanceof \Illuminate\Http\UploadedFile) {
                    $groupFiles = [$groupFiles];
                    $typeKey = 'general';
                } else {
                    $typeKey = is_string($groupKey) ? $groupKey : 'general';
                }

                foreach ((array) $groupFiles as $file) {
                    if (!$file) {
                        continue;
                    }
                    if (!$file instanceof \Illuminate\Http\UploadedFile) {
                        continue;
                    }
                    $path = $this->fileUploadService->upload($file, 'execution-bids/' . $executionRequest->id . '/' . $facility->id);
                    $attachments[] = [
                        'disk' => 'public',
                        'path' => $path,
                        'type' => $typeKey,
                        'original_name' => $file->getClientOriginalName(),
                        'mime_type' => $file->getClientMimeType(),
                        'size_bytes' => $file->getSize(),
                        'uploaded_at' => now()->toISOString(),
                    ];
                }
            }
        }

        // Technical plan as PDF attachment (Etimad-style)
        if ($request->hasFile('technical_plan_file')) {
            $file = $request->file('technical_plan_file');
            if ($file instanceof \Illuminate\Http\UploadedFile) {
                $path = $this->fileUploadService->upload($file, 'execution-bids/' . $executionRequest->id . '/' . $facility->id);
                $attachments[] = [
                    'disk' => 'public',
                    'path' => $path,
                    'type' => 'technical_plan',
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getClientMimeType(),
                    'size_bytes' => $file->getSize(),
                    'uploaded_at' => now()->toISOString(),
                    'source' => 'technical-plan',
                ];
            }
        }

        $newData['attachments'] = $attachments;

        if ($action === 'submit') {
            // Enforce technical plan requirement if enabled for this request
            $requiredFields = (array) ($requirements['required_fields'] ?? []);
            $requiredFields = array_values(array_unique(array_filter($requiredFields, fn ($f) => is_string($f) && $f !== '')));
            if (in_array('technical_plan', $requiredFields, true)) {
                $hasTechnicalPdf = false;
                foreach ($attachments as $a) {
                    if (!is_array($a)) {
                        continue;
                    }
                    if (($a['type'] ?? 'general') === 'technical_plan') {
                        $hasTechnicalPdf = true;
                        break;
                    }
                }
                $hasLegacyText = !empty(trim((string) ($data['technical_plan'] ?? data_get($existingBid?->data, 'technical.plan') ?? '')));
                if (!$hasTechnicalPdf && !$hasLegacyText) {
                    return back()->withErrors([
                        'execution' => 'المرفقات المطلوبة غير مكتملة قبل الإرسال النهائي.'
                    ]);
                }
            }

            if (($requirements['required_attachments_min'] ?? 1) > 0 && count($attachments) < (int) $requirements['required_attachments_min']) {
                return back()->withErrors([
                    'execution' => 'المرفقات المطلوبة غير مكتملة قبل الإرسال النهائي.'
                ]);
            }

            $requiredAttachments = (array) ($requirements['required_attachments'] ?? []);
            foreach ($requiredAttachments as $ra) {
                if (!is_array($ra)) {
                    continue;
                }
                if (empty($ra['required'])) {
                    continue;
                }
                $key = (string) ($ra['key'] ?? '');
                if ($key === '') {
                    continue;
                }

                $hasType = false;
                foreach ($attachments as $a) {
                    if (!is_array($a)) {
                        continue;
                    }
                    if (($a['type'] ?? 'general') === $key) {
                        $hasType = true;
                        break;
                    }
                }

                if (!$hasType) {
                    return back()->withErrors([
                        'execution' => 'المرفقات المطلوبة غير مكتملة قبل الإرسال النهائي.'
                    ]);
                }
            }

            $newData['submitted_at'] = now()->toISOString();

            $newData['final_snapshot'] = [
                'version' => 1,
                'created_at' => now()->toISOString(),
                'execution_request_id' => $executionRequest->id,
                'execution_request_title' => $executionRequest->getTranslatedTitle() ?? ('طلب تنفيذ #' . $executionRequest->id),
                'executor_facility_id' => $facility->id,
                'price_total' => $data['price_total'] ?? null,
                'currency' => 'SAR',
                'duration_days' => $data['duration_days'] ?? ($existingBid->duration_days ?? null),
                'warranty_months' => $data['warranty_months'] ?? ($existingBid->warranty_months ?? null),
                'notes' => $data['message'] ?? null,
                'technical_plan' => $data['technical_plan'] ?? data_get($existingBid?->data, 'technical.plan'),
                'financial_breakdown' => $data['financial_breakdown'] ?? data_get($existingBid?->data, 'financial.breakdown'),
                'financial_items' => data_get($newData, 'financial.items', []),
                'attachments' => array_map(fn ($a) => [
                    'disk' => $a['disk'] ?? 'public',
                    'path' => $a['path'] ?? null,
                    'type' => $a['type'] ?? 'general',
                    'original_name' => $a['original_name'] ?? null,
                    'mime_type' => $a['mime_type'] ?? null,
                    'size_bytes' => $a['size_bytes'] ?? null,
                ], $attachments),
                'declaration' => true,
            ];
        }

        ExecutionBid::updateOrCreate(
            [
                'execution_request_id' => $executionRequest->id,
                'executor_facility_id' => $facility->id,
            ],
            [
                'executor_user_id' => $user->id,
                'price_total' => $data['price_total'] ?? ($existingBid->price_total ?? null),
                'currency' => 'SAR',
                'duration_days' => $data['duration_days'] ?? ($existingBid->duration_days ?? null),
                'warranty_months' => $data['warranty_months'] ?? ($existingBid->warranty_months ?? null),
                'status' => $targetStatus,
                'data' => $newData,
            ]
        );

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'status' => $targetStatus,
                'saved_at' => now()->toISOString(),
            ]);
        }

        return back()->with('success', $action === 'save_draft'
            ? 'تم حفظ العرض كمسودة.'
            : 'تم إرسال عرضك بنجاح، سيتم التواصل معك عند المراجعة.'
        );
    }

    public function previewMyBidPdf(ExecutionRequest $executionRequest)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $facility = $user->facilities()->first();
        if (!$facility) {
            return redirect()->route('public.execution.bids.form', $executionRequest)->withErrors([
                'execution' => 'حسابك غير مرتبط بمنشأة، لذلك لا يمكن معاينة PDF لهذا العرض.'
            ]);
        }

        $bid = ExecutionBid::query()
            ->where('execution_request_id', $executionRequest->id)
            ->where('executor_facility_id', $facility->id)
            ->first();

        abort_unless($bid, 404);

        $executionRequest->load(['translations']);

        $pdf = Pdf::loadView('public.execution.bid-pdf', [
            'executionRequest' => $executionRequest,
            'bid' => $bid,
            'facility' => $facility,
            'user' => $user,
        ]);

        return $pdf->stream('execution-bid-preview.pdf');
    }

    public function downloadMyBidPdf(ExecutionRequest $executionRequest)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $facility = $user->facilities()->first();
        if (!$facility) {
            return redirect()->route('public.execution.bids.form', $executionRequest)->withErrors([
                'execution' => 'حسابك غير مرتبط بمنشأة، لذلك لا يمكن تنزيل PDF لهذا العرض.'
            ]);
        }

        $bid = ExecutionBid::query()
            ->where('execution_request_id', $executionRequest->id)
            ->where('executor_facility_id', $facility->id)
            ->first();

        abort_unless($bid, 404);

        $executionRequest->load(['translations']);

        $pdf = Pdf::loadView('public.execution.bid-pdf', [
            'executionRequest' => $executionRequest,
            'bid' => $bid,
            'facility' => $facility,
            'user' => $user,
        ]);

        $filename = 'execution-bid-' . $executionRequest->id . '-' . $facility->id . '.pdf';
        return $pdf->download($filename);
    }
}
