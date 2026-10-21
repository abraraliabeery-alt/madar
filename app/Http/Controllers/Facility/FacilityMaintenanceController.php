<?php

namespace App\Http\Controllers\Facility;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MaintenanceRequest;
use App\Models\Tenant;

class FacilityMaintenanceController extends Controller
{
    private function facility()
    {
        return Auth::user()->facilities()->firstOrFail();
    }

    private function formLists($facility): array
    {
        $products = $facility->products()->with('translations')->get()
            ->mapWithKeys(fn ($p) => [$p->id => $p->getTranslatedTitle() ?: ('#' . $p->id)]);

        $tenants = Tenant::where('facility_id', $facility->id)->with('translations')->get()
            ->mapWithKeys(fn ($t) => [$t->id => $t->getTranslatedName() ?: ('#' . $t->id)]);

        $users = $facility->users()->pluck('users.name', 'users.id');

        return compact('products', 'tenants', 'users');
    }

    private function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'product_id' => 'nullable|exists:products,id',
            'tenant_id' => 'nullable|exists:tenants,id',
            'type' => 'required|in:' . implode(',', MaintenanceRequest::TYPES),
            'priority' => 'required|in:' . implode(',', MaintenanceRequest::PRIORITIES),
            'assigned_to' => 'nullable|exists:users,id',
            'scheduled_at' => 'nullable|date',
            'cost' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'status' => 'nullable|in:open,in_progress,completed,cancelled',
        ];
    }

    public function index(Request $request)
    {
        $facility = $this->facility();

        $requests = MaintenanceRequest::where('facility_id', $facility->id)
            ->with(['product.translations', 'assignee', 'tenant.translations'])
            ->when($request->filled('search'), fn ($q) => $q->where('title', 'like', '%' . $request->search . '%'))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('type'), fn ($q) => $q->where('type', $request->type))
            ->when($request->filled('priority'), fn ($q) => $q->where('priority', $request->priority))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('facility.maintenance.index', compact('requests'));
    }

    public function create()
    {
        $maintenance = new MaintenanceRequest();
        return view('facility.maintenance.create', array_merge(
            compact('maintenance'),
            $this->formLists($this->facility())
        ));
    }

    public function store(Request $request)
    {
        $facility = $this->facility();
        $data = $request->validate($this->rules());

        MaintenanceRequest::create($data + [
            'facility_id' => $facility->id,
            'created_by' => Auth::id(),
            'status' => $data['status'] ?? 'open',
        ]);

        return redirect()->route('facility.maintenance.index')->with('success', __('تم إنشاء طلب الصيانة بنجاح'));
    }

    public function edit(MaintenanceRequest $maintenance)
    {
        $facility = $this->facility();
        abort_unless($maintenance->facility_id === $facility->id, 404);

        return view('facility.maintenance.edit', array_merge(
            compact('maintenance'),
            $this->formLists($facility)
        ));
    }

    public function update(Request $request, MaintenanceRequest $maintenance)
    {
        $facility = $this->facility();
        abort_unless($maintenance->facility_id === $facility->id, 404);
        $data = $request->validate($this->rules());

        $maintenance->update($data);

        return redirect()->route('facility.maintenance.index')->with('success', __('تم تحديث طلب الصيانة بنجاح'));
    }

    public function destroy(MaintenanceRequest $maintenance)
    {
        $facility = $this->facility();
        abort_unless($maintenance->facility_id === $facility->id, 404);
        $maintenance->delete();

        return redirect()->route('facility.maintenance.index')->with('success', __('تم حذف طلب الصيانة بنجاح'));
    }

    public function updateStatus(Request $request, MaintenanceRequest $maintenance)
    {
        $facility = $this->facility();
        abort_unless($maintenance->facility_id === $facility->id, 404);

        $request->validate(['status' => 'required|in:open,in_progress,completed,cancelled']);
        $maintenance->update([
            'status' => $request->status,
            'completed_at' => $request->status === 'completed' ? now() : null,
        ]);

        return back()->with('success', __('تم تحديث حالة الطلب'));
    }
}
