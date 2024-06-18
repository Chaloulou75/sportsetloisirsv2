<?php

namespace App\Http\Controllers;

use App\Http\Resources\StructureResource;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Structure;
use Illuminate\Http\Request;

class AdminStructureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $structures = Structure::select(['id', 'name', 'slug'])->get();

        return Inertia::render('Admin/Structures/Index', [
            'user_can' => [
                'view_admin' => $user->can('viewAdmin', User::class),
            ],
            'structures' => fn () => StructureResource::collection($structures),
        ]);

    }
}
