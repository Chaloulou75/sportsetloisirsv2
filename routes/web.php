<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\CityController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\FamilleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ActiviteController;
use App\Http\Controllers\FavoritesController;
use App\Http\Controllers\StructureController;
use App\Http\Controllers\DisciplineController;
use App\Http\Controllers\DepartementController;
use App\Http\Controllers\CityDisciplineController;
use App\Http\Controllers\StructureTarifController;
use App\Http\Controllers\FamilleDisciplineController;
use App\Http\Controllers\StructurePlanningController;
use App\Http\Controllers\CategoryDisciplineController;
use App\Http\Controllers\ProductReservationController;
use App\Http\Controllers\StructureCategorieController;
use App\Http\Controllers\DisciplineSimilaireController;
use App\Http\Controllers\StructureDisciplineController;
use App\Http\Controllers\DepartementDisciplineController;
use App\Http\Controllers\CityDisciplineCategorieController;
use App\Http\Controllers\StructureActiviteProduitController;
use App\Http\Controllers\CategoryDisciplineCritereController;
use App\Http\Controllers\CityDisciplineStructuretypeController;
use App\Http\Controllers\DepartementDisciplineCategorieController;
use App\Http\Controllers\CategoryDisciplineCritereValeurController;
use App\Http\Controllers\CityDisciplineCategorieStructureController;
use App\Http\Controllers\DepartementDisciplineStructuretypeController;
use App\Http\Controllers\CityDisciplineStructuretypeStructureController;
use App\Http\Controllers\CityDisciplineCategorieStructureActiviteController;
use App\Http\Controllers\CityDisciplineStructuretypeStructureActiviteController;

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

Route::resource('favoris', FavoritesController::class)->only([
    'index'
]);

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
Route::resource('familles', FamilleController::class)->only([
    'index', 'show'
]);

Route::get('/villes/{city}/disciplines/{discipline:slug}', [CityDisciplineController::class, 'show'], [
    'parameters' => [
        'villes' => 'city'
    ]
])->name('villes.disciplines.show');

Route::get('/villes/{city}/disciplines/{discipline:slug}/categories/{category:id}', [CityDisciplineCategorieController::class, 'show'])->name('villes.disciplines.categories.show');

Route::get('/villes/{city}/disciplines/{discipline:slug}/structuretypes/{structuretype:id}', [CityDisciplineStructuretypeController::class, 'show'])->name('villes.disciplines.structuretypes.show');

Route::get('/villes/{city}/disciplines/{discipline:slug}/categories/{category:id}/structures/{structure}', [CityDisciplineCategorieStructureController::class, 'show'])->name('villes.disciplines.categories.structures.show');

Route::get('/villes/{city}/disciplines/{discipline:slug}/structuretypes/{structuretype:id}/structures/{structure}', [CityDisciplineStructuretypeStructureController::class, 'show'])->name('villes.disciplines.structuretypes.structures.show');


Route::get('/villes/{city}/disciplines/{discipline:slug}/categories/{category:id}/structures/{structure}/activites/{activite:id}', [CityDisciplineCategorieStructureActiviteController::class, 'show'])->name('villes.disciplines.categories.structures.activites.show');

Route::get('/villes/{city}/disciplines/{discipline:slug}/structuretypes/{structuretype:id}/structures/{structure}/activites/{activite:id}', [CityDisciplineStructuretypeStructureActiviteController::class, 'show'])->name('villes.disciplines.structuretypes.structures.activites.show');


Route::get('/discipline/index.{extension?}', function ($extension = null) {
    return redirect('/disciplines/', 301);
});
Route::get('/dis-{disciplineWithPlus}-{id}.{extension?}', function ($disciplineWithPlus, $id, $extension = null) {
    $discipline = str_replace('+', '-', strtolower($disciplineWithPlus));
    return redirect('/disciplines/' . $discipline, 301);
});
Route::resource('disciplines', DisciplineController::class)->only([
    'index', 'show'
]);

Route::get('/localite-2/index.{extension?}', function ($extension = null) {
    return redirect('/departements/', 301);
});

Route::get('/{departementWithPlus}-{id}-2.{extension?}', function ($departementWithPlus, $id, $extension = null) {
    $departement = str_replace('+', '-', strtolower($departementWithPlus));
    return redirect('/departements/' . $id, 301);
});
Route::resource('departements', DepartementController::class)->only([
    'index', 'show'
]);

Route::get('/departements/{departement}/disciplines/{discipline:slug}', [DepartementDisciplineController::class, 'show'])->name('departements.disciplines.show');

Route::get('/departements/{departement}/disciplines/{discipline:slug}/categories/{category:id}', [DepartementDisciplineCategorieController::class, 'show'])->name('departements.disciplines.categories.show');

Route::get('/departements/{departement}/disciplines/{discipline:slug}/structuretypes/{structuretype:id}', [DepartementDisciplineStructuretypeController::class, 'show'])->name('departements.disciplines.structuretypes.show');


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
])->only([
    'index', 'show'
]);

Route::resource('product_reservations', ProductReservationController::class)->only([
    'store'
]);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/structures/{structure:slug}/activites/{activite:id}/newactivitystore', [ActiviteController::class, 'newactivitystore']);
    Route::put('/structures/{structure:slug}/activites/{activite:id}/toggleactif', [ActiviteController::class, 'toggleactif']);
    Route::resource('structures.activites', ActiviteController::class)->scoped(['structure' => 'slug','activite' => 'id'])->except('show');

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

    // Admin routes

    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/admin/{discipline:slug}', [AdminController::class, 'edit'])->name('admin.edit');

    Route::post('/discipline-similaire/{discipline}', [DisciplineSimilaireController::class, 'store'])->name('discipline-similaire.store');
    Route::put('/discipline-similaire/{discipline}', [DisciplineSimilaireController::class, 'detach'])->name('discipline-similaire.detach');
    Route::put('/disciplines/{discipline:slug}', [DisciplineController::class, 'update'])->name('disciplines.update');

    Route::post('/familles-disciplines/{discipline}', [FamilleDisciplineController::class, 'store'])->name('familles-disciplines.store');
    Route::put('/familles-disciplines/{discipline}', [FamilleDisciplineController::class, 'detach'])->name('familles-disciplines.detach');


    Route::post('/categories-disciplines/{discipline}', [CategoryDisciplineController::class, 'store'])->name('categories-disciplines.store');
    Route::put('/categories-disciplines/{discipline}', [CategoryDisciplineController::class, 'detach'])->name('categories-disciplines.detach');
    Route::patch('/categories-disciplines/{discipline}', [CategoryDisciplineController::class, 'update'])->name('categories-disciplines.update');

    Route::post('/categories-disciplines-criteres', [CategoryDisciplineCritereController::class, 'store'])->name('categories-disciplines-criteres.store');
    Route::delete('/categories-disciplines-criteres/{lienDisciplineCategorieCritere}', [CategoryDisciplineCritereController::class, 'destroy'])->name('categories-disciplines-criteres.destroy');

    Route::post('/categories-disciplines-criteres-valeurs/{critere}', [CategoryDisciplineCritereValeurController::class, 'store'])->name('categories-disciplines-criteres-valeurs.store');
    Route::patch('/categories-disciplines-criteres-valeurs/{lienDisCatCritValeur}', [CategoryDisciplineCritereValeurController::class, 'update'])->name('categories-disciplines-criteres-valeurs.update');
    Route::delete('/categories-disciplines-criteres-valeurs/{lienDisCatCritValeur}', [CategoryDisciplineCritereValeurController::class, 'destroy'])->name('categories-disciplines-criteres-valeurs.destroy');


    //, 'can:viewAdmin'
});

Route::get('structures', [StructureController::class, 'index'])
    ->name('structures.index');
Route::get('structures/{structure:slug}', [StructureController::class, 'show'])
    ->name('structures.show');
Route::get('/structures/{structure:slug}/activites/{activite:id}', [ActiviteController::class, 'show'])->name('structures.activites.show');


require __DIR__.'/auth.php';
