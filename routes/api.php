<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Famille\FamilleController;
use App\Http\Controllers\Discipline\DisciplineController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/familles', [FamilleController::class, 'loadFamilles']);
Route::get('/listdisciplines/{id}', [DisciplineController::class, 'getCategories']);
Route::get('/listdisciplines_similaires/{id}', [DisciplineController::class, 'getDisciplinesSimilaires']);

Route::get('/disciplines', [DisciplineController::class,'loadDisciplines']);
