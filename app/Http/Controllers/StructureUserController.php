<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Structure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class StructureUserController extends Controller
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
    public function store(Request $request, Structure $structure): RedirectResponse
    {
        request()->validate([
            'email' => ['required', 'max:50', 'email:filter', 'exists:users,email'],
            'contact' => ['required', 'string'],
            'phone' => ['required', 'phone:FR'],
            'niveau' => ['required'],
            'activites' => ['nullable'],
        ]);

        $structure = Structure::findOrFail($structure->id);

        $user = User::where('email', $request->email)->firstOrFail();

        $structureUser = $user->structures()->attach($structure, [
            'contact' => $request->contact,
            'niveau' => $request->niveau,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        //foreach($request->activites etc....)

        //envoie email?

        return to_route('structures.edit', $structure->slug)->with('success', 'Partenaire ajouté à votre structure.');

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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Structure $structure, string $partenaire): RedirectResponse
    {
        //verifier que le partenaire n'est pas le dernier à être Super Admin
        $partenaireASupprimer = $structure->partenaires()->wherePivot('user_id', $partenaire)->first();

        $superAdminForStructureCount = $structure->partenaires()->wherePivot('niveau', 1)->count();

        if(($partenaireASupprimer->pivot->niveau === 1) && ($superAdminForStructureCount < 2)) {
            return to_route('structures.edit', $structure->slug)->with('error', 'Il doit y avoir au minimum un Super Administrateur pour votre structure.');
        }

        $structure->partenaires()->detach($partenaire);

        return to_route('structures.edit', $structure->slug)->with('success', 'Partenaire supprimé à votre structure.');

    }
}
