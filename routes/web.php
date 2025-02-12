<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\RsvpController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\User\HomeController as HomeUserController;

// Route::get('/', function () {
//     return view('welcome');
// });


Route::prefix('admin')->middleware('auth','isAdmin')->group(function(){
    Route::get('/', [HomeController::class, 'index'])->name('dashboard.admin');
    Route::resource('/event', EventController::class);
    Route::put('/rsvp/updateStatus/{id}', [RsvpController::class, 'updateStatus'])->name('rsvp.updateStatus');
    Route::put('/rsvp/bulkUpdateStatus', [RsvpController::class, 'bulkUpdateStatus'])->name('rsvp.bulkUpdateStatus');
    Route::post('/event/{eventId}/deduct-points/{userId}', [RsvpController::class, 'deductPointsForNonRsvp'])->name('event.deductPoints');
    Route::get('/ranking', [HomeController::class, 'rankingBeswan'])->name('ranking.index');
    Route::get('/points', [HomeController::class, 'pointsByMonth'])->name('admin.pointsByMonth');


});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', [HomeUserController::class, 'index'])->name('dashboard.user');
    Route::get('/detail-acara/{eventId}', [HomeUserController::class, 'show'])->name('show.acara');
    Route::get('/riwayat-rsvp', [HomeUserController::class, 'historyRsvp'])->name('show.history');
    Route::post('/rsvp', [RsvpController::class, 'store'])->name('rsvp');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
