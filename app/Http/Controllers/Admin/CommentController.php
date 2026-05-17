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
            'commentable_type' => 'required|string',
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
