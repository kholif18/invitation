<?php

use App\Http\Controllers\InvitationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    // Invitations Routes
    Route::prefix('invitations')->name('invitations.')->group(function () {
        Route::get('/', [InvitationController::class, 'index'])->name('index');
        Route::get('/create', [InvitationController::class, 'create'])->name('create');
        Route::post('/', [InvitationController::class, 'store'])->name('store');
        Route::get('/templates', [InvitationController::class, 'templates'])->name('templates');
        Route::get('/statistics', [InvitationController::class, 'statistics'])->name('statistics');
        Route::get('/export', [InvitationController::class, 'export'])->name('export');
        Route::get('/bulk-statistics', [InvitationController::class, 'bulkStatistics'])->name('bulk-statistics');
        
        Route::prefix('{id}')->group(function () {
            Route::get('/', [InvitationController::class, 'show'])->name('show');
            Route::get('/edit', [InvitationController::class, 'edit'])->name('edit');
            Route::put('/', [InvitationController::class, 'update'])->name('update');
            Route::delete('/', [InvitationController::class, 'destroy'])->name('destroy');
            Route::post('/send', [InvitationController::class, 'send'])->name('send');
            Route::post('/duplicate', [InvitationController::class, 'duplicate'])->name('duplicate');
        });
    });
    Route::resource('guests', GuestController::class);
    // Route::get('invitations/{invitation}/guests', [GuestController::class, 'indexByInvitation'])->name('guests.byInvitation');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
