<?php

namespace App\Http\Controllers\FacilitySite;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\FacilityPartner;
use Illuminate\Http\Request;

class PartnerController extends Controller
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
        $partners = FacilityPartner::query()
            ->where('facility_id', $facilityModel->id)
            ->active()
            ->orderBy('order')
            ->orderBy('id')
            ->get();

        return view('facility_site.partners.index', [
            'facility' => $facilityModel,
            'partners' => $partners,
        ]);
    }

    public function show(Request $request, $facility, $slug)
    {
        $facilityModel = $this->resolveFacility($facility);
        $partner = FacilityPartner::query()
            ->where('facility_id', $facilityModel->id)
            ->where('slug', $slug)
            ->active()
            ->firstOrFail();

        return view('facility_site.partners.show', [
            'facility' => $facilityModel,
            'partner' => $partner,
        ]);
    }
}
