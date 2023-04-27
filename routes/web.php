<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\CityController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ActiviteController;
use App\Http\Controllers\FamilleController;
use App\Http\Controllers\StructureController;
use App\Http\Controllers\DisciplineController;
use App\Http\Controllers\DepartementController;
use App\Http\Controllers\CityDisciplineController;

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

Route::resource('familles', FamilleController::class);
Route::resource('disciplines', DisciplineController::class);
Route::resource('departements', DepartementController::class);

Route::resource('villes', CityController::class, [
    'parameters' => [
        'villes' => 'city'
    ]
]);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('structures.activites', ActiviteController::class)->scoped(['structure' => 'slug','activite' => 'id']);

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
