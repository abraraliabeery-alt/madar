<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Facility;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Generate main sitemap
     */
    public function index()
    {
        $content = view('public.sitemap.index')->render();

        return response($content, 200)
            ->header('Content-Type', 'text/xml');
    }

    /**
     * Generate products sitemap
     */
    public function products()
    {
        $products = Product::where('is_active', true)
            ->where('is_verified', true)
            ->get();

        $content = view('public.sitemap.products', compact('products'))->render();

        return response($content, 200)
            ->header('Content-Type', 'text/xml');
    }

    /**
     * Generate facilities sitemap
     */
    public function facilities()
    {
        $facilities = Facility::where('is_active', true)
            ->where('is_verified', true)
            ->get();

        $content = view('public.sitemap.facilities', compact('facilities'))->render();

        return response($content, 200)
            ->header('Content-Type', 'text/xml');
    }

    /**
     * Generate categories sitemap
     */
    public function categories()
    {
        $categories = Category::where('is_active', true)->get();

        $content = view('public.sitemap.categories', compact('categories'))->render();

        return response($content, 200)
            ->header('Content-Type', 'text/xml');
    }
}
