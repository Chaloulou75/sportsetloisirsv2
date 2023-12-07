<?php

namespace App\Http\Controllers\Departement;

use App\Models\City;
use Inertia\Inertia;
use App\Models\Famille;
use App\Models\Structure;
use App\Models\Departement;
use Illuminate\Http\Request;
use App\Models\Structuretype;
use App\Models\ListDiscipline;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\LienDisciplineCategorie;
use App\Models\LienDisciplineCategorieCritere;

class DepartementStructureController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(Departement $departement, $structure)
    {
        $familles = Famille::withProducts()->get();
        $listDisciplines = ListDiscipline::withProducts()->get();
        $allCities = City::withProducts()->get();

        $structure = Structure::withRelations()
                            ->where('slug', $structure)
                            ->first();

        $logoUrl = asset($structure->logo);

        $departement = Departement::with(['cities' => function ($query) {
            $query->whereHas('produits');
        }])
                        ->where('slug', $departement->slug)
                        ->select(['id', 'slug', 'numero', 'departement', 'prefixe', 'view_count'])
                        ->first();

        $criteres = LienDisciplineCategorieCritere::withValeurs()
                        ->whereIn('discipline_id', $structure->activites->pluck('discipline_id'))
                        ->whereIn('categorie_id', $structure->activites->pluck('categorie_id'))
                        ->where('visible_front', true)
                        ->get();

        $structure->timestamps = false;
        $structure->increment('view_count');

        return Inertia::render('Structures/Show', [
            'structure' => $structure,
            'familles' => $familles,
            'allCities' => $allCities,
            'listDisciplines' => $listDisciplines,
            'criteres' => $criteres,
            'logoUrl' => $logoUrl,
            'can' => [
                'update' => optional(Auth::user())->can('update', $structure),
                'delete' => optional(Auth::user())->can('delete', $structure),
            ],
            'departement' => $departement,
        ]);
    }

}
