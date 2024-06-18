<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;

class PostLikeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Post $post): RedirectResponse
    {
        if (auth()->check()) {
            $user = auth()->user();

            if (!$user->likedPosts->contains($post)) {
                $post->increment('likes');
                $user->likedPosts()->attach($post);

                return to_route('posts.show', ['post' => $post])->with('success', 'Article aimé.');
            } else {
                return to_route('posts.show', ['post' => $post])->with('error', 'Vous avez déjà aimé cet article.');
            }
        } else {
            return to_route('posts.show', ['post' => $post])->with('error', 'Vous devez vous identifier pour aimer un article.');
        }
    }
}
