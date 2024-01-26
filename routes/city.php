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

Route::get('/{city}', [CityController::class, 'show'], [
    'parameters' => [
        'villes' => 'city'
    ]
])->name('villes.show');

Route::get('/{city}/str-{structure}', [CityStructureController::class, 'show'], [
    'parameters' => [
        'villes' => 'city',
    ]
])->name('villes.structures.show');

Route::get('/{city}/activites-{activite}', [CityActiviteController::class, 'show'], [
    'parameters' => [
        'villes' => 'city',
    ]
])->name('villes.activites.show');

Route::get('/{city}/dis-{discipline}', [CityDisciplineController::class, 'show'], [
    'parameters' => [
        'villes' => 'city'
    ]
])->name('villes.disciplines.show');

Route::get('/{city}/dis-{discipline}/str-{structure}', [CityDisciplineStructureController::class, 'show'], [
    'parameters' => [
        'villes' => 'city'
    ]
])->name('villes.disciplines.structures.show');

Route::get('/{city}/dis-{discipline}/activites-{activite}', [CityDisciplineActiviteController::class, 'show'], [
    'parameters' => [
        'villes' => 'city'
    ]
])->name('villes.disciplines.activites.show');

Route::get('/{city}/dis-{discipline}/cat-{category:slug}', [CityDisciplineCategorieController::class, 'show'])->name('villes.disciplines.categories.show');
// /villes/disciplines/categories/

Route::get('/{city}/dis-{discipline}/cat-{category:slug}/str-{structure}', [CityDisciplineCategorieStructureController::class, 'show'])->name('villes.disciplines.categories.structures.show');

Route::get('/{city}/dis-{discipline}/cat-{category:slug}/activites-{activite}', [CityDisciplineCategorieActiviteController::class, 'show'])->name('villes.disciplines.categories.activites.show');

Route::get('/{city}/dis-{discipline}/typ-{structuretype}', [CityDisciplineStructuretypeController::class, 'show'])->name('villes.disciplines.structuretypes.show');
// /villes/disciplines/structuretypes/

Route::get('/{city}/{discipline}/typ-{structuretype}/str-{structure}', [CityDisciplineStructuretypeStructureController::class, 'show'])->name('villes.disciplines.structuretypes.structures.show');
// /villes/disciplines/structuretypes/structures/

Route::get('/{city}/dis-{discipline}/typ-{structuretype}/activites-{activite}', [CityDisciplineStructuretypeActiviteController::class, 'show'])->name('villes.disciplines.structuretypes.activites.show');
// /villes/disciplines/structuretypes/activites/

Route::get('/localite-1/index.{extension?}', function ($extension = null) {
    return redirect('/villes/', 301);
});
Route::get('/{villeWithPlus}-{id}-1.{extension?}', function ($villeWithPlus, $id, $extension = null) {
    $ville = str_replace('+', '-', strtolower($villeWithPlus));
    return redirect('/villes/' . $id, 301);
});
