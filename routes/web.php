<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\JobController;
use Illuminate\Support\Facades\Route;


Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');
    Route::post('/jobs/{id}/cancel', [JobController::class, 'cancel'])->name('jobs.cancel');
    Route::delete('/jobs/{id}', [JobController::class, 'delete'])->name('jobs.delete');

});

require __DIR__.'/auth.php';
