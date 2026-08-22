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
    public function index(Request $request)
    {
        $user = Auth::user();

        // Direct panel switch via query string
        $panel = $request->get('panel');
        if ($panel === 'admin' && $user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }
        if ($panel === 'facility' && $user->hasRole('facility')) {
            return redirect()->route('facility.dashboard');
        }
        if ($panel === 'client' && $user->hasRole('client')) {
            return redirect()->route('client.dashboard');
        }

        // Count assigned roles
        $roleCount = 0;
        if ($user->hasRole('admin')) { $roleCount++; }
        if ($user->hasRole('facility')) { $roleCount++; }
        if ($user->hasRole('client')) { $roleCount++; }

        // If the user has multiple roles, show the selector view
        if ($roleCount > 1) {
            return view('home');
        }

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
