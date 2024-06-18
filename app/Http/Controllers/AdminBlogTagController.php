<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Categorie;
use Illuminate\Http\Request;
use App\Http\Resources\TagResource;
use Illuminate\Http\RedirectResponse;

class AdminBlogTagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $tags = Tag::with('posts')
        ->withCount('posts')
        ->orderByDesc('posts_count')
        ->orderBy('name')
        ->paginate(150);

        return Inertia::render('Admin/Blog/Tags/Index', [
            'user_can' => [
                'view_admin' => $user->can('viewAdmin', User::class),
            ],
            'tags' => fn () => TagResource::collection($tags),
        ]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            "name" => 'nullable|string|min:3|max:255|unique:tags',
            'categories' => 'boolean',
        ]);

        if($request->name) {
            Tag::create(["name" => $request->name]);
        }

        if($request->categories === true) {
            $categories = Categorie::select('id', 'nom')->get();
            foreach($categories as $categorie) {
                Tag::firstOrCreate(["name" => $categorie->nom]);
            }
        }

        return to_route('admin.blog.tags.index')->with('success', 'Etiquette ajouté.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag): RedirectResponse
    {
        $tag->delete();
        return to_route('admin.blog.tags.index')->with('success', 'Tag supprimé.');
    }
}
