<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MarketingProductRequest;
use App\Models\User;
use Carbon\Carbon;

class MarketingProductRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = MarketingProductRequest::with('assignedTo')->latest();

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
        $statuses = MarketingProductRequest::statuses();
        $priorities = MarketingProductRequest::priorities();
        $admins = User::whereHas('roles', function ($q) {
            $q->where('name', 'admin');
        })->orWhere('primary_role', 'admin')->orderBy('name')->get(['id', 'name']);

        $stats = [
            'total' => MarketingProductRequest::count(),
            'open' => MarketingProductRequest::open()->count(),
            'new' => MarketingProductRequest::where('status', MarketingProductRequest::STATUS_NEW)->count(),
            'resolved' => MarketingProductRequest::where('status', MarketingProductRequest::STATUS_RESOLVED)->count(),
        ];

        return view('admin.marketing-product-requests.index', compact(
            'requests',
            'statuses',
            'priorities',
            'admins',
            'stats'
        ));
    }

    public function show(MarketingProductRequest $marketingProductRequest)
    {
        $marketingProductRequest->load('assignedTo');
        $statuses = MarketingProductRequest::statuses();
        $priorities = MarketingProductRequest::priorities();
        $admins = User::whereHas('roles', function ($q) {
            $q->where('name', 'admin');
        })->orWhere('primary_role', 'admin')->orderBy('name')->get(['id', 'name']);

        return view('admin.marketing-product-requests.show', compact('marketingProductRequest', 'statuses', 'priorities', 'admins'));
    }

    public function update(Request $request, MarketingProductRequest $marketingProductRequest)
    {
        $validated = $request->validate([
            'status' => 'required|in:' . implode(',', array_keys(MarketingProductRequest::statuses())),
            'priority' => 'required|in:' . implode(',', array_keys(MarketingProductRequest::priorities())),
            'assigned_to' => 'nullable|exists:users,id',
            'admin_notes' => 'nullable|string|max:5000',
        ]);

        if ($validated['status'] === MarketingProductRequest::STATUS_CONTACTED && $marketingProductRequest->contacted_at === null) {
            $marketingProductRequest->contacted_at = Carbon::now();
        }

        if (in_array($validated['status'], [MarketingProductRequest::STATUS_CLOSED, MarketingProductRequest::STATUS_REJECTED, MarketingProductRequest::STATUS_RESOLVED], true) && $marketingProductRequest->closed_at === null) {
            $marketingProductRequest->closed_at = Carbon::now();
        }

        $marketingProductRequest->fill($validated);
        $marketingProductRequest->save();

        return redirect()->route('admin.marketing-product-requests.index')
            ->with('success', 'تم تحديث طلب التسويق بنجاح.');
    }

    public function updateStatus(Request $request, MarketingProductRequest $marketingProductRequest)
    {
        $validated = $request->validate([
            'status' => 'required|in:' . implode(',', array_keys(MarketingProductRequest::statuses())),
        ]);

        if ($validated['status'] === MarketingProductRequest::STATUS_CONTACTED && $marketingProductRequest->contacted_at === null) {
            $marketingProductRequest->contacted_at = Carbon::now();
        }

        if (in_array($validated['status'], [MarketingProductRequest::STATUS_CLOSED, MarketingProductRequest::STATUS_REJECTED, MarketingProductRequest::STATUS_RESOLVED], true) && $marketingProductRequest->closed_at === null) {
            $marketingProductRequest->closed_at = Carbon::now();
        }

        $marketingProductRequest->status = $validated['status'];
        $marketingProductRequest->save();

        return redirect()->back()->with('success', 'تم تحديث الحالة إلى: ' . MarketingProductRequest::statuses()[$validated['status']]);
    }

    public function destroy(MarketingProductRequest $marketingProductRequest)
    {
        $marketingProductRequest->delete();

        return redirect()->route('admin.marketing-product-requests.index')
            ->with('success', 'تم حذف الطلب بنجاح.');
    }

    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|in:delete,close,resolve',
            'ids' => 'required|array',
            'ids.*' => 'exists:marketing_product_requests,id',
        ]);

        $ids = $validated['ids'];

        switch ($validated['action']) {
            case 'delete':
                MarketingProductRequest::whereIn('id', $ids)->delete();
                $message = 'تم حذف الطلبات المحددة.';
                break;

            case 'close':
                MarketingProductRequest::whereIn('id', $ids)->update([
                    'status' => MarketingProductRequest::STATUS_CLOSED,
                    'closed_at' => Carbon::now(),
                ]);
                $message = 'تم إغلاق الطلبات المحددة.';
                break;

            case 'resolve':
                MarketingProductRequest::whereIn('id', $ids)->update([
                    'status' => MarketingProductRequest::STATUS_RESOLVED,
                    'closed_at' => Carbon::now(),
                ]);
                $message = 'تم حل الطلبات المحددة.';
                break;
        }

        return redirect()->back()->with('success', $message);
    }
}
