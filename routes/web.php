<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ProfileController;
use Carbon\Carbon;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\InvitationController;


Route::get('/', function () {
    return redirect('/login');
});


/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {

    $allTasks = Auth::user()->tasks;

    // Pending tasks
    $pendingTasks = $allTasks
        ->where('completed', false)
        ->filter(function ($task) {
            return !$task->date || !Carbon::parse($task->date)->isPast();
        });

    // Expired tasks
    $expiredTasks = $allTasks
        ->where('completed', false)
        ->filter(function ($task) {
            return $task->date && Carbon::parse($task->date)->isPast();
        });

    // Completed today
    $completedTodayCount = $allTasks
        ->where('completed', true)
        ->filter(function ($task) {
            return $task->completed_at &&
                Carbon::parse($task->completed_at)->isToday();
        })
        ->count();

    // High priority tasks
    $highPriorityTasks = $pendingTasks->where('priority', 'high');

    // Today's tasks
    $todaysTasks = $pendingTasks->filter(function ($task) {
        return $task->date &&
            Carbon::parse($task->date)->isToday();
    });

    // Top tags
    $topTags = \App\Models\Tag::withCount([
        'tasks' => function ($query) {
            $query->where('user_id', Auth::id());
        }
    ])
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


/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');


    /*
    |--------------------------------------------------------------------------
    | Focus Timer
    |--------------------------------------------------------------------------
    */

    Route::get('/tasks/timer', function () {

        $pendingTasks = Auth::user()->tasks
            ->where('completed', false)
            ->filter(function ($task) {
                return !$task->date ||
                    !Carbon::parse($task->date)->isPast();
            });

        return view('tasks.timer', compact('pendingTasks'));

    })->name('tasks.timer');


    /*
    |--------------------------------------------------------------------------
    | Task Time
    |--------------------------------------------------------------------------
    */

    Route::post('/tasks/{task}/add-time', [TaskController::class, 'addTime']);


    /*
    |--------------------------------------------------------------------------
    | Tasks
    |--------------------------------------------------------------------------
    */

    Route::get('/tasks/create', [TaskController::class, 'create']);

    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');

    Route::get('/tasks', [TaskController::class, 'index']);

    Route::patch('/tasks/{task}/toggle', [TaskController::class, 'toggleComplete']);

    Route::delete('/tasks/clear-expired', [TaskController::class, 'clearExpired']);

});


/*
|--------------------------------------------------------------------------
| Task Owner Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'check.task.owner'])->group(function () {

    Route::put('/tasks/{task}', [TaskController::class, 'update']);

    Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);

});


/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    // Group Routes
    Route::get('/groups', [GroupController::class, 'index'])->name('groups.index');
    Route::get('/groups/create', [GroupController::class, 'create'])->name('groups.create');
    Route::post('/groups', [GroupController::class, 'store'])->name('groups.store');
    Route::get('/groups/{group}', [GroupController::class, 'show'])->name('groups.show');
    Route::delete('/groups/{group}/members/{user}', [GroupController::class, 'removeMember'])->name('groups.remove-member');
});


Route::middleware(['auth'])->group(function () {
    // Invitation Routes
    Route::post('/groups/{group}/invitations', [InvitationController::class, 'sendInvitation'])->name('invitations.send');
    Route::post('/invitations/accept/{token}', [InvitationController::class, 'accept'])->name('invitations.accept');

});

Route::middleware('auth')->group(function () {
    Route::post('/groups/{group}/invite', [InvitationController::class, 'send'])->name('invitations.send');
    Route::get('/invitations/{token}', [InvitationController::class, 'show'])->name('invitations.show');
    Route::post('/invitations/{token}/accept', [InvitationController::class, 'accept'])->name('invitations.accept');
    Route::post('/invitations/{token}/decline', [InvitationController::class, 'decline'])->name('invitations.decline');
});
require __DIR__.'/auth.php';
