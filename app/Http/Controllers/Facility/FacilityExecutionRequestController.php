<?php

namespace App\Http\Controllers\Facility;

use App\Http\Controllers\Controller;
use App\Models\ExecutionRequest;
use App\Models\ExecutionRequestTranslation;
use App\Models\ExecutionBid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FacilityExecutionRequestController extends Controller
{
    public function workspace()
    {
        $facility = Auth::user()->facilities()->firstOrFail();

        $requestsQuery = ExecutionRequest::query()
            ->where('facility_id', $facility->id)
            ->withCount('bids');

        $stats = [
            'total_requests' => (clone $requestsQuery)->count(),
            'open_requests' => (clone $requestsQuery)->where('status', 'open')->count(),
            'closed_requests' => (clone $requestsQuery)->whereIn('status', ['completed', 'closed', 'cancelled'])->count(),
            'total_bids' => ExecutionBid::whereIn('execution_request_id', function ($q) use ($facility) {
                $q->select('id')->from('execution_requests')->where('facility_id', $facility->id);
            })->count(),
        ];

        $recentRequests = ExecutionRequest::query()
            ->where('facility_id', $facility->id)
            ->with(['translations'])
            ->withCount('bids')
            ->latest()
            ->take(10)
            ->get();

        $myExecutorBids = ExecutionBid::query()
            ->where('executor_user_id', Auth::id())
            ->with(['executionRequest.translations'])
            ->latest()
            ->take(10)
            ->get();

        return view('facility.execution_requests.workspace', compact('facility', 'stats', 'recentRequests', 'myExecutorBids'));
    }

    public function index()
    {
        $facility = Auth::user()->facilities()->firstOrFail();

        $requests = ExecutionRequest::query()
            ->where('facility_id', $facility->id)
            ->with(['translations'])
            ->withCount('bids')
            ->latest()
            ->paginate(15);

        return view('facility.execution_requests.index', compact('facility', 'requests'));
    }

    public function create()
    {
        $facility = Auth::user()->facilities()->firstOrFail();
        $locales = config('locales.available');

        return view('facility.execution_requests.create', compact('facility', 'locales'));
    }

    public function store(Request $request)
    {
        $facility = Auth::user()->facilities()->firstOrFail();

        $data = $request->validate([
            'type' => 'nullable|string|max:100',
            'priority' => 'nullable|in:low,normal,high',
            'budget_min' => 'nullable|numeric|min:0',
            'budget_max' => 'nullable|numeric|min:0',
            'due_date' => 'nullable|date',
            'project_id' => 'nullable|exists:projects,id',
            'product_id' => 'nullable|exists:products,id',
            'bid_requirements' => 'nullable|array',
            'bid_requirements.required_attachments_min' => 'nullable|integer|min:0|max:10',
            'bid_requirements.required_attachments' => 'nullable|array|max:20',
            'bid_requirements.required_attachments.*.key' => 'nullable|string|max:50',
            'bid_requirements.required_attachments.*.label' => 'nullable|string|max:100',
            'bid_requirements.required_attachments.*.required' => 'nullable|boolean',
            'bid_requirements.required_attachments.*.category' => 'nullable|string|max:30',
            'bid_requirements.required_fields' => 'nullable|array',
            'bid_requirements.required_fields.*' => 'nullable|string|in:price_total,message,technical_plan,declaration',
            'translations' => 'required|array',
            'translations.*.locale' => 'required|string|in:' . implode(',', array_keys(config('locales.available'))) . '|distinct',
            'translations.*.title' => 'required|string|max:255',
            'translations.*.description' => 'nullable|string',
        ]);

        $requiredFields = array_values(array_unique(array_filter((array) data_get($data, 'bid_requirements.required_fields', []))));
        if (empty($requiredFields)) {
            $requiredFields = ['price_total', 'message', 'technical_plan', 'declaration'];
        }

        $requiredAttachmentsMin = (int) data_get($data, 'bid_requirements.required_attachments_min', 1);
        if ($requiredAttachmentsMin < 0) {
            $requiredAttachmentsMin = 0;
        }
        if ($requiredAttachmentsMin > 10) {
            $requiredAttachmentsMin = 10;
        }

        $requiredAttachments = [];
        $rawRequiredAttachments = (array) data_get($data, 'bid_requirements.required_attachments', []);
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

        $executionRequest = ExecutionRequest::create([
            'facility_id' => $facility->id,
            'project_id' => $data['project_id'] ?? null,
            'product_id' => $data['product_id'] ?? null,
            'type' => $data['type'] ?? null,
            'status' => 'open',
            'priority' => $data['priority'] ?? 'normal',
            'budget_min' => $data['budget_min'] ?? null,
            'budget_max' => $data['budget_max'] ?? null,
            'due_date' => $data['due_date'] ?? null,
            'data' => [
                'created_by_user_id' => Auth::id(),
                'bid_requirements' => [
                    'required_attachments_min' => $requiredAttachmentsMin,
                    'required_attachments' => $requiredAttachments,
                    'required_fields' => $requiredFields,
                ],
            ],
        ]);

        foreach ($data['translations'] as $translationData) {
            if (!empty($translationData['title'])) {
                ExecutionRequestTranslation::create([
                    'execution_request_id' => $executionRequest->id,
                    'locale' => $translationData['locale'],
                    'title' => $translationData['title'],
                    'description' => $translationData['description'] ?? null,
                ]);
            }
        }

        return redirect()->route('facility.execution-requests.show', $executionRequest)->with('success', 'تم إنشاء طلب التنفيذ بنجاح');
    }

    public function show(ExecutionRequest $executionRequest)
    {
        $facility = Auth::user()->facilities()->firstOrFail();

        abort_unless($executionRequest->facility_id === $facility->id, 404);

        $executionRequest->load(['translations', 'bids.executorUser']);

        return view('facility.execution_requests.show', compact('facility', 'executionRequest'));
    }

    public function storeBid(Request $request, ExecutionRequest $executionRequest)
    {
        $facility = Auth::user()->facilities()->firstOrFail();

        if (!$facility->isExecutionEligible()) {
            return redirect()->back()->withErrors([
                'execution' => 'تصنيف منشأتك الحالي لا يسمح بتقديم عروض تنفيذ. الرجاء التواصل مع الإدارة لتفعيل منشأتك كمنفِّذ.'
            ]);
        }

        abort_unless($executionRequest->facility_id === $facility->id, 404);

        $data = $request->validate([
            'price_total' => 'nullable|numeric|min:0',
            'duration_days' => 'nullable|integer|min:1',
            'warranty_months' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        $bid = ExecutionBid::create([
            'execution_request_id' => $executionRequest->id,
            'executor_user_id' => Auth::id(),
            'price_total' => $data['price_total'] ?? null,
            'currency' => 'SAR',
            'duration_days' => $data['duration_days'] ?? null,
            'warranty_months' => $data['warranty_months'] ?? null,
            'status' => 'pending',
            'data' => [
                'notes' => $data['notes'] ?? null,
            ],
        ]);

        return redirect()->route('facility.execution-requests.show', $executionRequest)->with('success', 'تم إضافة عرض المنفِّذ بنجاح');
    }
}
