<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Structure;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
    public function store(Request $request, Structure $structure)
    {
        request()->validate([
            'email' => ['required', 'max:50', 'email:filter', 'exists:users,email'],
            'contact' => ['required', 'string'],
            'phone' => ['required', 'phone:FR'],
            'niveau' => ['required'],
            'activites' => ['nullable'],
        ]);

        $structure = Structure::where('id', $structure->id)->firstOrFail();

        $user = User::where('email', $request->email)->firstOrFail();

        $structureUser = $user->structures()->attach($structure, [
            'contact' => $request->contact,
            'phone' => $request->phone,
            'niveau' => $request->niveau,
            'phone' => $request->phone,
        ]);

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
    public function destroy(Structure $structure, string $partenaire)
    {
        $structure->partenaires()->detach($partenaire);

        return to_route('structures.edit', $structure->slug)->with('success', 'Partenaire supprmé à votre structure.');

    }
}
