<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ApiAttributeController extends Controller
{
    /**
     * Get attributes by category
     */
    public function getByCategory(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'locale' => 'nullable|string|in:ar,en'
        ]);

        $category = Category::findOrFail($request->category_id);
        
        // Get locale from request parameter, session, or fallback to default
        $locale = $request->get('locale') ?? Session::get('locale', config('app.locale'));
        
        $attributes = $category->attributes()
            ->with(['translations' => function($query) use ($locale) {
                $query->where('locale', $locale);
            }])
            ->orderBy('id')
            ->get()
            ->map(function($attribute) use ($locale) {
                return [
                    'id' => $attribute->id,
                    'type' => $attribute->type,
                    'required' => $attribute->required,
                    'icon' => $attribute->icon,
                    'symbol' => $attribute->Symbol,
                    'show_in_card' => $attribute->show_in_card,
                    'name' => $attribute->getTranslatedName($locale),
                    'translated_symbol' => $attribute->getTranslatedSymbol($locale),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $attributes
        ]);
    }

    /**
     * Get all attributes
     */
    public function index(Request $request)
    {
        $request->validate([
            'locale' => 'nullable|string|in:ar,en'
        ]);
        
        // Get locale from request parameter, session, or fallback to default
        $locale = $request->get('locale') ?? Session::get('locale', config('app.locale'));
        
        $attributes = Attribute::with(['translations' => function($query) use ($locale) {
            $query->where('locale', $locale);
        }])
        ->orderBy('id')
        ->get()
        ->map(function($attribute) use ($locale) {
            return [
                'id' => $attribute->id,
                'type' => $attribute->type,
                'required' => $attribute->required,
                'category_id' => $attribute->category_id,
                'icon' => $attribute->icon,
                'symbol' => $attribute->Symbol,
                'show_in_card' => $attribute->show_in_card,
                'name' => $attribute->getTranslatedName($locale),
                'translated_symbol' => $attribute->getTranslatedSymbol($locale),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $attributes
        ]);
    }
}
