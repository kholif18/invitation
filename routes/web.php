<?php

use App\Http\Controllers\Admin\CommunicationController;
use App\Http\Controllers\Admin\EmailCampaignController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\LinkController;
use App\Http\Controllers\Admin\MessageTemplateController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WhatsAppController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\RSVPController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('dashboard', [AdminController::class, 'index'])->name('dashboard');

    // Invitations Routes
    Route::prefix('invitations')->name('invitations.')->group(function () {
        Route::get('/', [InvitationController::class, 'index'])->name('index');
        Route::get('/create', [InvitationController::class, 'create'])->name('create');
        Route::post('/', [InvitationController::class, 'store'])->name('store');
        Route::get('/templates', [InvitationController::class, 'templates'])->name('templates');
        Route::get('/statistics', [InvitationController::class, 'statistics'])->name('statistics');
        Route::get('/export', [InvitationController::class, 'export'])->name('export');
        Route::get('/bulk-statistics', [InvitationController::class, 'bulkStatistics'])->name('bulk-statistics');
    
        
        // Link management routes
        Route::controller(LinkController::class)->group(function () {
            Route::get('/links', 'index')->name('links');
            Route::get('/{invitationId}/links', 'show')->name('links.show');
            Route::post('/links/generate', 'generateLinks')->name('links.generate');
            Route::get('/links/{invitationId}/data', 'getLinks')->name('links.get');
            Route::post('/links/send', 'sendLink')->name('links.send');
            Route::post('/links/bulk-send', 'bulkSendLinks')->name('links.bulk-send');
            Route::get('/links/statistics/{invitationId}', 'getStatistics')->name('links.statistics');
            Route::get('/links/export/{invitationId}', 'exportLinks')->name('links.export');
            Route::post('/links/{linkId}/revoke', 'revokeLink')->name('links.revoke');
            Route::post('/links/{linkId}/regenerate', 'regenerateLink')->name('links.regenerate');
        });
        
        Route::prefix('{id}')->group(function () {
            Route::get('/', [InvitationController::class, 'show'])->name('show');
            Route::get('/edit', [InvitationController::class, 'edit'])->name('edit');
            Route::put('/', [InvitationController::class, 'update'])->name('update');
            Route::delete('/', [InvitationController::class, 'destroy'])->name('destroy');
            Route::post('/send', [InvitationController::class, 'send'])->name('send');
            Route::post('/duplicate', [InvitationController::class, 'duplicate'])->name('duplicate');
        });
    });

    // RSVP Routes
    Route::prefix('rsvp')->name('rsvp.')->group(function () {
        // Main RSVP management page
        Route::get('/', [RSVPController::class, 'index'])->name('index');
        
        // Get RSVP data (for AJAX)
        Route::get('/data', [RSVPController::class, 'getData'])->name('data');
        Route::get('/statistics', [RSVPController::class, 'getStatistics'])->name('statistics');
        
        // CRUD operations
        Route::get('/{id}', [RSVPController::class, 'show'])->name('show');
        Route::put('/{id}', [RSVPController::class, 'update'])->name('update');
        Route::delete('/{id}', [RSVPController::class, 'destroy'])->name('destroy');
        
        // Bulk operations
        Route::post('/bulk/update', [RSVPController::class, 'bulkUpdate'])->name('bulk.update');
        Route::post('/bulk/delete', [RSVPController::class, 'bulkDelete'])->name('bulk.delete');
        
        // Reminder operations
        Route::post('/reminders/send', [RSVPController::class, 'sendReminders'])->name('reminders.send');
        Route::post('/{id}/reminder', [RSVPController::class, 'sendReminder'])->name('reminder.send');
        
        // Export operations
        Route::get('/export/csv', [RSVPController::class, 'exportCSV'])->name('export.csv');
        Route::get('/export/excel', [RSVPController::class, 'exportExcel'])->name('export.excel');
        Route::get('/export/pdf', [RSVPController::class, 'exportPDF'])->name('export.pdf');
        
        // Report generation
        Route::get('/report', [RSVPController::class, 'generateReport'])->name('report');
        
        // Seating arrangement
        Route::get('/seating', [RSVPController::class, 'seating'])->name('seating');
        Route::post('/seating/assign', [RSVPController::class, 'assignSeating'])->name('seating.assign');
        
        // Filter by invitation
        Route::get('/invitation/{invitationId}', [RSVPController::class, 'getByInvitation'])->name('by-invitation');
    });
    
    Route::resource('guests', GuestController::class);
    // Route::get('invitations/{invitation}/guests', [GuestController::class, 'indexByInvitation'])->name('guests.byInvitation');
    
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo.update');
    Route::get('/profile/activity', [ProfileController::class, 'activityLog'])->name('profile.activity');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('users', UserController::class);
    Route::post('users/bulk-delete', [UserController::class, 'bulkDelete'])->name('users.bulk-delete');
    Route::post('users/{id}/change-password', [UserController::class, 'changePassword'])->name('users.change-password');

    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingController::class, 'edit'])->name('edit');
        Route::put('/', [SettingController::class, 'update'])->name('update');
    });
});

require __DIR__.'/auth.php';
