<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Departement\DepartementController;
use App\Http\Controllers\Departement\DepartementActiviteController;
use App\Http\Controllers\Departement\DepartementStructureController;
use App\Http\Controllers\Departement\DepartementDisciplineController;
use App\Http\Controllers\Departement\DepartementDisciplineActiviteController;
use App\Http\Controllers\Departement\DepartementDisciplineCategorieController;
use App\Http\Controllers\Departement\DepartementDisciplineStructureController;
use App\Http\Controllers\Departement\DepartementDisciplineStructuretypeController;
use App\Http\Controllers\Departement\DepartementDisciplineCategorieActiviteController;
use App\Http\Controllers\Departement\DepartementDisciplineCategorieStructureController;
use App\Http\Controllers\Departement\DepartementDisciplineStructuretypeActiviteController;
use App\Http\Controllers\Departement\DepartementDisciplineStructuretypeStructureController;

Route::get('/departements', [DepartementController::class, 'index'])->name(
    'departements.index'
);

Route::get('/dept-{departement}', [DepartementController::class, 'show'])->name('departements.show');

Route::get('/dept-{departement}/str-{structure}', [DepartementStructureController::class, 'show'])->name('departements.structures.show');

Route::match(['get', 'post'], '/dept-{departement}/activites-{activite}-{slug}/{produit?}', [DepartementActiviteController::class, 'show'])->name('departements.activites.show');

Route::get('/dept-{departement}/dis-{discipline}', [DepartementDisciplineController::class, 'show'])->name('departements.disciplines.show');

Route::get('/dept-{departement}/dis-{discipline}/str-{structure}', [DepartementDisciplineStructureController::class, 'show'])->name('departements.disciplines.structures.show');

Route::match(['get', 'post'], '/dept-{departement}/dis-{discipline}/activites-{activite}-{slug}/{produit?}', [DepartementDisciplineActiviteController::class, 'show'])->name('departements.disciplines.activites.show');

Route::match(['get', 'post'], '/dept-{departement}/dis-{discipline}/cat-{category:slug}', [DepartementDisciplineCategorieController::class, 'show'])->name('departements.disciplines.categories.show');

Route::get('/dept-{departement}/dis-{discipline}/cat-{category:slug}/str-{structure}', [DepartementDisciplineCategorieStructureController::class, 'show'])->name('departements.disciplines.categories.structures.show');

Route::match(['get', 'post'], '/dept-{departement}/dis-{discipline}/cat-{category:slug}/activites-{activite}-{slug}/{produit?}', [DepartementDisciplineCategorieActiviteController::class, 'show'])->name('departements.disciplines.categories.activites.show');

Route::get('/dept-{departement}/dis-{discipline}/type-{structuretype}', [DepartementDisciplineStructuretypeController::class, 'show'])->name('departements.disciplines.structuretypes.show');

Route::get('/dept-{departement}/dis-{discipline}/type-{structuretype}/str-{structure}', [DepartementDisciplineStructuretypeStructureController::class, 'show'])->name('departements.disciplines.structuretypes.structures.show');

Route::match(['get', 'post'], '/dept-{departement}/dis-{discipline}/type-{structuretype}/activites-{activite}-{slug}/{produit?}', [DepartementDisciplineStructuretypeActiviteController::class, 'show'])->name('departements.disciplines.structuretypes.activites.show');

Route::get('/localite-2/index.{extension?}', function ($extension = null) {
    return redirect('/departements/', 301);
});

Route::get('/{departementWithPlus}-{id}-2.{extension?}', function ($departementWithPlus, $id, $extension = null) {
    $departement = str_replace('+', '-', strtolower($departementWithPlus));
    return redirect('/departements/' . $id, 301);
});
