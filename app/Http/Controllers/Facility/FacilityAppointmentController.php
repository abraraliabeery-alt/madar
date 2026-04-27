<?php

namespace App\Http\Controllers\Facility;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FacilityAppointmentController extends Controller
{
    public function index(Request $request)
    {
        $facility = Auth::user()->facilities()->firstOrFail();

        $query = Appointment::with(['user'])
            ->where('facility_id', $facility->id);

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        if ($request->filled('from')) {
            $query->whereDate('appointment_time', '>=', $request->get('from'));
        }

        if ($request->filled('to')) {
            $query->whereDate('appointment_time', '<=', $request->get('to'));
        }

        $appointments = $query
            ->latest('appointment_time')
            ->paginate(20)
            ->appends($request->query());

        $stats = [
            'total' => Appointment::where('facility_id', $facility->id)->count(),
            'scheduled' => Appointment::where('facility_id', $facility->id)->where('status', 'scheduled')->count(),
            'completed' => Appointment::where('facility_id', $facility->id)->where('status', 'completed')->count(),
            'cancelled' => Appointment::where('facility_id', $facility->id)->where('status', 'cancelled')->count(),
        ];

        return view('facility.appointments.index', compact('facility', 'appointments', 'stats'));
    }

    public function show(Appointment $appointment)
    {
        $facility = Auth::user()->facilities()->firstOrFail();

        if ($appointment->facility_id !== $facility->id) {
            abort(403);
        }

        $appointment->load(['user', 'facility']);

        return view('facility.appointments.show', compact('facility', 'appointment'));
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        $facility = Auth::user()->facilities()->firstOrFail();

        if ($appointment->facility_id !== $facility->id) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:scheduled,completed,cancelled,rescheduled',
        ]);

        $appointment->update([
            'status' => $request->input('status'),
        ]);

        return redirect()
            ->route('facility.appointments.show', $appointment)
            ->with('success', 'تم تحديث حالة الموعد بنجاح');
    }
}
