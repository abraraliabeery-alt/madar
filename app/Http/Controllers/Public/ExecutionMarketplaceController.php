<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\ExecutionRequest;
use App\Models\ExecutionRequestTranslation;
use App\Models\ExecutionBid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class ExecutionMarketplaceController extends Controller
{
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

    public function storeBid(Request $request, ExecutionRequest $executionRequest)
    {
        $data = $request->validate([
            'price_total' => 'required|numeric|min:0',
            'message' => 'required|string|max:2000',
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

        ExecutionBid::create([
            'execution_request_id' => $executionRequest->id,
            'executor_user_id' => $user->id,
            'price_total' => $data['price_total'],
            'currency' => 'SAR',
            'status' => 'pending',
            'data' => [
                'notes' => $data['message'],
                'source' => 'public-marketplace',
            ],
        ]);

        return back()->with('success', 'تم إرسال عرضك بنجاح، سيتم التواصل معك عند المراجعة.');
    }
}
