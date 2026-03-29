<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    Route::resource('invitations', InvitationController::class);
    Route::resource('guests', GuestController::class);
    Route::get('invitations/{invitation}/guests', [GuestController::class, 'indexByInvitation'])->name('guests.byInvitation');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
