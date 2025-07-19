<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    /**
     * Subscribe to newsletter
     */
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:newsletter_subscribers,email',
        ]);

        // Handle newsletter subscription logic here

        return response()->json([
            'success' => true,
            'message' => 'تم الاشتراك في النشرة الإخبارية بنجاح'
        ]);
    }

    /**
     * Unsubscribe from newsletter
     */
    public function unsubscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // Handle newsletter unsubscription logic here

        return response()->json([
            'success' => true,
            'message' => 'تم إلغاء الاشتراك من النشرة الإخبارية'
        ]);
    }
}
