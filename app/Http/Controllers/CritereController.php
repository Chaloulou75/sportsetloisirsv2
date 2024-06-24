<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Critere;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Resources\CritereResource;
use App\Http\Resources\TypeChampResource;
use App\Models\TypeChamp;

class CritereController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {

        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $criteres = Critere::select(['id', 'nom'])->get();

        $typeChamps = TypeChamp::select(['id', 'type', 'criterable'])->get();

        return Inertia::render('Admin/Criteres/Index', [
            'user_can' => [
                'view_admin' => $user->can('viewAdmin', User::class),
            ],
            'criteres' => fn () => CritereResource::collection($criteres),
            'type_champs' => fn () => TypeChampResource::collection($typeChamps),
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
        Critere::create([
            'nom' => $request->nom
        ]);

        return to_route('admin.criteres.index')->with('success', 'Critere créé');

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Critere $critere): RedirectResponse
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $request->validate([
            'nom' => ['string'],
        ]);
        $critere->update(['nom' => $request->nom]);

        return to_route('admin.criteres.index')->with('success', 'Critère mis à jour');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Critere $critere): RedirectResponse
    {
        $critere->delete();
        return to_route('admin.criteres.index')->with('success', 'Critère supprimé');
    }
}
