<?php

namespace App\Http\Controllers;

use App\Models\City;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Famille;
use Illuminate\Http\Request;
use App\Models\ListDiscipline;
use Illuminate\Support\Facades\Cache;

class FaqController extends Controller
{
    public function index(): Response
    {
        $familles = Cache::remember('familles', 600, function () {
            return Famille::withProducts()->get();
        });
        $allCities = Cache::remember('allCities', 600, function () {
            return City::withProducts()->get();
        });
        $listDisciplines = Cache::remember('listDisciplines', 600, function () {
            return ListDiscipline::withProducts()->get();
        });

        return Inertia::render('Faq/Index', [
            'familles' => fn () => $familles,
            'listDisciplines' => fn () => $listDisciplines,
            'allCities' => fn () => $allCities,
        ]);
    }
}
