<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Category;
use App\Models\Structure;
use Illuminate\Support\Str;
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
        $categories = Category::select(['id', 'name', 'slug'])->get();

        return Inertia::render('Structure/Index', [
            'structures'=> Structure::with([
                    'category:id,name',
                    'user:id,name',
                    'disciplines:id,name',
                    // 'weekdays:id,name',
                    // 'medias',
                ])
                        ->filter(
                            request(['search', 'category'])
                        )
                        ->latest()
                        ->paginate(9)
                        ->through(function ($structure) {
                            return [
                            'id' => $structure->id,
                            'name' => $structure->name,
                            'slug' => $structure->slug,
                            'website' => $structure->website,
                            'email' => $structure->email,
                            'phone' => $structure->phone,
                            'address' => $structure->address,
                            'zip_code' => $structure->zip_code,
                            'city' => $structure->city,
                            'country' => $structure->country,
                            'address_lat' => $structure->address_lat,
                            'address_lng' => $structure->address_lng,
                            'description' => $structure->description,
                            'category' => $structure->category,
                            'disciplines' => $structure->disciplines,
                            // 'weekdays' => $structure->weekdays,
                            'user' => $structure->user,
                            // 'mediasImg' => MediaResource::collection($structure->medias),
                            // 'start_at' => $structure->start_at,
                            // 'end_at' => $structure->end_at,
                            // 'hour_start_at' => $structure->hour_start_at,
                            // 'hour_end_at' => $structure->hour_end_at,
                            // 'logo' => $structure->logo ? Storage::disk('s3')->temporaryUrl('logo/' .$structure->id. '/' .$structure->logo, now()->addMinutes(5)) : null,
                ];
                        })->withQueryString(),
            'filters' => request()->all(['search', 'category']),
            'categories' => $categories,
        ]);
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
        // dd($request->all());

        $regex = '/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/';

        $validated= request()->validate([
            'name' => ['required', 'string', 'max:255'],
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'structuretype_id' => ['required', Rule::exists('structuretypes', 'id')],
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

        $name = $validated['name'];
        $slug = Str::slug($name, '-');
        $validated['user_id'] = auth()->id();
        $validated['slug'] = $slug;

        dd($validated);

        $structure = Structure::create($validated);

        $disciplinesIds = collect($request['disciplines'])->pluck('id');
        $structure->disciplines()->attach($disciplinesIds);

        return $structure->toJson();
    }

    /**
     * Display the specified resource.
     */
    public function show(Structure $structure)
    {
        $structure->timestamps = false;
        $structure->increment('view_count');
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
