<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\City\CityController;
use App\Http\Controllers\City\CityActiviteController;
use App\Http\Controllers\City\CityStructureController;
use App\Http\Controllers\City\CityDisciplineController;
use App\Http\Controllers\City\CityDisciplineActiviteController;
use App\Http\Controllers\City\CityDisciplineCategorieController;
use App\Http\Controllers\City\CityDisciplineStructureController;
use App\Http\Controllers\City\CityDisciplineStructuretypeController;
use App\Http\Controllers\City\CityDisciplineCategorieActiviteController;
use App\Http\Controllers\City\CityDisciplineCategorieStructureController;
use App\Http\Controllers\City\CityDisciplineStructuretypeActiviteController;
use App\Http\Controllers\City\CityDisciplineStructuretypeStructureController;

Route::resource('villes', CityController::class, [
    'parameters' => [
        'villes' => 'city'
    ]
])->only([
    'index'
]);

Route::get('/{city:slug}', [CityController::class, 'show'], [
    'parameters' => [
        'villes' => 'city'
    ]
])->name('villes.show');

Route::get('/{city:slug}/str-{structure:slug}', [CityStructureController::class, 'show'], [
    'parameters' => [
        'villes' => 'city',
    ]
])->name('villes.structures.show');

Route::get('/{city:slug}/activites-{activite:id}', [CityActiviteController::class, 'show'], [
    'parameters' => [
        'villes' => 'city',
    ]
])->name('villes.activites.show');

Route::get('/{city:slug}/dis-{discipline:slug}', [CityDisciplineController::class, 'show'], [
    'parameters' => [
        'villes' => 'city'
    ]
])->name('villes.disciplines.show');

Route::get('/{city:slug}/dis-{discipline:slug}/str-{structure:slug}', [CityDisciplineStructureController::class, 'show'], [
    'parameters' => [
        'villes' => 'city'
    ]
])->name('villes.disciplines.structures.show');

Route::get('/{city:slug}/dis-{discipline:slug}/activites-{activite:id}', [CityDisciplineActiviteController::class, 'show'], [
    'parameters' => [
        'villes' => 'city'
    ]
])->name('villes.disciplines.activites.show');

Route::get('/{city:slug}/dis-{discipline:slug}/cat-{category:slug}', [CityDisciplineCategorieController::class, 'show'])->name('villes.disciplines.categories.show');
// /villes/disciplines/categories/

Route::get('/{city:slug}/dis-{discipline:slug}/cat-{category:slug}/str-{structure:slug}', [CityDisciplineCategorieStructureController::class, 'show'])->name('villes.disciplines.categories.structures.show');

Route::get('/{city:slug}/dis-{discipline:slug}/cat-{category:slug}/activites-{activite:id}', [CityDisciplineCategorieActiviteController::class, 'show'])->name('villes.disciplines.categories.activites.show');

Route::get('/{city:slug}/dis-{discipline:slug}/typ-{structuretype:id}', [CityDisciplineStructuretypeController::class, 'show'])->name('villes.disciplines.structuretypes.show');
// /villes/disciplines/structuretypes/

Route::get('/{city:slug}/{discipline:slug}/typ-{structuretype:id}/str-{structure:slug}', [CityDisciplineStructuretypeStructureController::class, 'show'])->name('villes.disciplines.structuretypes.structures.show');
// /villes/disciplines/structuretypes/structures/

Route::get('/{city:slug}/dis-{discipline:slug}/typ-{structuretype:id}/activites-{activite:id}', [CityDisciplineStructuretypeActiviteController::class, 'show'])->name('villes.disciplines.structuretypes.activites.show');
// /villes/disciplines/structuretypes/activites/
