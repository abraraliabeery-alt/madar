<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Facility;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RssController extends Controller
{
    /**
     * Generate products RSS feed
     */
    public function products()
    {
        $products = Product::where('is_active', true)
            ->where('is_verified', true)
            ->latest()
            ->take(20)
            ->get();

        $content = view('public.rss.products', compact('products'))->render();

        return response($content, 200)
            ->header('Content-Type', 'application/rss+xml');
    }

    /**
     * Generate facilities RSS feed
     */
    public function facilities()
    {
        $facilities = Facility::where('is_active', true)
            ->where('is_verified', true)
            ->latest()
            ->take(20)
            ->get();

        $content = view('public.rss.facilities', compact('facilities'))->render();

        return response($content, 200)
            ->header('Content-Type', 'application/rss+xml');
    }

    /**
     * Generate news RSS feed
     */
    public function news()
    {
        $news = News::where('is_active', true)
            ->latest()
            ->take(20)
            ->get();

        $content = view('public.rss.news', compact('news'))->render();

        return response($content, 200)
            ->header('Content-Type', 'application/rss+xml');
    }
}
