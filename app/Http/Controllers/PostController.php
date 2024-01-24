<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\City;
use App\Models\Post;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Famille;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ListDiscipline;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ListDiscipline $discipline = null): Response
    {
        $familles = Cache::remember('familles', 600, function () {
            return Famille::withProducts()->get();
        });
        $allCities = Cache::remember('allCities', 600, function () {
            return City::withProducts()->get();
        });
        $listDisciplines = Cache::remember('listDisciplines', 600, function () {
            return ListDiscipline::withProducts()->get();
        });

        $postsQuery = Post::with('author', 'comments', 'tags', 'disciplines')
                ->withCount('comments')
                ->latest()
                ->filter(
                    request(['search', 'author'])
                );

        if ($discipline) {
            $postsQuery->whereHas('disciplines', function ($query) use ($discipline) {
                $query->where('discipline_post.discipline_id', $discipline->id);
            });
        }

        $posts = $postsQuery->paginate(18)->withQueryString();

        return Inertia::render('Posts/Index', [
            'familles' => $familles,
            'listDisciplines' => $listDisciplines,
            'allCities' => $allCities,
            'posts' => $posts,
            'discipline' => $discipline ?? null,
            'filters' => request()->all(['search', 'author']),
        ]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        $familles = Cache::remember('familles', 600, function () {
            return Famille::withProducts()->get();
        });
        $allCities = Cache::remember('allCities', 600, function () {
            return City::withProducts()->get();
        });
        $listDisciplines = Cache::remember('listDisciplines', 600, function () {
            return ListDiscipline::withProducts()->get();
        });

        $tags = Tag::select('id', 'name')->get();
        $disciplines = ListDiscipline::select('id', 'name')->get();

        return Inertia::render('Posts/Create', [
            'familles' => $familles,
            'listDisciplines' => $listDisciplines,
            'allCities' => $allCities,
            'disciplines' => $disciplines,
            'tags' => $tags,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|min:3|max:255',
            'thumbnail' => 'nullable|image',
            'excerpt' => 'required|string|min:3',
            'tags' => 'nullable|array',
            'disciplines' => 'nullable|array',
            'body' => 'required',
        ]);

        $user = auth()->user();
        $title = $request->title;
        $slug = Str::slug($title, '-');
        $body = $request->body;

        $post = Post::create([
            'user_id' => $user->id,
            'title' => $title,
            'slug' => $slug,
            'excerpt' => $request->excerpt,
            'body' => $body,
        ]);

        if($request->hasFile('thumbnail')) {
            $path = request()->file('thumbnail')->store('blog/' . $post->id . '/thumbnails', 'public');
            $post->update(['thumbnail' => $path ]);
        }


        $disciplines = $request->disciplines;
        if($disciplines) {
            foreach($disciplines as $discipline) {
                $post->disciplines()->attach($discipline['id']);
            }
        }

        $tags = $request->tags;
        if($tags) {
            foreach($tags as $tag) {
                $post->tags()->attach($tag['id']);
            }
        }

        return to_route('posts.show', ['post' => $post->slug ])->with('success', 'Article publié.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        $familles = Cache::remember('familles', 600, function () {
            return Famille::withProducts()->get();
        });
        $allCities = Cache::remember('allCities', 600, function () {
            return City::withProducts()->get();
        });
        $listDisciplines = Cache::remember('listDisciplines', 600, function () {
            return ListDiscipline::withProducts()->get();
        });

        $post = Post::with('author', 'author.structures', 'comments', 'comments.author', 'tags', 'disciplines')->findOrFail($post->id);

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
        if($post->thumbnail) {
            Storage::disk('public')->delete($post->thumbnail);
        }
        $post->delete();
        return to_route('posts.index')->with('success', 'Article supprimé.');
    }
}
