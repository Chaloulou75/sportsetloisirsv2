<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Categorie;
use Illuminate\Http\Request;

use App\Http\Resources\CategorieResource;
use Illuminate\Database\Eloquent\Builder;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $categories = Categorie::select(['id', 'nom', 'derivés'])->get();

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
    public function store(Request $request)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Categorie $categorie)
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $request->validate([
            'nom' => ['string'],
        ]);
        $categorie->update(['nom' => $request->nom]);

        return to_route('admin.categories.index')->with('success', 'Catégorie mis à jour');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Categorie $categorie)
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
