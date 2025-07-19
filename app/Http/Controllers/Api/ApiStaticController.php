<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApiStaticController extends Controller
{
    /**
     * About page data
     */
    public function about()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'title' => 'من نحن',
                'content' => 'محتوى صفحة من نحن',
                'team' => [
                    // Team members data
                ]
            ]
        ]);
    }

    /**
     * Services page data
     */
    public function services()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'title' => 'خدماتنا',
                'services' => [
                    // Services data
                ]
            ]
        ]);
    }

    /**
     * Team page data
     */
    public function team()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'title' => 'فريق العمل',
                'members' => [
                    // Team members data
                ]
            ]
        ]);
    }

    /**
     * Terms page data
     */
    public function terms()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'title' => 'الشروط والأحكام',
                'content' => 'محتوى الشروط والأحكام'
            ]
        ]);
    }

    /**
     * Privacy page data
     */
    public function privacy()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'title' => 'سياسة الخصوصية',
                'content' => 'محتوى سياسة الخصوصية'
            ]
        ]);
    }

    /**
     * FAQ page data
     */
    public function faq()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'title' => 'الأسئلة الشائعة',
                'faqs' => [
                    // FAQ data
                ]
            ]
        ]);
    }

    /**
     * Sitemap data
     */
    public function sitemap()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'pages' => [
                    // Static pages
                ],
                'categories' => [
                    // Categories
                ],
                'products' => [
                    // Products
                ],
                'facilities' => [
                    // Facilities
                ]
            ]
        ]);
    }
}
