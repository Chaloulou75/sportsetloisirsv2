<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\StructureController;
use App\Http\Controllers\DisciplineController;

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

Route::resource('category', CategoryController::class);
Route::resource('discipline', DisciplineController::class);

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/structure/create', [StructureController::class, 'create'])->name('structure.create');
    Route::post('/structure', [StructureController::class, 'store'])->name('structure.store');
    Route::delete('/structure/{structure}', [StructureController::class, 'destroy'])->name('structure.destroy');

    Route::get('/profile/structures', [ProfileController::class, 'messtructures'])->name('profile.structures');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('structure', [StructureController::class, 'index'])
    ->name('structure.index');
Route::get('structure/{structure:slug}', [StructureController::class, 'show'])
    ->name('structure.show');

require __DIR__.'/auth.php';
