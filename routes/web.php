<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LivreController;


Route::get('/livres/{livre}', [LivreController::class, 'show'])->name('livres.show');
Route::get('/', [LivreController::class, 'index'])->name('livres.index');
Route::post('/livres/{livre}/reserve', [LivreController::class, 'reserve'])->name('livres.reserve');

