<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Critere;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

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

        return Inertia::render('Admin/Criteres/Index', [
            'user_can' => [
                'view_admin' => $user->can('viewAdmin', User::class),
            ],
            'criteres' => fn () => $criteres,
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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
