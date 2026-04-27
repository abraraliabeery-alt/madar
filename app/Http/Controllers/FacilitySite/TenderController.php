<?php

namespace App\Http\Controllers\FacilitySite;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class TenderController extends Controller
{
    protected function resolveFacility($facility)
    {
        return Facility::query()
            ->where(function($q) use ($facility){
                $q->where('slug', $facility)
                  ->orWhere('id', $facility)
                  ->orWhere('name', $facility)
                  ->orWhere('email', $facility);
            })
            ->where('is_active', true)
            ->firstOrFail();
    }

    public function example(Request $request, $facility)
    {
        $facilityModel = $this->resolveFacility($facility);
        $brandColor = $facilityModel->primary_color ?? '#2563eb';
        return view('facility_site.tenders.example', [
            'facility' => $facilityModel,
            'brandColor' => $brandColor,
        ]);
    }

    public function previewPdf(Request $request, $facility, $tender)
    {
        $facilityModel = $this->resolveFacility($facility);
        $brandColor = $facilityModel->primary_color ?? '#2563eb';
        $pdf = Pdf::loadView('facility_site.tenders.print', [
            'facility' => $facilityModel,
            'brandColor' => $brandColor,
            'tender' => $tender,
        ])->setPaper('a4');
        return $pdf->stream('tender-preview.pdf');
    }

    public function downloadPdf(Request $request, $facility, $tender)
    {
        $facilityModel = $this->resolveFacility($facility);
        $brandColor = $facilityModel->primary_color ?? '#2563eb';
        $pdf = Pdf::loadView('facility_site.tenders.print', [
            'facility' => $facilityModel,
            'brandColor' => $brandColor,
            'tender' => $tender,
        ])->setPaper('a4');
        return $pdf->download('tender.pdf');
    }
}
