<?php

namespace App\Http\Controllers\Facility;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Project;
use App\Models\Building;
use Carbon\Carbon;

class FacilityRentalController extends Controller
{
    public function index(Request $request)
    {
        $facility = Auth::user()->facilities()->first();
        if (!$facility) {
            return redirect()->route('facility.create');
        }

        $query = Product::with([
            'category',
            'project.translations',
            'building.translations',
            'offers' => function($q){
                $q->where('offer_type', 'like', 'rent_%')->latest();
            }
        ])
        ->where('facility_id', $facility->id)
        ->where('available_for_rent', true);

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }
        if ($request->filled('building_id')) {
            $query->where('building_id', $request->building_id);
        }
        if ($request->filled('search')) {
            $query->where('address', 'like', '%'.$request->search.'%');
        }

        // Offer status filter
        if ($request->filled('offer_status')) {
            $status = $request->offer_status;
            if ($status === 'active') {
                $query->whereHas('offers', function($q){
                    $q->where('offer_type', 'like', 'rent_%')
                      ->where('is_active', true)
                      ->where(function($dateQ){
                          $today = Carbon::today()->toDateString();
                          $dateQ->whereNull('valid_from')->orWhere('valid_from', '<=', $today);
                      })
                      ->where(function($dateQ){
                          $today = Carbon::today()->toDateString();
                          $dateQ->whereNull('valid_to')->orWhere('valid_to', '>=', $today);
                      });
                });
            } elseif ($status === 'inactive') {
                $query->whereHas('offers', function($q){
                    $q->where('offer_type', 'like', 'rent_%')
                      ->where(function($x){
                          $x->where('is_active', false)->orWhereNull('is_active');
                      });
                });
            } elseif ($status === 'expiring') {
                $query->whereHas('offers', function($q){
                    $q->where('offer_type', 'like', 'rent_%')
                      ->where('is_active', true)
                      ->whereNotNull('valid_to')
                      ->whereBetween('valid_to', [Carbon::today()->toDateString(), Carbon::today()->addDays(14)->toDateString()]);
                });
            }
        }

        $products = $query->paginate(20);

        // مصادر الفلاتر (بدون الاعتماد على أعمدة الاسم في الجدول الأساسي)
        $projects = Project::where('facility_id', $facility->id)->with('translations')->orderBy('id', 'desc')->get(['id']);
        $buildings = Building::where('facility_id', $facility->id)->with('translations')->orderBy('id', 'desc')->get(['id']);

        return view('facility.rentals.index', compact('products','projects','buildings'));
    }
}

