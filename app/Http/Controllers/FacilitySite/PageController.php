<?php

namespace App\Http\Controllers\FacilitySite;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\FacilityPage;
use Illuminate\Http\Request;

class PageController extends Controller
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

    public function show(Request $request, $facility, $slug)
    {
        $facilityModel = $this->resolveFacility($facility);
        $page = FacilityPage::query()
            ->where('facility_id', $facilityModel->id)
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        return view('facility_site.pages.show', [
            'facility' => $facilityModel,
            'page' => $page,
        ]);
    }
}
