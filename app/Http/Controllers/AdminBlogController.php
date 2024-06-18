<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Post;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Http\Resources\PostResource;
use App\Http\Resources\TagResource;

class AdminBlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $posts = Post::with('author', 'comments', 'tags')
                        ->withCount('comments')
                        ->latest()
                        ->filter(
                            request(['search', 'author'])
                        )
                        ->paginate(18)
                        ->withQueryString();
        $tags = Tag::all();

        return Inertia::render('Admin/Blog/Index', [
            'user_can' => [
                'view_admin' => $user->can('viewAdmin', User::class),
            ],
            'posts' => fn () => PostResource::collection($posts),
            'tags' => fn () => TagResource::collection($tags),
            'filters' => request()->all(['search', 'author']),
        ]);

    }
}
