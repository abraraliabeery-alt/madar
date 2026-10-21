<?php

namespace App\Http\Controllers\Facility;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\FacilityTender;
use App\Services\TenderDocumentService;
use App\Services\TenderWordService;
use App\Services\AI\TenderExtractionService;
use App\Services\AI\TenderResponseService;

class FacilityTenderController extends Controller
{
    private function facility()
    {
        return Auth::user()->facilities()->firstOrFail();
    }

    private function checkAccess(FacilityTender $tender): void
    {
        abort_unless($tender->facility_id === $this->facility()->id, 404);
    }

    public function index(Request $request)
    {
        $facility = $this->facility();

        $tenders = FacilityTender::where('facility_id', $facility->id)
            ->when($request->filled('search'), fn ($q) => $q->where(function ($q2) use ($request) {
                $q2->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('reference', 'like', '%' . $request->search . '%');
            }))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('facility.tenders.index', compact('tenders'));
    }

    public function create()
    {
        $this->facility();
        return view('facility.tenders.create');
    }

    public function store(Request $request, TenderDocumentService $documents, TenderExtractionService $extraction)
    {
        $facility = $this->facility();

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'reference' => 'nullable|string|max:100',
            'content' => 'required_without:document|nullable|string',
            'document' => 'nullable|file|mimes:txt,docx,xlsx,xls,pdf|max:10240',
        ]);

        $content = $data['content'] ?? '';
        $filePath = null;

        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $filePath = $file->store('tenders', 'public');
            try {
                $content = trim($content) !== '' ? $content : $documents->extractText($file);
            } catch (\Throwable $e) {
                // يبقى المحتوى اليدوي إن تعذّر استخراج نص الملف
            }
        }

        $tenderData = ['content' => $content];
        if ($filePath) {
            $tenderData['file_path'] = $filePath;
        }

        $status = 'draft';
        try {
            $tenderData = array_merge($tenderData, $extraction->analyze($content));
            $status = 'analyzed';
        } catch (\Throwable $e) {
            $tenderData['extraction_error'] = $e->getMessage();
        }

        $tender = FacilityTender::create([
            'facility_id' => $facility->id,
            'title' => $data['title'],
            'reference' => $data['reference'] ?? null,
            'status' => $status,
            'data' => $tenderData,
        ]);

        return redirect()->route('facility.tenders.show', $tender)
            ->with('success', __('تم إنشاء المناقصة بنجاح'));
    }

    public function show(FacilityTender $tender)
    {
        $this->checkAccess($tender);
        return view('facility.tenders.show', compact('tender'));
    }

    public function respond(FacilityTender $tender)
    {
        $this->checkAccess($tender);
        return view('facility.tenders.respond', compact('tender'));
    }

    /**
     * توليد الرد على المناقصة بالذكاء الاصطناعي
     */
    public function generate(Request $request, FacilityTender $tender, TenderResponseService $responses)
    {
        $this->checkAccess($tender);
        $facility = $this->facility();

        $response = $responses->generate($tender, $facility);

        $tender->update(['data' => array_merge($tender->data ?? [], ['response' => $response])]);

        return redirect()->route('facility.tenders.respond', $tender)
            ->with('success', __('تم توليد الرد بنجاح'));
    }

    /**
     * تحميل الرد كصفحة قابلة للطباعة
     */
    public function downloadResponse(FacilityTender $tender)
    {
        $this->checkAccess($tender);

        return view('facility.tenders.response_pdf', [
            'tender' => $tender,
            'facility' => $this->facility(),
            'brandColor' => '#0d9488',
        ]);
    }

    /**
     * تحميل الرد كملف Word
     */
    public function downloadWord(FacilityTender $tender, TenderWordService $word)
    {
        $this->checkAccess($tender);

        $path = $word->generateDocx($tender);

        return response()->download($path, 'tender-' . $tender->id . '.docx')->deleteFileAfterSend();
    }

    public function destroy(FacilityTender $tender)
    {
        $this->checkAccess($tender);

        if ($path = $tender->data['file_path'] ?? null) {
            Storage::disk('public')->delete($path);
        }
        $tender->delete();

        return redirect()->route('facility.tenders.index')->with('success', __('تم حذف المناقصة'));
    }
}
