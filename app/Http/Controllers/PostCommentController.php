<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PostCommentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Post $post): RedirectResponse
    {
        request()->validate([
            'body' => 'required|string|min:3|max:255'
        ]);

        $post->comments()->create([
            'user_id' => request()->user()->id,
            'body' => request('body')
        ]);

        return to_route('posts.show', $post)->with('success', 'Commentaire ajouté.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post, Comment $comment): RedirectResponse
    {
        $comment->delete();
        return to_route('posts.show', $post)->with('success', 'Commentaire supprimé.');

    }
}
