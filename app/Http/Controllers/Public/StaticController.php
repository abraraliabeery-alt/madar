<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StaticController extends Controller
{
    /**
     * How it works page
     */
    public function howItWorks()
    {
        return view('public.static.how-it-works');
    }

    /**
     * Pricing page
     */
    public function pricing()
    {
        return view('public.static.pricing');
    }

    /**
     * Testimonials page
     */
    public function testimonials()
    {
        return view('public.static.testimonials');
    }

    /**
     * Blog listing page
     */
    public function blog()
    {
        return view('public.static.blog');
    }

    /**
     * Blog post page
     */
    public function blogPost($post)
    {
        return view('public.static.blog-post', compact('post'));
    }

    /**
     * News listing page
     */
    public function news()
    {
        return view('public.static.news');
    }

    /**
     * News article page
     */
    public function newsArticle($article)
    {
        return view('public.static.news-article', compact('article'));
    }

    /**
     * Careers page
     */
    public function careers()
    {
        return view('public.static.careers');
    }

    /**
     * Job details page
     */
    public function jobDetails($job)
    {
        return view('public.static.job-details', compact('job'));
    }

    /**
     * Apply for job
     */
    public function applyForJob(Request $request, $job)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string',
            'cv' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'cover_letter' => 'nullable|string',
        ]);

        // Handle job application logic here

        return redirect()->back()->with('success', 'تم إرسال طلب التوظيف بنجاح');
    }

    /**
     * Terms of service page
     */
    public function terms()
    {
        return view('public.static.terms');
    }

    /**
     * Privacy policy page
     */
    public function privacy()
    {
        return view('public.static.privacy');
    }

    /**
     * Cookie policy page
     */
    public function cookies()
    {
        return view('public.static.cookies');
    }

    /**
     * Advertising policy page
     */
    public function advertising()
    {
        return view('public.static.advertising');
    }

    /**
     * Generic dynamic page handler for any public.* route
     * This method will handle routes like public.cities.index, public.terms, etc.
     */
    public function dynamicPage($slug)
    {
        // Handle special cases for cities
        if ($slug === 'cities') {
            return $this->citiesIndex();
        }

        // Try to find a page with this slug
        $page = \App\Models\Page::where('slug', $slug)->first();
        
        if ($page) {
            // If it's a link type page, redirect to the URL
            if ($page->type === 'link' && $page->url) {
                return redirect($page->url);
            }
            
            // Otherwise, show the page content
            return view('public.static.page', compact('page'));
        }

        // If no page found, try to handle as a model index
        if ($slug === 'cities') {
            return $this->citiesIndex();
        }

        // If nothing found, return 404
        abort(404);
    }

    /**
     * Cities index page
     */
    public function citiesIndex()
    {
        $cities = \App\Models\City::active()->featured()->ordered()->take(6)->get();
        return view('public.cities.index', compact('cities'));
    }
}
