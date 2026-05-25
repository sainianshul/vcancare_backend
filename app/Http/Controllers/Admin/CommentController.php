<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'commentable_type' => 'required|string|in:' . implode(',', [
                Comment::TYPE_USER, Comment::TYPE_NURSE, Comment::TYPE_PATIENT, 
                Comment::TYPE_CARE_TYPE, Comment::TYPE_LOGIN_HISTORY, Comment::TYPE_LOGS, Comment::TYPE_REQUEST_BID, Comment::TYPE_CARE_REQUEST
            ]),
            'commentable_id' => 'required|integer',
            'body' => 'required|string|max:2000',
        ]);

        $validated['created_by'] = auth()->id();

        Comment::create($validated);

        return back()->with('success', 'Comment added successfully.');
    }

    public function destroy(Comment $comment)
    {
        // Optional: authorize user is the creator or an admin
        // if ($comment->created_by !== auth()->id()) { abort(403); }

        $comment->delete();

        return back()->with('success', 'Comment deleted successfully.');
    }
}
