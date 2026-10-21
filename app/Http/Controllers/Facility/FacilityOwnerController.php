<?php

namespace App\Http\Controllers\Facility;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Owner;
use App\Models\OwnerPayout;
use App\Models\Contract;
use App\Models\Payment;

class FacilityOwnerController extends Controller
{
    private function facility()
    {
        return Auth::user()->facilities()->firstOrFail();
    }

    private function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
            'national_id' => 'nullable|string|max:50',
            'is_company' => 'required|in:0,1',
            'contact_person' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
        ];
    }

    public function index(Request $request)
    {
        $facility = $this->facility();

        $owners = Owner::where('facility_id', $facility->id)
            ->withCount('products')
            ->when($request->filled('search'), fn ($q) => $q->where(function ($q2) use ($request) {
                $q2->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('phone', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhere('national_id', 'like', '%' . $request->search . '%');
            }))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('facility.owners.index', compact('owners'));
    }

    public function create()
    {
        return view('facility.owners.create', ['owner' => new Owner()]);
    }

    public function store(Request $request)
    {
        $facility = $this->facility();
        $data = $request->validate($this->rules());

        Owner::create($data + [
            'facility_id' => $facility->id,
            'is_company' => $data['is_company'] === '1',
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('facility.owners.index')->with('success', __('تم إضافة المالك بنجاح'));
    }

    public function show(Owner $owner)
    {
        $facility = $this->facility();
        abort_unless($owner->facility_id === $facility->id, 404);

        return view('facility.owners.show', $this->ownerStatementData($owner, $facility));
    }

    private function ownerStatementData(Owner $owner, $facility): array
    {
        $contracts = Contract::where('facility_id', $facility->id)
            ->where('owner_id', $owner->id)
            ->with(['product.translations', 'tenant', 'user'])
            ->withSum('payments', 'amount')
            ->latest()
            ->get();

        $payouts = OwnerPayout::where('owner_id', $owner->id)
            ->where('facility_id', $facility->id)
            ->with('creator')
            ->latest('paid_at')
            ->get();

        $collected = Payment::whereIn('contract_id', $contracts->pluck('id'))
            ->where('status', 'confirmed')->sum('amount');
        $paidToOwner = $payouts->sum('amount');
        $ownerShare = $collected * (1 - ($owner->commission_rate ?? 0) / 100);

        $totals = [
            'properties' => $owner->products()->count(),
            'contracts' => $contracts->count(),
            'active_contracts' => $contracts->where('status', 'active')->count(),
            'contracts_value' => $contracts->sum('total_amount'),
            'collected' => $collected,
            'paid_to_owner' => $paidToOwner,
            'owner_due' => $ownerShare - $paidToOwner,
        ];

        return compact('owner', 'contracts', 'payouts', 'totals');
    }

    public function edit(Owner $owner)
    {
        $facility = $this->facility();
        abort_unless($owner->facility_id === $facility->id, 404);

        return view('facility.owners.edit', compact('owner'));
    }

    public function update(Request $request, Owner $owner)
    {
        $facility = $this->facility();
        abort_unless($owner->facility_id === $facility->id, 404);
        $data = $request->validate($this->rules());

        $owner->update($data + [
            'is_company' => $data['is_company'] === '1',
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('facility.owners.index')->with('success', __('تم تحديث المالك بنجاح'));
    }

    public function destroy(Owner $owner)
    {
        $facility = $this->facility();
        abort_unless($owner->facility_id === $facility->id, 404);
        $owner->delete();

        return redirect()->route('facility.owners.index')->with('success', __('تم حذف المالك بنجاح'));
    }

    public function storePayout(Request $request, Owner $owner)
    {
        $facility = $this->facility();
        abort_unless($owner->facility_id === $facility->id, 404);

        $data = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'paid_at' => 'required|date',
            'payment_method' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        OwnerPayout::create($data + [
            'facility_id' => $facility->id,
            'owner_id' => $owner->id,
            'created_by' => Auth::id(),
        ]);

        return back()->with('success', __('تم تسجيل التسوية بنجاح'));
    }

    public function statementPdf(Owner $owner)
    {
        $facility = $this->facility();
        abort_unless($owner->facility_id === $facility->id, 404);

        return view('facility.owners.statement_pdf', $this->ownerStatementData($owner, $facility) + ['facility' => $facility]);
    }
}
