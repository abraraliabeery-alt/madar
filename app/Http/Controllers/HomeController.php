<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();

        // Redirect based on user role
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('facility')) {
            return redirect()->route('facility.dashboard');
        }

        // Any other authenticated user goes to the client dashboard
        return redirect()->route('client.dashboard');
    }
}
