<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Resources\CategorieResource;
use Illuminate\Database\Eloquent\Builder;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $categories = Categorie::with(['disciplines' => function ($query) {
            $query->orderBy('name');
        }])
                ->select(['id', 'nom'])
                ->get();

        return Inertia::render('Admin/Categories/Index', [
            'user_can' => [
                'view_admin' => $user->can('viewAdmin', User::class),
            ],
            'categories' => $categories,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $request->validate([
            'nom' => ['required', 'string', 'min:3'],
        ]);
        Categorie::create([
            'nom' => $request->nom
        ]);

        return to_route('admin.categories.index')->with('success', 'Catégorie créée');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Categorie $categorie): RedirectResponse
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $request->validate([
            'nom' => ['required', 'string', 'min:3'],
        ]);
        $categorie->update(['nom' => $request->nom]);

        return to_route('admin.categories.index')->with('success', 'Catégorie mis à jour');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Categorie $categorie): RedirectResponse
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $categorie->delete();
        return to_route('admin.categories.index')->with('success', 'Catégorie supprimé');
    }


    public function loadCategories()
    {
        $discipline_id = request('discipline_id');

        $categories = Categorie::whereHas('disciplines', function ($query) use ($discipline_id) {
            $query->where('discipline_id', $discipline_id);
        })->get();

        return CategorieResource::collection($categories);
    }
}
