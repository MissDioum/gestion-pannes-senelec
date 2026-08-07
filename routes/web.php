<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PanneController;
use App\Http\Controllers\SuperviseurController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TechnicienController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', function () {
    $user = Auth::user();

    return match ($user->role) {
        'abonne'         => redirect()->route('pannes.index'),
        'superviseur'    => redirect()->route('superviseur.index'),
        'technicien'     => redirect()->route('technicien.index'),
        'administrateur' => redirect()->route('admin.dashboard'),
        default          => view('dashboard'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:abonne'])->group(function () {
    Route::get('/pannes', [PanneController::class, 'index'])->name('pannes.index');
    Route::get('/pannes/historique', [PanneController::class, 'historique'])->name('pannes.historique');
    Route::post('/pannes/notifications/lues', [PanneController::class, 'marquerLues'])->name('pannes.notifs.lues');
    Route::get('/pannes/creer', [PanneController::class, 'create'])->name('pannes.create');
    Route::post('/pannes', [PanneController::class, 'store'])->name('pannes.store');
    Route::get('/pannes/{id}', [PanneController::class, 'show'])->name('pannes.show');
    Route::get('/pannes/{id}/statut', [PanneController::class, 'statut'])->name('pannes.statut');
});

Route::middleware(['auth', 'role:superviseur'])->prefix('superviseur')->group(function () {
    Route::get('/signalements', [SuperviseurController::class, 'index'])->name('superviseur.index');
    Route::get('/statistiques', [SuperviseurController::class, 'statistiques'])->name('superviseur.statistiques');
    Route::post('/signalements/{id}/affecter', [SuperviseurController::class, 'affecter'])->name('superviseur.affecter');
    Route::post('/signalements/{id}/cloturer', [SuperviseurController::class, 'cloturer'])->name('superviseur.cloturer');
    Route::post('/notifications/lues', [SuperviseurController::class, 'marquerLues'])->name('superviseur.notifs.lues');
});

Route::middleware(['auth', 'role:technicien'])->prefix('technicien')->group(function () {
    Route::get('/missions', [TechnicienController::class, 'index'])->name('technicien.index');
    Route::post('/missions/{id}/mettre-a-jour', [TechnicienController::class, 'mettreAJour'])->name('technicien.mettreAJour');
    Route::post('/missions/{id}/terminer', [TechnicienController::class, 'marquerTermine'])->name('technicien.terminer');
    Route::post('/missions/{id}/refuser', [TechnicienController::class, 'refuser'])->name('technicien.refuser');
    Route::post('/notifications/lues', [TechnicienController::class, 'marquerLues'])->name('technicien.notifs.lues');
});

Route::middleware(['auth', 'role:administrateur'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/utilisateurs', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/utilisateurs/creer', [AdminController::class, 'create'])->name('admin.create');
    Route::post('/utilisateurs', [AdminController::class, 'store'])->name('admin.store');
    Route::get('/utilisateurs/{id}/modifier', [AdminController::class, 'edit'])->name('admin.edit');
    Route::patch('/utilisateurs/{id}', [AdminController::class, 'update'])->name('admin.update');
    Route::delete('/utilisateurs/{id}', [AdminController::class, 'destroy'])->name('admin.destroy');
    Route::get('/signalements/export', [AdminController::class, 'exportSignalements'])->name('admin.export');
});

require __DIR__.'/auth.php';
