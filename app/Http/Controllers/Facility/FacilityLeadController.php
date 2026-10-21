<?php

namespace App\Http\Controllers\Facility;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Lead;
use App\Models\LeadActivity;
use App\Models\User;

class FacilityLeadController extends Controller
{
    private function facility()
    {
        return Auth::user()->facilities()->firstOrFail();
    }

    private function staffUsers($facility)
    {
        return $facility->users()->orderBy('users.name')->get(['users.id', 'users.name']);
    }

    private function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
            'source' => 'nullable|in:' . implode(',', array_keys(Lead::SOURCES)),
            'stage' => 'required|in:' . implode(',', array_keys(Lead::STAGES)),
            'interest_type' => 'nullable|in:buy,rent',
            'product_id' => 'nullable|exists:products,id',
            'assigned_to' => 'nullable|exists:users,id',
            'budget' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ];
    }

    private function checkAccess(Lead $lead): void
    {
        abort_unless($lead->facility_id === Auth::user()->facilities()->firstOrFail()->id, 404);
    }

    /**
     * لوحة العملاء المحتملين (كانبان حسب المرحلة)
     */
    public function index(Request $request)
    {
        $facility = $this->facility();

        $leads = Lead::where('facility_id', $facility->id)
            ->with(['product.translations', 'assignedTo', 'nextFollowUp'])
            ->when($request->filled('search'), fn ($q) => $q->where(function ($q2) use ($request) {
                $q2->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('phone', 'like', '%' . $request->search . '%');
            }))
            ->when($request->filled('assigned_to'), fn ($q) => $q->where('assigned_to', $request->assigned_to))
            ->latest()
            ->get();

        $byStage = $leads->groupBy('stage');
        $users = $this->staffUsers($facility);

        $stats = [
            'total' => $leads->count(),
            'new' => $byStage->get('new', collect())->count(),
            'won' => $byStage->get('won', collect())->count(),
            'due_followups' => $leads->filter(fn ($l) => $l->nextFollowUp && $l->nextFollowUp->follow_up_at <= now())->count(),
        ];

        return view('facility.leads.index', compact('leads', 'byStage', 'stats', 'users'));
    }

    public function create()
    {
        $facility = $this->facility();
        $lead = new Lead(['stage' => 'new']);
        $products = $facility->products()->with('translations')->get();
        $users = $this->staffUsers($facility);

        return view('facility.leads.create', compact('lead', 'products', 'users'));
    }

    public function store(Request $request)
    {
        $facility = $this->facility();
        $data = $request->validate($this->rules());

        $lead = Lead::create($data + ['facility_id' => $facility->id]);

        LeadActivity::create([
            'lead_id' => $lead->id,
            'user_id' => Auth::id(),
            'type' => 'note',
            'body' => 'تم إنشاء العميل المحتمل',
        ]);

        return redirect()->route('facility.leads.index')->with('success', __('تم إضافة العميل المحتمل بنجاح'));
    }

    public function show(Lead $lead)
    {
        $this->checkAccess($lead);
        $lead->load(['product.translations', 'assignedTo', 'activities.user']);

        return view('facility.leads.show', compact('lead'));
    }

    public function edit(Lead $lead)
    {
        $this->checkAccess($lead);
        $facility = $this->facility();
        $products = $facility->products()->with('translations')->get();
        $users = $this->staffUsers($facility);

        return view('facility.leads.edit', compact('lead', 'products', 'users'));
    }

    public function update(Request $request, Lead $lead)
    {
        $this->checkAccess($lead);
        $lead->update($request->validate($this->rules()));

        return redirect()->route('facility.leads.index')->with('success', __('تم تحديث العميل المحتمل بنجاح'));
    }

    public function destroy(Lead $lead)
    {
        $this->checkAccess($lead);
        $lead->delete();

        return redirect()->route('facility.leads.index')->with('success', __('تم حذف العميل المحتمل'));
    }

    /**
     * تغيير المرحلة من لوحة الكانبان
     */
    public function updateStage(Request $request, Lead $lead)
    {
        $this->checkAccess($lead);
        $request->validate(['stage' => 'required|in:' . implode(',', array_keys(Lead::STAGES))]);

        $lead->update(['stage' => $request->stage]);

        if ($request->expectsJson()) {
            return response()->json(['ok' => true, 'stage' => $lead->stage]);
        }
        return back()->with('success', __('تم تحديث المرحلة'));
    }

    /**
     * إضافة نشاط/ملاحظة على العميل
     */
    public function storeActivity(Request $request, Lead $lead)
    {
        $this->checkAccess($lead);
        $data = $request->validate([
            'type' => 'required|in:' . implode(',', array_keys(LeadActivity::TYPES)),
            'body' => 'nullable|string|max:1000',
            'follow_up_at' => 'nullable|date',
        ]);

        LeadActivity::create($data + [
            'lead_id' => $lead->id,
            'user_id' => Auth::id(),
        ]);

        return back()->with('success', __('تم إضافة النشاط'));
    }

    /**
     * تقرير العملاء المحتملين
     */
    public function report()
    {
        $facility = $this->facility();
        $leads = Lead::where('facility_id', $facility->id);

        $total = (clone $leads)->count();
        $won = (clone $leads)->where('stage', 'won')->count();
        $lost = (clone $leads)->where('stage', 'lost')->count();
        $byStage = (clone $leads)->selectRaw('stage, COUNT(*) as c')->groupBy('stage')->pluck('c', 'stage');

        $bySource = (clone $leads)
            ->selectRaw("source, COUNT(*) as count, SUM(stage = 'won') as won")
            ->groupBy('source')->get();

        $byUser = (clone $leads)->whereNotNull('assigned_to')
            ->selectRaw("assigned_to, COUNT(*) as count, SUM(stage = 'won') as won")
            ->groupBy('assigned_to')->get();
        $userNames = User::whereIn('id', $byUser->pluck('assigned_to'))->pluck('name', 'id');

        return view('facility.leads.report', compact('total', 'won', 'lost', 'byStage', 'bySource', 'byUser', 'userNames'));
    }
}
