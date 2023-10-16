<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Famille\FamilleController;

Route::resource('familles', FamilleController::class)->only([
    'index', 'show'
]);

Route::get('/rubrique/index.{extension?}', function ($extension = null) {
    return redirect('/familles/', 301);
});
Route::get('/rub-{rubriqueWithPlus}-{id}.{extension?}', function ($rubriqueWithPlus, $id, $extension = null) {
    $famille = str_replace('+', '-', strtolower($rubriqueWithPlus));
    return redirect('/familles/' . $famille, 301);
});
