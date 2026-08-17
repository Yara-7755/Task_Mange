<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $tasks = Auth::user()->tasks()->with(['tags', 'category'])->latest()->get();

        return response()->json([
            'tasks' => $tasks,
        ]);
    }
}
