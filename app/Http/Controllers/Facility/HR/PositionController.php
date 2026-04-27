<?php

namespace App\Http\Controllers\Facility\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Position;

class PositionController extends Controller
{
    public function index(Request $request)
    {
        $facility = Auth::user()->facilities()->firstOrFail();
        $query = Position::where('facility_id', $facility->id);
        if ($search = $request->get('search')) {
            $query->where('name', 'like', "%$search%");
        }
        return response()->json($query->orderBy('name')->paginate(15));
    }

    public function store(Request $request)
    {
        $facility = Auth::user()->facilities()->firstOrFail();
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'grade' => 'nullable|string|max:100',
        ]);
        $data['facility_id'] = $facility->id;
        $position = Position::create($data);
        return response()->json($position, 201);
    }

    public function show(Position $position)
    {
        $this->authorizeAccess($position);
        return response()->json($position);
    }

    public function update(Request $request, Position $position)
    {
        $this->authorizeAccess($position);
        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'grade' => 'nullable|string|max:100',
        ]);
        $position->update($data);
        return response()->json($position);
    }

    public function destroy(Position $position)
    {
        $this->authorizeAccess($position);
        $position->delete();
        return response()->json(['deleted' => true]);
    }

    private function authorizeAccess(Position $position): void
    {
        $facility = Auth::user()->facilities()->firstOrFail();
        abort_unless($position->facility_id === $facility->id, 403);
    }
}
