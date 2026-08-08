<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ProfileController;
use App\Models\Task;
use Carbon\Carbon;
Route::get('/dashboard', function () {
    $todaysTasks = auth()->user()->tasks()
        ->whereDate('date', now()->toDateString())
        ->get();

    return view('dashboard', compact('todaysTasks'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/tasks/{task}/add-time', [TaskController::class, 'addTime']);
});

Route::middleware('auth')->group(function () {
    Route::get('/tasks/create', [TaskController::class, 'create']);
    Route::post('/tasks', [TaskController::class, 'store']);
    Route::get('/tasks', [TaskController::class, 'index']);
    Route::patch('/tasks/{task}/toggle', [TaskController::class, 'toggleComplete']);
});

Route::middleware(['auth', 'check.task.owner'])->group(function () {
    Route::put('/tasks/{task}', [TaskController::class, 'update']);
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);
});

require __DIR__.'/auth.php';
