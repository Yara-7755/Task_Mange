<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TaskController extends Controller
{
    public function create()
    {
        $categories = Category::all();
        return view('tasks.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'                  => 'required|string',
            'description'           => 'nullable|string',
            'date'                  => 'nullable|date|after_or_equal:today',
            'category_id'           => 'required|exists:categories,id',
            'priority'              => 'required|in:low,medium,high',
            'repeat_type'           => 'nullable|in:none,daily,weekly,custom',
            'repeat_interval_value' => 'nullable|integer|min:1',
            'repeat_interval_unit'  => 'nullable|in:minutes,hours',
        ]);

        $repeatMinutes = null;
        if ($request->repeat_type === 'custom' && $request->repeat_interval_value) {
            $repeatMinutes = $request->repeat_interval_unit === 'hours'
                ? $request->repeat_interval_value * 60
                : $request->repeat_interval_value;
        }

        Task::create([
            'name'                    => $request->name,
            'description'             => $request->description,
            'date'                    => $request->date,
            'category_id'             => $request->category_id,
            'completed'               => $request->has('completed'),
            'priority'                => $request->priority,
            'repeat_type'             => $request->repeat_type ?? 'none',
            'repeat_interval_minutes' => $repeatMinutes,
            'user_id'                 => Auth::id(),
        ]);

        return redirect('/tasks')->with('success', 'done saved');
    }

    public function index(Request $request)
    {
        $query = Auth::user()->tasks();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $tasks = $query->get();
        $categories = Category::all();

        $completedTasks = $tasks->where('completed', true);

        $expiredTasks = $tasks->where('completed', false)->filter(function ($task) {
            return $task->date && Carbon::parse($task->date)->isPast();
        });

        $pendingTasks = $tasks->where('completed', false)->reject(function ($task) {
            return $task->date && Carbon::parse($task->date)->isPast();
        });

        return view('tasks.index', compact(
            'categories',
            'completedTasks',
            'expiredTasks',
            'pendingTasks'
        ));
    }
    public function addTime(Request $request, Task $task)
    {
        $task->increment('time_spent', $request->seconds);

        return response()->json(['success' => true]);
    }

    public function toggleComplete(Task $task)
    {
        $completed = ! $task->completed;

        $task->update([
            'completed'   => $completed,
            'completed_at'=> $completed ? now() : null,
            'repeated'    => false,
        ]);

        return back();
    }
    public function update(Request $request, Task $task)
    {
        $request->validate([
            'name'                  => 'required|string',
            'description'           => 'nullable|string',
            'date'                  => 'nullable|date',
            'category_id'           => 'required|exists:categories,id',
            'priority'              => 'required|in:low,medium,high',
            'repeat_type'           => 'nullable|in:none,daily,weekly,custom',
            'repeat_interval_value' => 'nullable|integer|min:1',
            'repeat_interval_unit'  => 'nullable|in:minutes,hours',
        ]);

        $repeatMinutes = null;
        if ($request->repeat_type === 'custom' && $request->repeat_interval_value) {
            $repeatMinutes = $request->repeat_interval_unit === 'hours'
                ? $request->repeat_interval_value * 60
                : $request->repeat_interval_value;
        }

        $task->update([
            'name'                    => $request->name,
            'description'             => $request->description,
            'date'                    => $request->date,
            'category_id'             => $request->category_id,
            'completed'               => $request->has('completed'),
            'priority'                => $request->priority,
            'repeat_type'             => $request->repeat_type ?? 'none',
            'repeat_interval_minutes' => $repeatMinutes,
        ]);

        return back()->with('success', 'Task updated successfully');
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return redirect('/tasks')
            ->with('success', 'Task deleted successfully');
    }
}
