<?php

use App\Http\Controllers\Admin\GuestController;
use App\Http\Controllers\Admin\InvitationController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TemplateController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\InvitationViewController;
use App\Http\Controllers\RSVPController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Public invitation viewing routes - Let// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');akkan di luar middleware auth
Route::get('invitation/{slug}', [InvitationViewController::class, 'show'])->name('invitation.show');
Route::get('invitation/{slug}/{code}', [InvitationViewController::class, 'show'])->name('invitation.show.withCode');
Route::post('invitation/{slug}/wish', [InvitationViewController::class, 'sendWish'])->name('invitation.wish');

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('dashboard', [AdminController::class, 'index'])->name('dashboard');

    // Template management
    Route::get('templates/select', [TemplateController::class, 'select'])->name('templates.select');
    Route::post('templates/{template}/set-default', [TemplateController::class, 'setDefault'])->name('templates.set-default');
    Route::get('templates/{template}/preview', [TemplateController::class, 'preview'])->name('templates.preview');
    Route::get('templates/{template}/preview-iframe', [TemplateController::class, 'previewIframe'])->name('templates.preview-iframe');
    Route::get('templates/{template}/download', [TemplateController::class, 'download'])->name('templates.download');
    Route::resource('templates', TemplateController::class);

    Route::resource('invitations', InvitationController::class);
    Route::post('invitations/{invitation}/duplicate', [InvitationController::class, 'duplicate'])->name('invitations.duplicate');
    Route::get('invitations/{invitation}/customize-template', [InvitationController::class, 'customizeTemplate'])->name('invitations.customize-template');
    Route::put('invitations/{invitation}/update-template-settings', [InvitationController::class, 'updateTemplateSettings'])->name('invitations.update-template-settings');
    
    // Guest management routes
    Route::prefix('invitations/{invitation}/guests')->name('invitations.guests.')->group(function () {
        Route::get('/', [GuestController::class, 'index'])->name('index');
        Route::get('/create', [GuestController::class, 'create'])->name('create');
        Route::post('/', [GuestController::class, 'store'])->name('store');
        Route::get('/{guest}/edit', [GuestController::class, 'edit'])->name('edit');
        Route::put('/{guest}', [GuestController::class, 'update'])->name('update');
        Route::delete('/{guest}', [GuestController::class, 'destroy'])->name('destroy');
        Route::post('/{guest}/send', [GuestController::class, 'sendInvitation'])->name('send');
        Route::post('/send-bulk', [GuestController::class, 'sendBulk'])->name('send-bulk');
        Route::get('/import', [GuestController::class, 'import'])->name('import');
        Route::post('/import', [GuestController::class, 'processImport'])->name('process-import');
        Route::get('/export', [GuestController::class, 'export'])->name('export');
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
