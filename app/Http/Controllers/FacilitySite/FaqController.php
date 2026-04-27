<?php

namespace App\Http\Controllers\FacilitySite;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\FacilityFaq;
use Illuminate\Http\Request;

class FaqController extends Controller
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

    public function index(Request $request, $facility)
    {
        $facilityModel = $this->resolveFacility($facility);
        $faqs = FacilityFaq::query()
            ->where('facility_id', $facilityModel->id)
            ->active()
            ->orderBy('order')
            ->orderBy('id')
            ->get();

        return view('facility_site.faqs.index', [
            'facility' => $facilityModel,
            'faqs' => $faqs,
        ]);
    }
}
