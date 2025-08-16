<?php

namespace App\Services;

use App\Models\Page;
use App\Models\City;
use App\Models\Product;
use App\Models\Facility;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class DynamicRouteService
{
    /**
     * Handle dynamic routes for public.* namespace
     * This method can be called from a catch-all route to handle any public.* route
     */
    public static function handleRoute($routeName)
    {
        // Parse the route name to extract the model and action
        $parts = explode('.', $routeName);
        
        if (count($parts) < 2) {
            return false;
        }
        
        $namespace = $parts[0]; // 'public'
        $model = $parts[1];     // 'cities', 'products', etc.
        $action = $parts[2] ?? 'index'; // 'index', 'show', etc.
        
        if ($namespace !== 'public') {
            return false;
        }
        
        // Handle different models
        switch ($model) {
            case 'cities':
                return self::handleCitiesRoute($action);
                
            case 'products':
                return self::handleProductsRoute($action);
                
            case 'facilities':
                return self::handleFacilitiesRoute($action);
                
            case 'categories':
                return self::handleCategoriesRoute($action);
                
            case 'features':
                return self::handleFeaturesRoute($action);
                
            default:
                // Try to find a page with this slug
                return self::handlePageRoute($model);
        }
    }
    
    /**
     * Handle cities routes
     */
    private static function handleCitiesRoute($action)
    {
        switch ($action) {
            case 'index':
                $cities = City::active()->featured()->ordered()->take(6)->get();
                return view('public.cities.index', compact('cities'));
                
            default:
                abort(404);
        }
    }
    
    /**
     * Handle products routes
     */
    private static function handleProductsRoute($action)
    {
        switch ($action) {
            case 'index':
                $products = Product::active()->latest()->paginate(12);
                return view('public.products.index', compact('products'));
                
            case 'featured':
                $products = Product::active()->featured()->latest()->take(8)->get();
                return view('public.products.featured', compact('products'));
                
            case 'latest':
                $products = Product::active()->latest()->take(8)->get();
                return view('public.products.latest', compact('products'));
                
            default:
                abort(404);
        }
    }
    
    /**
     * Handle facilities routes
     */
    private static function handleFacilitiesRoute($action)
    {
        switch ($action) {
            case 'index':
                $facilities = Facility::active()->latest()->paginate(12);
                return view('public.facilities.index', compact('facilities'));
                
            case 'featured':
                $facilities = Facility::active()->featured()->latest()->take(8)->get();
                return view('public.facilities.featured', compact('facilities'));
                
            default:
                abort(404);
        }
    }
    
    /**
     * Handle categories routes
     */
    private static function handleCategoriesRoute($action)
    {
        switch ($action) {
            case 'index':
                $categories = Category::active()->ordered()->get();
                return view('public.categories.index', compact('categories'));
                
            default:
                abort(404);
        }
    }
    
    /**
     * Handle features routes
     */
    private static function handleFeaturesRoute($action)
    {
        switch ($action) {
            case 'index':
                $features = \App\Models\Feature::active()->ordered()->get();
                return view('public.features.index', compact('features'));
                
            default:
                abort(404);
        }
    }
    
    /**
     * Handle page routes (for static pages like terms, privacy, etc.)
     */
    private static function handlePageRoute($slug)
    {
        $page = Page::where('slug', $slug)->first();
        
        if (!$page) {
            abort(404);
        }
        
        // If it's a link type page, redirect to the URL
        if ($page->type === 'link' && $page->url) {
            return redirect($page->url);
        }
        
        // Otherwise, show the page content
        return view('public.static.page', compact('page'));
    }
    
    /**
     * Check if a route name is a dynamic route that this service can handle
     */
    public static function isDynamicRoute($routeName)
    {
        $parts = explode('.', $routeName);
        return count($parts) >= 2 && $parts[0] === 'public';
    }
    
    /**
     * Get the view name for a dynamic route
     */
    public static function getViewName($routeName)
    {
        $parts = explode('.', $routeName);
        
        if (count($parts) < 2) {
            return null;
        }
        
        $model = $parts[1];
        $action = $parts[2] ?? 'index';
        
        return "public.{$model}.{$action}";
    }
}
