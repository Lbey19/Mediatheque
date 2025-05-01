<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LivreController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CdController; // <<< AJOUTER CETTE LIGNE
use App\Http\Controllers\HomeController; // <<< AJOUTER SI MANQUANT

/*
|--------------------------------------------------------------------------
| Routes publiques
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/cds', [CdController::class, 'index'])
->name('cds.index'); //

Route::get('/dvds', function () {
    return view('dvds.index');
})->name('dvds.index');

/*
|--------------------------------------------------------------------------
| Routes Livres
|--------------------------------------------------------------------------
*/

Route::get('/livres', [LivreController::class, 'index'])->name('livres.index');
Route::get('/livres/{livre}', [LivreController::class, 'show'])->name('livres.show');

Route::post('/livres/{livre}/reserve', [LivreController::class, 'reserve'])
    ->middleware('auth')
    ->name('livres.reserve');


/*
|--------------------------------------------------------------------------
| Routes CDs (Nouvelles routes)
|--------------------------------------------------------------------------
*/
// La route pour la liste /cds est déjà définie plus haut (publique)
Route::get('/cds/{cd}', [CdController::class, 'show'])->name('cds.show'); // <<< AJOUTER CETTE LIGNE (pour les détails)
// La route pour la réservation sera ajoutée ici plus tard et nécessitera ->middleware('auth')
Route::post('/cds/{cd}/reserve', [CdController::class, 'reserve'])
    ->middleware('auth') // La réservation nécessite d'être connecté
    ->name('cds.reserve');
/*
|--------------------------------------------------------------------------
| Routes pour utilisateurs authentifiés
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
});

/*
|--------------------------------------------------------------------------
| Auth Laravel Breeze
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';
