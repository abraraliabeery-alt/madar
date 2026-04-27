<?php

namespace App\Http\Controllers\Facility;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Product;
use App\Models\Offer;
use App\Models\Booking;
use App\Models\Contract;
use App\Services\AI\ProjectStageAiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FacilityProjectController extends Controller
{
    public function index()
    {
        $facility = Auth::user()->facilities()->firstOrFail();

        $projects = Project::query()
            ->where('facility_id', $facility->id)
            ->with('stages')
            ->latest()
            ->paginate(12);

        return view('facility.projects.index', compact('facility', 'projects'));
    }

    public function show(Project $project)
    {
        $facility = Auth::user()->facilities()->firstOrFail();

        abort_unless($project->facility_id === $facility->id, 404);

        $project->load(['stages']);

        return view('facility.projects.show', compact('facility', 'project'));
    }

    public function lifecycle(Project $project)
    {
        $facility = Auth::user()->facilities()->firstOrFail();

        abort_unless($project->facility_id === $facility->id, 404);

        $project->load(['stages']);

        $productsQuery = Product::query()
            ->where('facility_id', $facility->id)
            ->where('project_id', $project->id);

        $totalProducts = (clone $productsQuery)->count();

        $productIds = (clone $productsQuery)->pluck('id');

        $totalOffers = Offer::query()->whereIn('product_id', $productIds)->count();
        $totalBookings = Booking::query()->whereIn('product_id', $productIds)->count();
        $totalContracts = Contract::query()->whereIn('product_id', $productIds)->count();

        $projectsStats = [
            'total_products' => $totalProducts,
            'total_offers' => $totalOffers,
            'total_bookings' => $totalBookings,
            'total_contracts' => $totalContracts,
        ];

        return view('facility.projects.lifecycle', compact('facility', 'project', 'projectsStats'));
    }

    public function aiStage(Request $request, Project $project, ProjectStageAiService $aiService)
    {
        $facility = Auth::user()->facilities()->firstOrFail();

        abort_unless($project->facility_id === $facility->id, 404);

        $data = $request->validate([
            'stage_key' => 'required|in:feasibility,design',
            'notes' => 'nullable|string|max:5000',
        ]);

        $result = $aiService->analyze($project, $data['stage_key'], $data['notes'] ?? '');

        return redirect()
            ->route('facility.projects.lifecycle', $project)
            ->with('ai_'.$data['stage_key'].'_status', $result['status'] ?? 'ok')
            ->with('ai_'.$data['stage_key'].'_content', $result['content'] ?? '');
    }
}
