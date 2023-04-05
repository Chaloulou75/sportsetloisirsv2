<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\CityController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ActiviteController;
use App\Http\Controllers\CategoryController;
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

Route::resource('categories', CategoryController::class);
Route::resource('disciplines', DisciplineController::class);
Route::resource('departements', DepartementController::class);

Route::get('villes/{city}/disciplines/{discipline}', [CityDisciplineController::class, 'show'])->name('citydiscipline.show');

Route::resource('villes', CityController::class, [
    'parameters' => [
        'villes' => 'city'
    ]
]);

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/structures/create', [StructureController::class, 'create'])->name('structures.create');
    Route::post('/structures', [StructureController::class, 'store'])->name('structures.store');
    Route::delete('/structures/{structure}', [StructureController::class, 'destroy'])->name('structures.destroy');

    Route::resource('/structures/{structure}/activites', ActiviteController::class);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('structures', [StructureController::class, 'index'])
    ->name('structures.index');
Route::get('structures/{structure:slug}', [StructureController::class, 'show'])
    ->name('structures.show');

Route::get('structures/{structure:slug}/activites/create', [ActiviteController::class, 'create'])
    ->name('activites.create');


require __DIR__.'/auth.php';
