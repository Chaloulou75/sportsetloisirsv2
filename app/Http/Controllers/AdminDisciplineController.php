<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ListDiscipline;

class AdminDisciplineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $listDisciplines = ListDiscipline::select(['id', 'slug', 'name'])->get();

        return Inertia::render('Admin/Disciplines/Index', [
            'user_can' => [
                'view_admin' => $user->can('viewAdmin', User::class),
            ],
            'listDisciplines' => fn () => $listDisciplines,
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ListDiscipline $discipline): Response
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        return Inertia::render('Admin/Disciplines/Edit', [
            'user_can' => [
                'view_admin' => $user->can('viewAdmin', User::class),
            ],
            'discipline' => fn () => $discipline,
        ]);

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
    public function destroy(string $id)
    {
        //
    }

    public function duplicate(Request $request)
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $validatedData = $request->validate([
            'discipline_origin' => ['required'],
            'discipline_target' => ['required'],
        ]);

        $dis_origin = ListDiscipline::with('categories')->where('slug', $validatedData['discipline_origin'])->firstOrFail();
        $dis_target = ListDiscipline::with('categories')->where('slug', $validatedData['discipline_target'])->firstOrFail();

        foreach ($dis_origin->categories as $category) {
            $originPivotAttributes = $category->pivot;
            $targetCategoriesWithPivot = $dis_target->categories()->withPivot('categorie_id')->get();

            // Check if the target discipline's categories contain the same categorie_id as the origin category
            $categoryExistsInTarget = $targetCategoriesWithPivot->contains(function ($targetCategory) use ($originPivotAttributes) {
                return $targetCategory->pivot->categorie_id === $originPivotAttributes->categorie_id;
            });


            if (!$categoryExistsInTarget) {

                $pivotAttributes = collect($originPivotAttributes)->except(['id','discipline_id', 'categorie_id'])->toArray();

                $pivotAttributes['slug'] = Str::slug($category->nom) . '-' . $category->id . '_random_texte';

                $dis_target->categories()->attach($category->id, $pivotAttributes);

                $attachedPivotRecord = $dis_target->categories()->withPivot('categorie_id')->wherePivot('categorie_id', $category->id)->first();

                if ($attachedPivotRecord) {
                    $newSlug = Str::slug($category->nom) . '-' . $attachedPivotRecord->pivot->id;
                    $dis_target->categories()->updateExistingPivot($category->id, ['slug' => $newSlug]);
                }
            }
        }

        return to_route('admin.disciplines.index')->with('success', 'Les catégories de la discipline '. $dis_origin->name .' a été dupliquée à '. $dis_target->name .'.');
    }
}
