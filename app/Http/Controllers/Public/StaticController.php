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
}
