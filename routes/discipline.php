<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Discipline\DisciplineController;
use App\Http\Controllers\Discipline\CategoryDisciplineController;
use App\Http\Controllers\Discipline\StructureTypeDisciplineController;
use App\Http\Controllers\Discipline\DisciplineActiviteController;
use App\Http\Controllers\Discipline\DisciplineStructureController;
use App\Http\Controllers\Discipline\DisciplineCategorieActiviteController;
use App\Http\Controllers\Discipline\DisciplineCategorieStructureController;
use App\Http\Controllers\Discipline\DisciplineStructuretypeActiviteController;
use App\Http\Controllers\Discipline\DisciplineStructuretypeStructureController;

Route::resource('disciplines', DisciplineController::class)->only([
    'index'
]);

Route::get('/dis-{discipline:slug}', [DisciplineController::class, 'show'])->name('disciplines.show');

Route::get('/dis-{discipline:slug}/str-{structure:slug}', [DisciplineStructureController::class, 'show'])->name('disciplines.structures.show');

Route::get('/dis-{discipline:slug}/activite-{activite:id}', [DisciplineActiviteController::class, 'show'])->name('disciplines.activites.show');

Route::get('/dis-{discipline:slug}/cat-{category:slug}', [CategoryDisciplineController::class, 'show'])->name('disciplines.categories.show');

Route::get('/dis-{discipline:slug}/cat-{category:slug}/str-{structure:slug}', [DisciplineCategorieStructureController::class, 'show'])->name('disciplines.categories.structures.show');

Route::get('/dis-{discipline:slug}/cat-{category:slug}/activite-{activite:id}', [DisciplineCategorieActiviteController::class, 'show'])->name('disciplines.categories.activites.show');

Route::get('/dis-{discipline:slug}/typ-{structuretype:id}', [StructureTypeDisciplineController::class, 'show'])->name('disciplines.structuretypes.show');

Route::get('/dis-{discipline:slug}/typ-{structuretype:id}/str-{structure:slug}', [DisciplineStructuretypeStructureController::class, 'show'])->name('disciplines.structuretypes.structures.show');

Route::get('/dis-{discipline:slug}/typ-{structuretype:id}/activite-{activite:id}', [DisciplineStructuretypeActiviteController::class, 'show'])->name('disciplines.structuretypes.activites.show');
