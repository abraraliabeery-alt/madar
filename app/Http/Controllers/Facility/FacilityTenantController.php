<?php

namespace App\Http\Controllers\Facility;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tenant;

class FacilityTenantController extends Controller
{
    private function facility()
    {
        return Auth::user()->facilities()->firstOrFail();
    }

    private function syncTranslations(Tenant $tenant, array $translations): void
    {
        $incomingLocales = [];
        foreach ($translations as $data) {
            if (empty($data['locale']) || empty($data['name'])) {
                continue;
            }
            $incomingLocales[] = $data['locale'];
            $tenant->translations()->updateOrCreate(
                ['locale' => $data['locale']],
                ['name' => $data['name'], 'notes' => $data['notes'] ?? null]
            );
        }
        if ($incomingLocales) {
            $tenant->translations()->whereNotIn('locale', $incomingLocales)->delete();
        }
    }

    private function rules(): array
    {
        return [
            'is_company' => 'required|in:0,1',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
            'national_id' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'user_id' => 'nullable|exists:users,id',
            'is_active' => 'boolean',
            'translations' => 'required|array|min:1',
            'translations.*.locale' => 'required|string|max:5',
            'translations.*.name' => 'required|string|max:255',
            'translations.*.notes' => 'nullable|string',
        ];
    }

    public function index(Request $request)
    {
        $facility = $this->facility();

        $tenants = Tenant::where('facility_id', $facility->id)
            ->with('translations')
            ->when($request->filled('search'), fn ($q) => $q->where(function ($q2) use ($request) {
                $q2->where('phone', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhere('national_id', 'like', '%' . $request->search . '%')
                    ->orWhereHas('translations', fn ($t) => $t->where('name', 'like', '%' . $request->search . '%'));
            }))
            ->when($request->filled('is_company'), fn ($q) => $q->where('is_company', $request->is_company === '1'))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('facility.tenants.index', compact('tenants'));
    }

    public function create()
    {
        $tenant = new Tenant();
        return view('facility.tenants.create', compact('tenant'));
    }

    public function store(Request $request)
    {
        $facility = $this->facility();
        $data = $request->validate($this->rules());

        $tenant = Tenant::create([
            'facility_id' => $facility->id,
            'user_id' => $data['user_id'] ?? null,
            'phone' => $data['phone'] ?? null,
            'email' => $data['email'] ?? null,
            'national_id' => $data['national_id'] ?? null,
            'is_company' => $data['is_company'] === '1',
            'contact_person' => $data['contact_person'] ?? null,
            'address' => $data['address'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        $this->syncTranslations($tenant, $data['translations']);

        return redirect()->route('facility.tenants.index')->with('success', __('تم إضافة المستأجر بنجاح'));
    }

    public function edit(Tenant $tenant)
    {
        $facility = $this->facility();
        abort_unless($tenant->facility_id === $facility->id, 404);
        $tenant->load('translations');

        return view('facility.tenants.edit', compact('tenant'));
    }

    public function update(Request $request, Tenant $tenant)
    {
        $facility = $this->facility();
        abort_unless($tenant->facility_id === $facility->id, 404);
        $data = $request->validate($this->rules());

        $tenant->update([
            'user_id' => $data['user_id'] ?? $tenant->user_id,
            'phone' => $data['phone'] ?? null,
            'email' => $data['email'] ?? null,
            'national_id' => $data['national_id'] ?? null,
            'is_company' => $data['is_company'] === '1',
            'contact_person' => $data['contact_person'] ?? null,
            'address' => $data['address'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        $this->syncTranslations($tenant, $data['translations']);

        return redirect()->route('facility.tenants.index')->with('success', __('تم تحديث المستأجر بنجاح'));
    }

    public function destroy(Tenant $tenant)
    {
        $facility = $this->facility();
        abort_unless($tenant->facility_id === $facility->id, 404);
        $tenant->delete();

        return redirect()->route('facility.tenants.index')->with('success', __('تم حذف المستأجر بنجاح'));
    }
}
