<?php

namespace App\Http\Controllers\FacilitySite;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\FacilityService;
use Illuminate\Http\Request;

class ServiceController extends Controller
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
        $services = FacilityService::query()
            ->where('facility_id', $facilityModel->id)
            ->active()
            ->orderBy('order')
            ->orderBy('id')
            ->paginate(12);

        return view('facility_site.services.index', [
            'facility' => $facilityModel,
            'services' => $services,
        ]);
    }

    public function show(Request $request, $facility, $slug)
    {
        $facilityModel = $this->resolveFacility($facility);
        $service = FacilityService::query()
            ->where('facility_id', $facilityModel->id)
            ->where('slug', $slug)
            ->active()
            ->firstOrFail();

        return view('facility_site.services.show', [
            'facility' => $facilityModel,
            'service' => $service,
        ]);
    }
}
