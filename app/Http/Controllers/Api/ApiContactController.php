<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ApiContactController extends Controller
{
    /**
     * Send contact message
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // Handle contact message logic here
        // You can send email, save to database, etc.

        return response()->json([
            'success' => true,
            'message' => 'تم إرسال الرسالة بنجاح'
        ]);
    }

    /**
     * Request quote
     */
    public function requestQuote(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string',
            'property_type' => 'required|string',
            'budget' => 'required|string',
            'location' => 'required|string',
            'message' => 'nullable|string',
        ]);

        // Handle quote request logic here

        return response()->json([
            'success' => true,
            'message' => 'تم إرسال طلب عرض السعر بنجاح'
        ]);
    }

    /**
     * Send feedback
     */
    public function sendFeedback(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'rating' => 'required|integer|between:1,5',
            'message' => 'required|string',
        ]);

        // Handle feedback logic here

        return response()->json([
            'success' => true,
            'message' => 'تم إرسال التغذية الراجعة بنجاح'
        ]);
    }
}
