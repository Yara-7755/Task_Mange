<?php

namespace App\Http\Controllers\Api\tasks;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CreateTaskController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date'    => 'nullable|date',
            'category_id' => 'required|exists:categories,id',
        ]);

        $task = $request->user()->tasks()->create([
            'name'        => $validated['name'],
            'description' => $validated['description'] ?? null,
            'due_date'    => $validated['due_date'] ?? null,
            'category_id' => $validated['category_id'],
        ]);

        return response()->json([
            'message' => 'Task created successfully',
            'task'    => $task
        ], 201);
    }
}
