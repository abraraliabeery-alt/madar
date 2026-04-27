<?php

namespace App\Http\Controllers\FacilitySite;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\FacilityProject;
use Illuminate\Http\Request;

class ProjectController extends Controller
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
        $projects = FacilityProject::query()
            ->where('facility_id', $facilityModel->id)
            ->published()
            ->orderBy('order')
            ->orderBy('id')
            ->paginate(12);

        return view('facility_site.projects.index', [
            'facility' => $facilityModel,
            'projects' => $projects,
        ]);
    }

    public function show(Request $request, $facility, $slug)
    {
        $facilityModel = $this->resolveFacility($facility);
        $project = FacilityProject::query()
            ->where('facility_id', $facilityModel->id)
            ->where('slug', $slug)
            ->published()
            ->firstOrFail();

        return view('facility_site.projects.show', [
            'facility' => $facilityModel,
            'project' => $project,
        ]);
    }
}
