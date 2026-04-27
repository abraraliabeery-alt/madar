<?php

namespace App\Http\Controllers\FacilitySite;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\FacilityGalleryItem;
use Illuminate\Http\Request;

class GalleryController extends Controller
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
        $category = $request->query('category');

        $items = FacilityGalleryItem::query()
            ->where('facility_id', $facilityModel->id)
            ->when($category, fn($q) => $q->where('category', $category))
            ->active()
            ->orderBy('order')
            ->orderBy('id')
            ->paginate(24);

        $categories = FacilityGalleryItem::query()
            ->where('facility_id', $facilityModel->id)
            ->select('category')
            ->distinct()
            ->pluck('category')
            ->filter();

        return view('facility_site.gallery.index', [
            'facility' => $facilityModel,
            'items' => $items,
            'categories' => $categories,
            'currentCategory' => $category,
        ]);
    }

    public function show(Request $request, $facility, $slug)
    {
        $facilityModel = $this->resolveFacility($facility);
        $item = FacilityGalleryItem::query()
            ->where('facility_id', $facilityModel->id)
            ->where('slug', $slug)
            ->active()
            ->firstOrFail();

        return view('facility_site.gallery.show', [
            'facility' => $facilityModel,
            'item' => $item,
        ]);
    }
}
