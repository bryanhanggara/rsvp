<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\EventController;

Route::get('/', function () {
    return view('welcome');
});


Route::prefix('admin')->middleware('auth')->group(function(){
    Route::get('/', [HomeController::class, 'index'])->name('dashboard.admin');
    Route::resource('/event', EventController::class);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
