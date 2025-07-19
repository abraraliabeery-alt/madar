<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiUserController extends Controller
{
    /**
     * Get user favorites
     */
    public function favorites()
    {
        $user = Auth::user();

        return response()->json([
            'success' => true,
            'data' => [
                'products' => $user->favoriteProducts,
                'facilities' => $user->favoriteFacilities
            ]
        ]);
    }

    /**
     * Get user favorite products
     */
    public function favoriteProducts()
    {
        $user = Auth::user();

        return response()->json([
            'success' => true,
            'data' => $user->favoriteProducts
        ]);
    }

    /**
     * Get user favorite facilities
     */
    public function favoriteFacilities()
    {
        $user = Auth::user();

        return response()->json([
            'success' => true,
            'data' => $user->favoriteFacilities
        ]);
    }

    /**
     * Get user activity
     */
    public function activity()
    {
        $user = Auth::user();

        return response()->json([
            'success' => true,
            'data' => [
                'bookings' => $user->bookings()->latest()->take(10)->get(),
                'views' => [], // User view history
                'searches' => [] // User search history
            ]
        ]);
    }

    /**
     * Get user booking activity
     */
    public function bookingActivity()
    {
        $user = Auth::user();

        return response()->json([
            'success' => true,
            'data' => $user->bookings()->with(['product', 'facility'])->latest()->paginate(10)
        ]);
    }

    /**
     * Get user view activity
     */
    public function viewActivity()
    {
        $user = Auth::user();

        return response()->json([
            'success' => true,
            'data' => [] // User view history
        ]);
    }

    /**
     * Get user search activity
     */
    public function searchActivity()
    {
        $user = Auth::user();

        return response()->json([
            'success' => true,
            'data' => [] // User search history
        ]);
    }

    /**
     * Get user settings
     */
    public function settings()
    {
        $user = Auth::user();

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
                'preferences' => [] // User preferences
            ]
        ]);
    }

    /**
     * Update user settings
     */
    public function updateSettings(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string',
            'avatar' => 'sometimes|image|max:2048',
        ]);

        $user->update($request->only(['name', 'phone']));

        if ($request->hasFile('avatar')) {
            // Handle avatar upload
        }

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث الإعدادات بنجاح'
        ]);
    }

    /**
     * Get user privacy settings
     */
    public function privacySettings()
    {
        $user = Auth::user();

        return response()->json([
            'success' => true,
            'data' => [
                'privacy_settings' => [] // Privacy settings
            ]
        ]);
    }

    /**
     * Update user privacy settings
     */
    public function updatePrivacySettings(Request $request)
    {
        $request->validate([
            'profile_visibility' => 'sometimes|string',
            'contact_visibility' => 'sometimes|string',
        ]);

        // Update privacy settings

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث إعدادات الخصوصية بنجاح'
        ]);
    }

    /**
     * Get user security settings
     */
    public function securitySettings()
    {
        $user = Auth::user();

        return response()->json([
            'success' => true,
            'data' => [
                'security_settings' => [] // Security settings
            ]
        ]);
    }

    /**
     * Update user security settings
     */
    public function updateSecuritySettings(Request $request)
    {
        $request->validate([
            'two_factor_enabled' => 'sometimes|boolean',
            'login_notifications' => 'sometimes|boolean',
        ]);

        // Update security settings

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث إعدادات الأمان بنجاح'
        ]);
    }
}
