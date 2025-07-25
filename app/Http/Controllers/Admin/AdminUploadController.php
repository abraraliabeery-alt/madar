<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminUploadController extends Controller
{
    /**
     * رفع صورة لمحرر Summernote
     */
    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $path = $request->file('image')->store('editor', 'public');

        return Storage::url($path);
    }
}
