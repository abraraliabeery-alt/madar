<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiAppointmentController extends Controller
{
    /**
     * Display a listing of user appointments
     */
    public function index()
    {
        $user = Auth::user();
        $appointments = Appointment::where('user_id', $user->id)->with(['facility'])->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $appointments
        ]);
    }

    /**
     * Store a new appointment
     */
    public function store(Request $request)
    {
        $request->validate([
            'facility_id' => 'required|exists:facilities,id',
            'appointment_date' => 'required|date|after:today',
            'appointment_time' => 'required|date_format:H:i',
            'subject' => 'required|string|max:255',
            'message' => 'nullable|string',
        ]);

        $user = Auth::user();
        $appointment = $user->appointments()->create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'تم إنشاء الموعد بنجاح',
            'data' => $appointment
        ]);
    }

    /**
     * Display the specified appointment
     */
    public function show(Appointment $appointment)
    {
        $appointment->load(['facility']);

        return response()->json([
            'success' => true,
            'data' => $appointment
        ]);
    }

    /**
     * Update the specified appointment
     */
    public function update(Request $request, Appointment $appointment)
    {
        $request->validate([
            'appointment_date' => 'sometimes|date|after:today',
            'appointment_time' => 'sometimes|date_format:H:i',
            'subject' => 'sometimes|string|max:255',
            'message' => 'nullable|string',
        ]);

        $appointment->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث الموعد بنجاح',
            'data' => $appointment
        ]);
    }

    /**
     * Remove the specified appointment
     */
    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف الموعد بنجاح'
        ]);
    }

    /**
     * Cancel appointment
     */
    public function cancel(Appointment $appointment)
    {
        $appointment->update(['status' => 'cancelled']);

        return response()->json([
            'success' => true,
            'message' => 'تم إلغاء الموعد بنجاح'
        ]);
    }

    /**
     * Reschedule appointment
     */
    public function reschedule(Request $request, Appointment $appointment)
    {
        $request->validate([
            'appointment_date' => 'required|date|after:today',
            'appointment_time' => 'required|date_format:H:i',
        ]);

        $appointment->update($request->only(['appointment_date', 'appointment_time']));

        return response()->json([
            'success' => true,
            'message' => 'تم إعادة جدولة الموعد بنجاح'
        ]);
    }
}
