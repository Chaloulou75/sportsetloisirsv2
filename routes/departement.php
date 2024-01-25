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

Route::get('/dept-{departement:slug}', [DepartementController::class, 'show'])->name('departements.show');

Route::get('/dept-{departement:slug}/str-{structure:slug}', [DepartementStructureController::class, 'show'])->name('departements.structures.show');

Route::get('/dept-{departement:slug}/activites-{activite:id}', [DepartementActiviteController::class, 'show'])->name('departements.activites.show');

Route::get('/dept-{departement:slug}/dis-{discipline}', [DepartementDisciplineController::class, 'show'])->name('departements.disciplines.show');

Route::get('/dept-{departement:slug}/dis-{discipline}/str-{structure:slug}', [DepartementDisciplineStructureController::class, 'show'])->name('departements.disciplines.structures.show');

Route::get('/dept-{departement:slug}/dis-{discipline}/activite-{activite:id}', [DepartementDisciplineActiviteController::class, 'show'])->name('departements.disciplines.activites.show');

Route::get('/dept-{departement:slug}/dis-{discipline}/cat-{category:slug}', [DepartementDisciplineCategorieController::class, 'show'])->name('departements.disciplines.categories.show');
// /departements/disciplines/categories/

Route::get('/dept-{departement:slug}/dis-{discipline}/cat-{category:slug}/str-{structure:slug}', [DepartementDisciplineCategorieStructureController::class, 'show'])->name('departements.disciplines.categories.structures.show');
// /departements/disciplines/categories/structure

Route::get('/dept-{departement:slug}/dis-{discipline}/cat-{category:slug}/activite-{activite:slug}', [DepartementDisciplineCategorieActiviteController::class, 'show'])->name('departements.disciplines.categories.activites.show');
// /departements/disciplines/categories/activite

Route::get('/dept-{departement:slug}/dis-{discipline}/type-{structuretype}', [DepartementDisciplineStructuretypeController::class, 'show'])->name('departements.disciplines.structuretypes.show');
// /departements/disciplines/structuretypes/

Route::get('/dept-{departement:slug}/dis-{discipline}/type-{structuretype}/str-{structure:slug}', [DepartementDisciplineStructuretypeStructureController::class, 'show'])->name('departements.disciplines.structuretypes.structures.show');

Route::get('/dept-{departement:slug}/dis-{discipline}/type-{structuretype}/activite-{activite:id}', [DepartementDisciplineStructuretypeActiviteController::class, 'show'])->name('departements.disciplines.structuretypes.activites.show');

Route::get('/localite-2/index.{extension?}', function ($extension = null) {
    return redirect('/departements/', 301);
});

Route::get('/{departementWithPlus}-{id}-2.{extension?}', function ($departementWithPlus, $id, $extension = null) {
    $departement = str_replace('+', '-', strtolower($departementWithPlus));
    return redirect('/departements/' . $id, 301);
});
