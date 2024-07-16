<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MonitorController;
use App\Http\Controllers\QueueController;

Route::get('/', [MonitorController::class, 'index'])->name('monitor.index');

Route::get('/dashboard', [QueueController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/dashboard/next', [QueueController::class, 'next'])->name('queues.next');
    Route::post('/dashboard/repeat', [QueueController::class, 'repeat'])->name('queues.repeat');
    Route::post('/dashboard/recall', [QueueController::class, 'recall'])->name('queues.recall');
    Route::post('/dashboard/reset', [QueueController::class, 'reset'])->name('queues.reset');
    Route::post('/dashboard/add-loket', [QueueController::class, 'addLoket'])->name('add.loket');
    Route::delete('/dashboard/remove-loket', [QueueController::class, 'removeLoket'])->name('remove.loket');
});

require __DIR__.'/auth.php';
