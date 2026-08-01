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
            'name'        => 'required|string',
            'description' => 'nullable|string',
            'date'        => 'nullable|date|after_or_equal:today',
            'category_id' => 'required|exists:categories,id',
        ], [
            'date.after_or_equal' => 'Check date...',

        ]);

        Task::create([
            'name' => $request->name,
            'description' => $request->description,
            'date' => $request->date,
            'category_id' => $request->category_id,
            'completed' => $request->has('completed'),
            'user_id' => Auth::id(),   // ✅ هلأ بياخد id المستخدم الحقيقي المسجل دخوله
        ]);


        return redirect('/tasks')->with('success',  'done saved');
    }

    public function index()
    {
        $tasks = Auth::user()->tasks;
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

    public function toggleComplete(Task $task)
    {
        $task->update([
            'completed' => ! $task->completed,
        ]);

        return back();
    }


    public function update(Request $request, Task $task)
    {
        $request->validate([
            'name'        => 'required|string',
            'description' => 'nullable|string',
            'date'        => 'nullable|date|after_or_equal:today',
            'category_id' => 'required|exists:categories,id',
        ]);

        $task->update([
            'name'        => $request->name,
            'description' => $request->description,
            'date'        => $request->date,
            'category_id' => $request->category_id,
            'completed'   => $request->has('completed'),
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
