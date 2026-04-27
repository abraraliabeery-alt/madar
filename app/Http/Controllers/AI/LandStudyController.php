<?php

namespace App\Http\Controllers\AI;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LandStudy;
use App\Services\AI\LandStudyService;

class LandStudyController extends Controller
{
    public function form()
    {
        return view('ai.land-study-form');
    }

    public function submit(Request $request, LandStudyService $service)
    {
        $data = $request->validate([
            'location' => 'required|string|max:255',
            'area_sqm' => 'required|numeric|min:1',
            'zoning' => 'nullable|string|max:100',
            'street_width' => 'nullable|numeric|min:0',
            'budget' => 'nullable|string|max:100',
            'horizon' => 'nullable|string|max:100',
            'preferences' => 'nullable|string|max:500',
        ]);

        $study = LandStudy::create([
            'user_id' => optional($request->user())->id,
            'inputs' => $data,
            'status' => 'processing',
        ]);

        $service->generate($study);

        return redirect()->route('client.ai.land-studies.show', $study->id);
    }

    public function show($id)
    {
        $study = LandStudy::findOrFail($id);
        return view('ai.land-study-show', compact('study'));
    }

    public function list()
    {
        $studies = LandStudy::latest()->paginate(10);
        return view('ai.land-study-index', compact('studies'));
    }
}
