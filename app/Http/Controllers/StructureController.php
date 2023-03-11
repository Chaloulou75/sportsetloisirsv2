<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Structure;
use Illuminate\Http\Request;
use App\Models\Structuretype;
use Illuminate\Validation\Rule;

class StructureController extends Controller
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
        $structuresType = Structuretype::select(['id', 'name'])->get();

        return Inertia::render('Structure/Create', [
            'structuresType' => $structuresType
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        dd($request->all());

        $regex = '/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/';

        $validated= request()->validate([
            'firstname' => ['required'],
            'lastname' => ['required'],
            'structuretype_id' => ['required', Rule::exists('structurestype', 'id')],
            'category_id' => ['required', Rule::exists('categories', 'id')],
            'email' => ['required', 'max:50', 'email'],
            'website' => ['nullable', 'regex:'.$regex],
            'phone' => ['required', 'digits:10'],
            'facebook' => ['nullable'],
            'instagram' => ['nullable'],
            'youtube' => ['nullable'],
            'address' => ['nullable'],
            'city' => ['nullable'],
            'zip_code' => ['nullable'],
            'country' => ['nullable'],
            'address_lat' => ['nullable'],
            'address_lng' => ['nullable'],
            'description' => ['required', 'min:8'],
        ]);

        $structure = Structure::create($validated);

        return $structure->toJson();
    }

    /**
     * Display the specified resource.
     */
    public function show(Structure $structure)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Structure $structure)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Structure $structure)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Structure $structure)
    {
        //
    }
}
