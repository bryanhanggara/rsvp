<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\RsvpController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\AkumulasiController;
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
    Route::get('/leaderboard-month', [HomeController::class, 'monthlyLeaderboard'])->name('admin.leaderboard.month');
    Route::get('/points', [HomeController::class, 'pointsByMonth'])->name('admin.pointsByMonth');
    Route::get('/admin/total-point-event-per-bulan', [AkumulasiController::class, 'totalPointEventPerBulan'])->name('admin.total.point.event.perbulan');
    Route::get('/admin/export-points', [HomeController::class, 'exportPoints'])->name('admin.exportPoints');
});

Route::group(['middleware' => ['auth', 'verified']], function () {
    Route::get('/', [HomeUserController::class, 'index'])->name('dashboard.user');
    Route::get('/detail-acara/{eventId}', [HomeUserController::class, 'show'])->name('show.acara');
    Route::get('/riwayat-rsvp', [HomeUserController::class, 'historyRsvp'])->name('show.history');
    Route::get('/leaderboard-periode', [HomeUserController::class, 'leaderboardPeriode'])->name('user.leaderboard.periode');
    Route::get('/leaderboard-month', [HomeUserController::class, 'leaderboardMonth'])->name('user.leaderboard.month');
    Route::post('/rsvp', [RsvpController::class, 'store'])->name('rsvp');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
