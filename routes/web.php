<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\CityController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FamilleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ActiviteController;
use App\Http\Controllers\StructureController;
use App\Http\Controllers\DisciplineController;
use App\Http\Controllers\DepartementController;
use App\Http\Controllers\CityDisciplineController;
use App\Http\Controllers\StructureTarifController;
use App\Http\Controllers\StructurePlanningController;
use App\Http\Controllers\StructureCategorieController;
use App\Http\Controllers\StructureDisciplineController;
use App\Http\Controllers\StructureActiviteProduitController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [HomeController::class, 'index'])
    ->name('welcome');

Route::get('/mentions', function () {
    return Inertia::render('Mentions/Index');
})->name('mentions.index');

Route::get('/faq', function () {
    return Inertia::render('Faq/Index');
})->name('faq.index');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/rubrique/index.{extension?}', function ($extension = null) {
    return redirect('/familles/', 301);
});
Route::get('/rub-{rubriqueWithPlus}-{id}.{extension?}', function ($rubriqueWithPlus, $id, $extension = null) {
    $famille = str_replace('+', '-', strtolower($rubriqueWithPlus));
    return redirect('/familles/' . $famille, 301);
});
Route::resource('familles', FamilleController::class);

Route::get('/villes/{city}/disciplines/{discipline:slug}', [CityDisciplineController::class, 'show'], [
    'parameters' => [
        'villes' => 'city'
    ]
])->name('villes.disciplines.show');

Route::get('/discipline/index.{extension?}', function ($extension = null) {
    return redirect('/disciplines/', 301);
});
Route::get('/dis-{disciplineWithPlus}-{id}.{extension?}', function ($disciplineWithPlus, $id, $extension = null) {
    $discipline = str_replace('+', '-', strtolower($disciplineWithPlus));
    return redirect('/disciplines/' . $discipline, 301);
});
Route::resource('disciplines', DisciplineController::class);


Route::get('/localite-2/index.{extension?}', function ($extension = null) {
    return redirect('/departements/', 301);
});

Route::get('/{departementWithPlus}-{id}-2.{extension?}', function ($departementWithPlus, $id, $extension = null) {
    $departement = str_replace('+', '-', strtolower($departementWithPlus));
    return redirect('/departements/' . $id, 301);
});
Route::resource('departements', DepartementController::class);

Route::get('/localite-1/index.{extension?}', function ($extension = null) {
    return redirect('/villes/', 301);
});
Route::get('/{villeWithPlus}-{id}-1.{extension?}', function ($villeWithPlus, $id, $extension = null) {
    $ville = str_replace('+', '-', strtolower($villeWithPlus));
    return redirect('/villes/' . $id, 301);
});
Route::resource('villes', CityController::class, [
    'parameters' => [
        'villes' => 'city'
    ]
]);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/structures/{structure:slug}/activites/{activite:id}/newactivitystore', [ActiviteController::class, 'newactivitystore']);
    Route::put('/structures/{structure:slug}/activites/{activite:id}/toggleactif', [ActiviteController::class, 'toggleactif']);
    Route::resource('structures.activites', ActiviteController::class)->scoped(['structure' => 'slug','activite' => 'id']);

    Route::post('/structures/{structure:slug}/activites/{activite:id}/produits/{produit:id}/duplicate', [StructureActiviteProduitController::class, 'duplicate'])->name('produits.duplicate');
    Route::resource('structures.activites.produits', StructureActiviteProduitController::class)->scoped(['structure' => 'slug', 'activite' => 'id', 'produit' => 'id']);

    Route::post('/structures/{structure:slug}/tarifs/{tarif:id}/produits/{produit:id}/duplicate', [StructureTarifController::class, 'duplicate'])->name('tarifs.duplicate');
    Route::delete('/structures/{structure:slug}/tarifs/{tarif:id}/produits/{produit:id}', [StructureTarifController::class, 'destroy'])->name('tarifs.destroy');
    Route::delete('/structures/{structure:slug}/tarifs/{tarif:id}', [StructureTarifController::class, 'destroyTarif'])->name('tarifs.destroyTarif');

    Route::resource('structures.tarifs', StructureTarifController::class)->scoped(['structure' => 'slug','tarif' => 'id'])->only([ 'store', 'update']);
    Route::resource('structures.plannings', StructurePlanningController::class)->scoped(['structure' => 'slug','planning' => 'id'])->only(['store', 'update', 'destroy']);
    Route::resource('structures.disciplines', StructureDisciplineController::class)->scoped(['structure' => 'slug','discipline' => 'id'])->only(['destroy']);
    Route::resource('structures.categories', StructureCategorieController::class)->scoped(['structure' => 'slug','categorie' => 'id'])->only(['destroy']);

    Route::get('/structures/create', [StructureController::class, 'create'])->name('structures.create');
    Route::post('/structures', [StructureController::class, 'store'])->name('structures.store');
    Route::get('/structures/{structure:slug}/edit', [StructureController::class, 'edit'])->name('structures.edit');
    Route::put('/structures/{structure}', [StructureController::class, 'update'])->name('structures.update');
    Route::delete('/structures/{structure}', [StructureController::class, 'destroy'])->name('structures.destroy');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('structures', [StructureController::class, 'index'])
    ->name('structures.index');
Route::get('structures/{structure:slug}', [StructureController::class, 'show'])
    ->name('structures.show');


require __DIR__.'/auth.php';
