<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Post;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Famille;
use Illuminate\Http\Request;
use App\Models\ListDiscipline;
use Illuminate\Http\RedirectResponse;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $familles = Famille::withProducts()->get();
        $listDisciplines = ListDiscipline::withProducts()->get();
        $allCities = City::withProducts()->get();
        $posts = Post::with('author', 'comments', 'tags')
                ->withCount('comments')
                ->latest()
                ->filter(
                    request(['search', 'author'])
                )
                ->paginate(18)
                ->withQueryString();

        return Inertia::render('Posts/Index', [
            'familles' => $familles,
            'listDisciplines' => $listDisciplines,
            'allCities' => $allCities,
            'posts' => $posts,
            'filters' => request()->all(['search', 'author']),
        ]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post): Response
    {
        $familles = Famille::withProducts()->get();
        $listDisciplines = ListDiscipline::withProducts()->get();
        $allCities = City::withProducts()->get();

        $post = Post::with('author', 'comments', 'comments.author', 'tags')->findOrFail($post->id);

        $post->increment('views_count');

        return Inertia::render('Posts/Show', [
            'familles' => $familles,
            'listDisciplines' => $listDisciplines,
            'allCities' => $allCities,
            'post' => $post,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post): RedirectResponse
    {
        $post->delete();
        return to_route('posts.index')->with('success', 'Article supprimé.');
    }
}
