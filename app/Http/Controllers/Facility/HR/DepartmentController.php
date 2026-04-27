<?php

namespace App\Http\Controllers\Facility\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Department;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        $facility = Auth::user()->facilities()->firstOrFail();
        $query = Department::where('facility_id', $facility->id);
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
            'manager_user_id' => 'nullable|exists:users,id',
        ]);
        $data['facility_id'] = $facility->id;
        $department = Department::create($data);
        return response()->json($department, 201);
    }

    public function show(Department $department)
    {
        $this->authorizeAccess($department);
        return response()->json($department);
    }

    public function update(Request $request, Department $department)
    {
        $this->authorizeAccess($department);
        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'manager_user_id' => 'nullable|exists:users,id',
        ]);
        $department->update($data);
        return response()->json($department);
    }

    public function destroy(Department $department)
    {
        $this->authorizeAccess($department);
        $department->delete();
        return response()->json(['deleted' => true]);
    }

    private function authorizeAccess(Department $department): void
    {
        $facility = Auth::user()->facilities()->firstOrFail();
        abort_unless($department->facility_id === $facility->id, 403);
    }
}
