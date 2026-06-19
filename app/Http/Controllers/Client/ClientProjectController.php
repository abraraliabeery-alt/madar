<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Neighborhood;
use App\Models\Attribute;
use App\Models\Project;
use App\Models\ProjectAttachment;
use App\Models\ProjectTranslation;
use App\Models\ExecutionRequest;
use App\Models\ExecutionRequestTranslation;
use App\Models\StageAttribute;
use App\Models\Street;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientProjectController extends Controller
{
    public function create()
    {
        $locales = (array) config('locales.available');

        $cities = City::query()->active()->ordered()->get();
        $neighborhoods = Neighborhood::query()->where('is_active', true)->orderBy('name')->get();
        $streets = Street::query()->where('is_active', true)->orderBy('name')->get();

        $ideaStageAttributes = StageAttribute::query()
            ->where('stage_key', 'idea')
            ->with('translations')
            ->orderBy('order')
            ->get();

        $projectAttributes = Attribute::query()
            ->forProjects()
            ->with('translations')
            ->orderBy('id')
            ->get();

        return view('client.projects.create', compact('locales', 'cities', 'neighborhoods', 'streets', 'ideaStageAttributes', 'projectAttributes'));
    }

    public function show(Project $project)
    {
        abort_unless($project->client_user_id === Auth::id(), 404);

        $project->load([
            'translations',
            'city',
            'neighborhood',
            'street',
            'attachmentsFiles',
        ]);

        return view('client.projects.show', compact('project'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'translations' => 'required|array|min:1',
            'translations.*.locale' => 'required|string|max:10|distinct',
            'translations.*.name' => 'nullable|string|max:255',
            'translations.*.description' => 'nullable|string',
            'image' => 'nullable|image|max:4096',
            'attachments_files' => 'nullable|array|max:10',
            'attachments_files.*' => 'file|max:10240|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx',
            'status' => 'nullable|string|in:draft,open_for_bids,awarded,closed,cancelled',
            'bid_deadline' => 'required_if:status,open_for_bids|nullable|date|after_or_equal:today',
            'qa_deadline' => 'nullable|date|before_or_equal:bid_deadline',
            'site_visit_date' => 'nullable|date',
            'project_type' => 'nullable|in:residential,commercial,industrial,government,other',
            'request_type' => 'nullable|string|max:255',
            'scope_of_work' => 'nullable|string|max:255',
            'finishing_level' => 'nullable|string|max:255',
            'land_area' => 'nullable|numeric',
            'built_area' => 'nullable|numeric',
            'floors_count' => 'nullable|integer|min:0',
            'rooms_count' => 'nullable|integer|min:0',
            'bathrooms_count' => 'nullable|integer|min:0',
            'budget_min' => 'nullable|numeric|min:0',
            'budget_max' => 'nullable|numeric|min:0',
            'start_date' => 'nullable|date',
            'duration_days' => 'nullable|integer|min:0',
            'requirements' => 'nullable|string',
            'city_id' => 'nullable|exists:cities,id',
            'neighborhood_id' => 'nullable|exists:neighborhoods,id',
            'street_id' => 'nullable|exists:streets,id',
            'address' => 'nullable|string|max:255',
            'google_maps_url' => 'nullable|url',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'attachments' => 'nullable|array',
            'attachments.*' => 'nullable|string|max:255',
            'stage_attributes' => 'nullable|array',
            'attributes' => 'nullable|array',
        ]);

        $ideaStageAttributes = StageAttribute::query()
            ->where('stage_key', 'idea')
            ->get();

        if ($ideaStageAttributes->isNotEmpty()) {
            foreach ($ideaStageAttributes as $attribute) {
                if (!$attribute->required) {
                    continue;
                }

                $value = $request->input('stage_attributes.'.$attribute->id);
                if ($value === null || $value === '') {
                    return back()
                        ->withErrors(['stage_attributes.'.$attribute->id => 'هذا الحقل مطلوب'])
                        ->withInput();
                }
            }
        }

        $projectAttributes = Attribute::query()->forProjects()->get();
        if ($projectAttributes->isNotEmpty()) {
            foreach ($projectAttributes as $attribute) {
                if (!$attribute->required) {
                    continue;
                }

                $value = $request->input('attributes.'.$attribute->id.'.value');
                if ($value === null || $value === '') {
                    return back()
                        ->withErrors(['attributes.'.$attribute->id.'.value' => 'هذا الحقل مطلوب'])
                        ->withInput();
                }
            }
        }

        $translations = collect($data['translations'] ?? [])
            ->filter(fn ($t) => !empty($t['name']))
            ->values();

        if ($translations->isEmpty()) {
            return back()
                ->withErrors(['translations' => 'اسم المشروع مطلوب في لغة واحدة على الأقل'])
                ->withInput();
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('projects', 'public');
        }

        $project = Project::create([
            'client_user_id' => Auth::id(),
            'project_type' => $data['project_type'] ?? null,
            'request_type' => $data['request_type'] ?? null,
            'scope_of_work' => $data['scope_of_work'] ?? null,
            'finishing_level' => $data['finishing_level'] ?? null,
            'land_area' => $data['land_area'] ?? null,
            'built_area' => $data['built_area'] ?? null,
            'floors_count' => $data['floors_count'] ?? null,
            'rooms_count' => $data['rooms_count'] ?? null,
            'bathrooms_count' => $data['bathrooms_count'] ?? null,
            'budget_min' => $data['budget_min'] ?? null,
            'budget_max' => $data['budget_max'] ?? null,
            'start_date' => $data['start_date'] ?? null,
            'duration_days' => $data['duration_days'] ?? null,
            'requirements' => $data['requirements'] ?? null,
            'city_id' => $data['city_id'] ?? null,
            'neighborhood_id' => $data['neighborhood_id'] ?? null,
            'street_id' => $data['street_id'] ?? null,
            'address' => $data['address'] ?? null,
            'google_maps_url' => $data['google_maps_url'] ?? null,
            'latitude' => $data['latitude'] ?? null,
            'longitude' => $data['longitude'] ?? null,
            'attachments' => $data['attachments'] ?? null,
            'image' => $imagePath,
            'status' => $data['status'] ?? 'draft',
            'bid_deadline' => $data['bid_deadline'] ?? null,
            'qa_deadline' => $data['qa_deadline'] ?? null,
            'site_visit_date' => $data['site_visit_date'] ?? null,
        ]);

        if ($request->hasFile('attachments_files')) {
            foreach ((array) $request->file('attachments_files') as $file) {
                if (!$file) {
                    continue;
                }

                $path = $file->store('project-attachments/' . $project->id, 'public');

                ProjectAttachment::create([
                    'project_id' => $project->id,
                    'uploaded_by_user_id' => Auth::id(),
                    'disk' => 'public',
                    'path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getClientMimeType(),
                    'size_bytes' => $file->getSize(),
                ]);
            }
        }

        foreach ($translations as $translation) {
            ProjectTranslation::create([
                'project_id' => $project->id,
                'locale' => $translation['locale'],
                'name' => $translation['name'],
                'description' => $translation['description'] ?? null,
            ]);
        }

        // Publish to the public execution marketplace when opened for bids
        if (($project->status ?? 'draft') === 'open_for_bids') {
            $executionRequest = ExecutionRequest::create([
                'facility_id' => null,
                'project_id' => $project->id,
                'product_id' => null,
                'type' => $project->request_type ?? $project->project_type,
                'status' => 'open',
                'priority' => 'normal',
                'budget_min' => $project->budget_min,
                'budget_max' => $project->budget_max,
                'due_date' => $project->bid_deadline,
                'data' => [
                    'source' => 'client-project',
                    'client_user_id' => $project->client_user_id,
                    'scope_of_work' => $project->scope_of_work,
                    'finishing_level' => $project->finishing_level,
                    'start_date' => optional($project->start_date)->toDateString(),
                    'duration_days' => $project->duration_days,
                    'city_id' => $project->city_id,
                    'neighborhood_id' => $project->neighborhood_id,
                    'street_id' => $project->street_id,
                    'address' => $project->address,
                    'latitude' => $project->latitude,
                    'longitude' => $project->longitude,
                    'google_maps_url' => $project->google_maps_url,
                    'qa_deadline' => $project->qa_deadline,
                    'site_visit_date' => $project->site_visit_date,
                ],
            ]);

            foreach ($translations as $translation) {
                $title = $translation['name'] ?? null;
                $desc = $translation['description'] ?? null;

                ExecutionRequestTranslation::create([
                    'execution_request_id' => $executionRequest->id,
                    'locale' => $translation['locale'],
                    'title' => $title ?: ('طلب تنفيذ #' . $project->id),
                    'description' => $desc,
                ]);
            }
        }

        if ($projectAttributes->isNotEmpty() && $request->has('attributes')) {
            $projectPivot = [];
            foreach ($projectAttributes as $attribute) {
                $value = $request->input('attributes.'.$attribute->id.'.value');
                if ($value === null || $value === '') {
                    continue;
                }
                $projectPivot[$attribute->id] = ['value' => (string) $value];
            }

            if (!empty($projectPivot)) {
                $project->attributes()->syncWithoutDetaching($projectPivot);
            }
        }

        $ideaStage = $project->stages()->where('key', 'idea')->first();
        if ($ideaStage && $ideaStageAttributes->isNotEmpty()) {
            $payload = [];
            foreach ($ideaStageAttributes as $attribute) {
                $value = $request->input('stage_attributes.'.$attribute->id);
                if ($value === null || $value === '') {
                    continue;
                }
                $payload[$attribute->id] = ['value' => (string) $value];
            }

            if (!empty($payload)) {
                $ideaStage->attributes()->syncWithoutDetaching($payload);
            }
        }

        return redirect()->route('client.dashboard')->with('success', 'تم إنشاء المشروع بنجاح');
    }
}
