<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ProfileController;
use App\Models\Task;
use Carbon\Carbon;

Route::get('/', function () {
    return redirect('/login');
});


Route::get('/dashboard', function () {
    $allTasks = \Illuminate\Support\Facades\Auth::user()->tasks;

    $pendingTasks = $allTasks->where('completed', false)->filter(function ($task) {
        return !$task->date || !\Carbon\Carbon::parse($task->date)->isPast();
    });

    $expiredTasks = $allTasks->where('completed', false)->filter(function ($task) {
        return $task->date && \Carbon\Carbon::parse($task->date)->isPast();
    });

    $completedTodayCount = $allTasks->where('completed', true)->filter(function ($task) {
        return $task->completed_at && \Carbon\Carbon::parse($task->completed_at)->isToday();
    })->count();

    $highPriorityTasks = $pendingTasks->where('priority', 'high');

    $todaysTasks = $pendingTasks->filter(function ($task) {
        return $task->date && \Carbon\Carbon::parse($task->date)->isToday();
    });

    $topTags = \App\Models\Tag::withCount(['tasks' => function ($query) {
        $query->where('user_id', \Illuminate\Support\Facades\Auth::id());
    }])
        ->get()
        ->filter(function ($tag) {
            return $tag->tasks_count > 0;
        })
        ->sortByDesc('tasks_count')
        ->take(5);

    $pendingCount = $pendingTasks->count();
    $expiredCount = $expiredTasks->count();

    return view('dashboard', compact(
        'pendingTasks',
        'pendingCount',
        'expiredCount',
        'completedTodayCount',
        'highPriorityTasks',
        'topTags',
        'todaysTasks'
    ));
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
    Route::delete('/tasks/clear-expired', [TaskController::class, 'clearExpired']);
});

Route::middleware(['auth', 'check.task.owner'])->group(function () {
    Route::put('/tasks/{task}', [TaskController::class, 'update']);
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);
});

require __DIR__.'/auth.php';
