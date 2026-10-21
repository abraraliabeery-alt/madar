<?php

namespace App\Http\Controllers\Facility;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Quote;
use App\Enums\QuoteStatus;
use App\Enums\DiscountMethod;

class FacilityQuoteController extends Controller
{
    private function facility()
    {
        return Auth::user()->facilities()->firstOrFail();
    }

    private function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'client_name' => 'nullable|string|max:255',
            'client_email' => 'nullable|email|max:255',
            'client_phone' => 'nullable|string|max:30',
            'status' => 'required|in:' . implode(',', array_column(QuoteStatus::cases(), 'value')),
            'notes' => 'nullable|string',
            'items' => 'nullable|array',
            'items.*.name' => 'required|string|max:255',
            'items.*.description' => 'nullable|string',
            'items.*.quantity' => 'required|numeric|min:0',
            'items.*.unit' => 'nullable|string|max:50',
            'items.*.unit_price' => 'required|numeric|min:0',
            'terms' => 'nullable|array',
            'terms.*.title' => 'required|string|max:255',
            'terms.*.content' => 'nullable|string',
            'discounts' => 'nullable|array',
            'discounts.*.name' => 'required|string|max:255',
            'discounts.*.method' => 'required|in:' . implode(',', array_column(DiscountMethod::cases(), 'value')),
            'discounts.*.value' => 'required|numeric|min:0',
            'discounts.*.condition' => 'nullable|string|max:255',
        ];
    }

    private function syncChildren(Quote $quote, array $data): void
    {
        $quote->items()->delete();
        foreach ($data['items'] ?? [] as $i => $item) {
            $quote->items()->create($item + ['sort_order' => $i]);
        }

        $quote->terms()->delete();
        foreach ($data['terms'] ?? [] as $i => $term) {
            $quote->terms()->create($term + ['sort_order' => $i]);
        }

        $quote->discounts()->delete();
        foreach ($data['discounts'] ?? [] as $i => $discount) {
            $quote->discounts()->create($discount + ['sort_order' => $i]);
        }
    }

    public function index(Request $request)
    {
        $facility = $this->facility();

        $quotes = Quote::where('facility_id', $facility->id)
            ->withCount('items')
            ->with(['items', 'discounts'])
            ->when($request->filled('search'), fn ($q) => $q->where(function ($q2) use ($request) {
                $q2->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('quote_number', 'like', '%' . $request->search . '%')
                    ->orWhere('client_name', 'like', '%' . $request->search . '%');
            }))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('facility.quotes.index', compact('quotes'));
    }

    public function create()
    {
        $this->facility();

        $quote = new Quote();
        $quoteNumber = 'QT-' . date('Y') . '-' . str_pad((string) (Quote::max('id') + 1), 4, '0', STR_PAD_LEFT);
        $statuses = QuoteStatus::cases();
        $methods = DiscountMethod::cases();

        return view('facility.quotes.create', compact('quote', 'quoteNumber', 'statuses', 'methods'));
    }

    public function store(Request $request)
    {
        $facility = $this->facility();
        $data = $request->validate($this->rules());

        $quote = DB::transaction(function () use ($facility, $data) {
            $quote = Quote::create([
                'facility_id' => $facility->id,
                'created_by' => Auth::id(),
                'quote_number' => 'QT-' . date('Y') . '-' . str_pad((string) (Quote::max('id') + 1), 4, '0', STR_PAD_LEFT),
                'title' => $data['title'],
                'client_name' => $data['client_name'] ?? null,
                'client_email' => $data['client_email'] ?? null,
                'client_phone' => $data['client_phone'] ?? null,
                'status' => $data['status'],
                'notes' => $data['notes'] ?? null,
            ]);

            $this->syncChildren($quote, $data);

            return $quote;
        });

        return redirect()->route('facility.quotes.show', $quote)->with('success', __('تم إنشاء عرض السعر بنجاح'));
    }

    public function show(Quote $quote)
    {
        $facility = $this->facility();
        abort_unless($quote->facility_id === $facility->id, 404);
        $quote->load(['items', 'terms', 'discounts']);

        return view('facility.quotes.show', compact('quote'));
    }

    public function edit(Quote $quote)
    {
        $facility = $this->facility();
        abort_unless($quote->facility_id === $facility->id, 404);
        $quote->load(['items', 'terms', 'discounts']);

        $quoteNumber = $quote->quote_number;
        $statuses = QuoteStatus::cases();
        $methods = DiscountMethod::cases();

        return view('facility.quotes.edit', compact('quote', 'quoteNumber', 'statuses', 'methods'));
    }

    public function update(Request $request, Quote $quote)
    {
        $facility = $this->facility();
        abort_unless($quote->facility_id === $facility->id, 404);
        $data = $request->validate($this->rules());

        DB::transaction(function () use ($quote, $data) {
            $quote->update(collect($data)->only([
                'title', 'client_name', 'client_email', 'client_phone', 'status', 'notes',
            ])->all());

            $this->syncChildren($quote, $data);
        });

        return redirect()->route('facility.quotes.show', $quote)->with('success', __('تم تحديث عرض السعر بنجاح'));
    }

    public function destroy(Quote $quote)
    {
        $facility = $this->facility();
        abort_unless($quote->facility_id === $facility->id, 404);
        $quote->delete();

        return redirect()->route('facility.quotes.index')->with('success', __('تم حذف عرض السعر'));
    }

    /**
     * تحميل عرض السعر كملف PDF
     */
    public function download(Quote $quote, \App\Services\QuotePdfService $pdf)
    {
        $facility = $this->facility();
        abort_unless($quote->facility_id === $facility->id, 404);

        return $pdf->download($quote);
    }

    /**
     * اقتراح سعر بند بالذكاء الاصطناعي
     */
    public function suggestPrice(Request $request, \App\Services\QuotePricingService $pricing)
    {
        $this->facility();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'unit' => 'nullable|string|max:50',
        ]);

        $price = $pricing->suggest($data['name'], $data['unit'] ?? null);

        if ($price === null) {
            return response()->json(['price' => null, 'message' => __('خدمة الاقتراح غير متاحة حالياً')], 422);
        }

        return response()->json(['price' => $price]);
    }
}
