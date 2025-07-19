<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Product;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiCommentController extends Controller
{
    /**
     * Display a listing of user comments
     */
    public function index()
    {
        $user = Auth::user();
        $comments = Comment::where('user_id', $user->id)->with(['commentable'])->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $comments
        ]);
    }

    /**
     * Store product comment
     */
    public function storeProductComment(Request $request, Product $product)
    {
        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'comment' => 'required|string|max:1000',
        ]);

        $user = Auth::user();

        $comment = $product->comments()->create([
            'user_id' => $user->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم إضافة التعليق بنجاح',
            'data' => $comment
        ]);
    }

    /**
     * Store facility comment
     */
    public function storeFacilityComment(Request $request, Facility $facility)
    {
        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'comment' => 'required|string|max:1000',
        ]);

        $user = Auth::user();

        $comment = $facility->comments()->create([
            'user_id' => $user->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم إضافة التعليق بنجاح',
            'data' => $comment
        ]);
    }

    /**
     * Update comment
     */
    public function update(Request $request, Comment $comment)
    {
        $request->validate([
            'rating' => 'sometimes|integer|between:1,5',
            'comment' => 'sometimes|string|max:1000',
        ]);

        $comment->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث التعليق بنجاح',
            'data' => $comment
        ]);
    }

    /**
     * Remove comment
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف التعليق بنجاح'
        ]);
    }
}
