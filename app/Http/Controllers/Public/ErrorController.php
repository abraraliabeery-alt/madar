<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ErrorController extends Controller
{
    /**
     * 404 Not Found page
     */
    public function notFound()
    {
        return response()->view('errors.404', [], 404);
    }

    /**
     * 500 Server Error page
     */
    public function serverError()
    {
        return response()->view('errors.500', [], 500);
    }

    /**
     * Maintenance page
     */
    public function maintenance()
    {
        return response()->view('errors.maintenance', [], 503);
    }
}
