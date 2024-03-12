<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ListeTarifType;
use Illuminate\Http\RedirectResponse;

class AdminTarifTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $tarifs = ListeTarifType::with([
                'categories',
                'categories.categorie',
                'categories.categorie.discipline' => function ($query) {
                    $query->orderBy('name');
                },
                'categories.tarif_attributs',
                'categories.tarif_attributs.valeurs',
                'categories.tarif_attributs.sous_attributs',
                'categories.tarif_attributs.sous_attributs.valeurs',
            ])
            ->select(['id', 'type', 'slug'])
            ->get();

        return Inertia::render('Admin/Tarifs/Index', [
            'user_can' => [
                'view_admin' => $user->can('viewAdmin', User::class),
            ],
            'tarifs' => fn () => $tarifs,
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
            'type' => ['required', 'string', 'min:3'],
        ]);
        ListeTarifType::create([
            'type' => $request->type,
            'slug' => Str::slug($request->type)
        ]);

        return to_route('admin.tarifs.index')->with('success', 'Type de tarif créé');

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ListeTarifType $tarif): RedirectResponse
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $request->validate([
            'type' => ['required', 'string', 'min:3'],
        ]);
        $tarif->update([
            'type' => $request->type,
            'slug' => Str::slug($request->type)
        ]);

        return to_route('admin.tarifs.index')->with('success', 'Type de tarif mis à jour');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ListeTarifType $tarif): RedirectResponse
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $tarif->delete();
        return to_route('admin.tarifs.index')->with('success', 'Type de tarif supprimé');
    }
}
