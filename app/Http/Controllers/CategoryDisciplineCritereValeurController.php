<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\LienDisciplineCategorieCritereValeur;

class CategoryDisciplineCritereValeurController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LienDisciplineCategorieCritereValeur $lienDisCatCritValeur)
    {
        $request->validate([
            'valeur' => ['required', 'string', 'max:255'],
            'id' => ['required', Rule::exists('liens_disciplines_categories_criteres_valeurs', 'id')],
        ]);

        $lienDisCatCritValeur = LienDisciplineCategorieCritereValeur::where('id', $lienDisCatCritValeur->id)->firstOrFail();

        $lienDisCatCritValeur->update(['valeur' => $request->valeur]);
        return redirect()->back()->with('success', 'Valeur du critère modifiée');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
